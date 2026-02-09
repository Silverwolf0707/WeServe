<?php

namespace App\Http\Controllers\Admin;

use App\Events\PatientRecordCreated;
use App\Events\PatientStatusChanged;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPatientRecordRequest;
use App\Http\Requests\StorePatientRecordRequest;
use App\Http\Requests\UpdatePatientRecordRequest;
use App\Models\PatientRecord;
use App\Models\PatientStatusLog;
use App\Models\PatientTrackingNumber;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\SimpleExcel\SimpleExcelWriter;


class PatientRecordsController extends Controller
{
    use CsvImportTrait;

public function index(Request $request)
{
    abort_if(Gate::denies('patient_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $showDeleted = $request->get('show_deleted', false);
    $statusFilter = $request->get('status_filter', '');
    $searchTerm = $request->get('search', '');

    $query = $showDeleted 
        ? PatientRecord::onlyTrashed()
        : PatientRecord::query();

    $query->with(['latestStatusLog'])
          ->orderByDesc($showDeleted ? 'deleted_at' : 'date_processed');

    // Apply status filter on server side
    if ($statusFilter && !$showDeleted) {
        $query->whereHas('latestStatusLog', function ($q) use ($statusFilter) {
            if ($statusFilter === 'Processing') {
                $q->whereIn('status', ['Processing', 'Rejected']);
            } elseif ($statusFilter === 'Submitted') {
                $q->where('status', 'like', 'Submitted%')
                  ->where('status', 'not like', '%[ROLLED BACK]%');
            }
        });
    }

    // Apply search term if provided
    if ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
            // Text field searches
            $q->where('control_number', 'like', "%{$searchTerm}%")
              ->orWhere('patient_name', 'like', "%{$searchTerm}%")
              ->orWhere('claimant_name', 'like', "%{$searchTerm}%")
              ->orWhere('diagnosis', 'like', "%{$searchTerm}%")
              ->orWhere('address', 'like', "%{$searchTerm}%")
              ->orWhere('contact_number', 'like', "%{$searchTerm}%")
              ->orWhere('case_worker', 'like', "%{$searchTerm}%")
              ->orWhere('case_type', 'like', "%{$searchTerm}%")
              ->orWhere('case_category', 'like', "%{$searchTerm}%")
              
              // Date processed search with multiple format support
              ->orWhere(function ($dateQ) use ($searchTerm) {
                  $this->addDateProcessedSearch($dateQ, $searchTerm);
              });
        });
    }

    $patientRecords = $query->paginate(100)->withQueryString();

    // Process each record as before
    $processedRecords = $patientRecords->map(function ($record) use ($showDeleted) {
        $latestStatus = $record->latestStatusLog;
        $statusValue = $latestStatus->status ?? ($showDeleted ? 'Deleted' : 'Processing');
        $cleanStatus = preg_replace('/\[ROLLED BACK\]/i', '', $statusValue);
        $cleanStatus = trim($cleanStatus);

        if ($showDeleted) {
            $record->filter_category = 'Deleted';
        } else {
            $processingStatuses = ['Processing', 'Rejected'];
            $record->filter_category = in_array($cleanStatus, $processingStatuses) 
                ? 'Processing' 
                : 'Submitted';
        }

        $record->clean_status = $cleanStatus;
        $record->is_deleted = $showDeleted;
        return $record;
    });

    $patientRecords->setCollection($processedRecords);

    return view('admin.patientRecords.index', compact(
        'patientRecords', 
        'showDeleted', 
        'searchTerm'
    ));
}

/**
 * Helper method for date_processed searching with multiple format support
 */
protected function addDateProcessedSearch($query, $searchTerm)
{
    // Remove the time part from display format if present
    $searchTermWithoutTime = preg_replace('/\s+\d{1,2}:\d{2}\s*(AM|PM)?/i', '', $searchTerm);
    
    // List of possible date formats that users might enter
    $dateFormats = [

        'F j, Y g:i A',    // December 30, 2024 11:32 AM
        'F j, Y H:i',      // December 30, 2024 11:32
        'Y-m-d H:i:s',     // 2024-12-30 11:32:00
        'Y/m/d H:i:s',     // 2024/12/30 11:32:00
        'm/d/Y H:i:s',     // 12/30/2024 11:32:00
        'd/m/Y H:i:s',     // 30/12/2024 11:32:00
        
        // Date-only formats
        'F j, Y',          // December 30, 2024
        'Y-m-d',           // 2024-12-30
        'Y/m/d',           // 2024/12/30
        'm/d/Y',           // 12/30/2024
        'd/m/Y',           // 30/12/2024
        'M j, Y',          // Dec 30, 2024
        
        // Month-year formats
        'F Y',             // December 2024
        'M Y',             // Dec 2024
        'm/Y',             // 12/2024
        'Y-m',             // 2024-12
        
        // Year only
        'Y',               // 2024
        
        // Month only
        'F',               // December
        'M',               // Dec
        'm',               // 12
        'n',               // 12 (without leading zero)
    ];
    
    $foundMatch = false;
    
    foreach ($dateFormats as $format) {
        try {
            $date = \Carbon\Carbon::createFromFormat($format, $searchTerm);
            
       
            if (in_array($format, ['Y'])) {
            
                $query->whereYear('date_processed', $date->year);
                $foundMatch = true;
                break;
            } elseif (in_array($format, ['F Y', 'M Y', 'm/Y', 'Y-m'])) {
      
                $query->whereYear('date_processed', $date->year)
                      ->whereMonth('date_processed', $date->month);
                $foundMatch = true;
                break;
            } elseif (in_array($format, ['F', 'M', 'm', 'n'])) {
         
                $monthNumber = $date->month;
                $query->whereMonth('date_processed', $monthNumber);
                $foundMatch = true;
                break;
            } elseif (str_contains($format, 'H:i') || str_contains($format, 'g:i')) {
            
                $query->where('date_processed', $date->format('Y-m-d H:i:s'));
                $foundMatch = true;
                break;
            } else {
          
                $query->whereDate('date_processed', $date->format('Y-m-d'));
                $foundMatch = true;
                break;
            }
        } catch (\Exception $e) {
            continue;
        }
    }
   
    if (!$foundMatch) {
        try {
            $date = \Carbon\Carbon::parse($searchTerm);
            
            $now = \Carbon\Carbon::now();
            $diffInDays = $date->diffInDays($now);
           
            if ($diffInDays > 365 || preg_match('/\d{4}/', $searchTerm)) {
               
                $query->whereDate('date_processed', $date->format('Y-m-d'));
                $foundMatch = true;
            }
        } catch (\Exception $e) {
           
        }
    }
    
    
    if (!$foundMatch) {
    
        $query->orWhere('date_processed', 'like', "%{$searchTerm}%")
       
              ->orWhereRaw("DATE_FORMAT(date_processed, '%M %d, %Y %h:%i %p') LIKE ?", ["%{$searchTerm}%"]) // December 30, 2024 11:32 AM
              ->orWhereRaw("DATE_FORMAT(date_processed, '%M %d, %Y') LIKE ?", ["%{$searchTerm}%"]) // December 30, 2024
              ->orWhereRaw("DATE_FORMAT(date_processed, '%Y-%m-%d %H:%i:%s') LIKE ?", ["%{$searchTerm}%"]) // 2024-12-30 11:32:00
              ->orWhereRaw("DATE_FORMAT(date_processed, '%Y-%m-%d') LIKE ?", ["%{$searchTerm}%"]) // 2024-12-30
              ->orWhereRaw("DATE_FORMAT(date_processed, '%m/%d/%Y') LIKE ?", ["%{$searchTerm}%"]) // 12/30/2024
              ->orWhereRaw("DATE_FORMAT(date_processed, '%d/%m/%Y') LIKE ?", ["%{$searchTerm}%"]) // 30/12/2024
              ->orWhereRaw("DATE_FORMAT(date_processed, '%W') LIKE ?", ["%{$searchTerm}%"]) // Monday
              ->orWhereRaw("DATE_FORMAT(date_processed, '%M') LIKE ?", ["%{$searchTerm}%"]); // December
    }
}

    public function create()
    {
        abort_if(Gate::denies('patient_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $latestId = PatientRecord::withTrashed()->max('id') + 1;
        $today = now()->format('Ymd');
        $controlNumber = 'CSWD-' . $today . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $dateProcessed = now();

        return view('admin.patientRecords.create', compact('controlNumber', 'dateProcessed'));
    }


    public function store(StorePatientRecordRequest $request)
    {

        abort_if(Gate::denies('patient_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $patient = PatientRecord::where('patient_name', $request->patient_name)
            ->orderBy('date_processed', 'desc')
            ->first();

        // If the patient exists, check the eligibility
        if ($patient) {
            $lastApplicationDate = Carbon::parse($patient->date_processed);
            $nextEligibleDate = $lastApplicationDate->copy()->addMonths(6);
            $currentDate = Carbon::now();

            if ($currentDate->lt($nextEligibleDate)) {
                $diff = $currentDate->diff($nextEligibleDate);

                $remainingMonths = $diff->m;
                $remainingDays = $diff->d;

                $message = 'The patient is not eligible to apply yet. Please wait for ';

                if ($remainingMonths > 0) {
                    $message .= $remainingMonths . ' ' . Str::plural('month', $remainingMonths);
                }

                if ($remainingMonths > 0 && $remainingDays > 0) {
                    $message .= ' and ';
                }

                if ($remainingDays > 0) {
                    $message .= $remainingDays . ' ' . Str::plural('day', $remainingDays);
                }

                return redirect()->back()->with('toast', [
                    'type' => 'danger',
                    'title' => 'Eligibility Check',
                    'message' => $message . '.',
                    'time' => now()->diffForHumans(),
                ]);
            }
        }

        $patientRecord = PatientRecord::create($request->all());

        $statusLog = PatientStatusLog::create([
            'patient_id' => $patientRecord->id,
            'status' => PatientStatusLog::STATUS_PROCESSING,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'status_date' => now(),
        ]);
        NotificationService::createNewPatientNotifications($statusLog);


        do {
            $trackingNumber = 'WS' . now()->format('YmdHis') . rand(100, 999);
        } while (PatientTrackingNumber::where('tracking_number', $trackingNumber)->exists());


        PatientTrackingNumber::create([
            'patient_id' => $patientRecord->id,
            'tracking_number' => $trackingNumber,
        ]);
        // Store the created record ID in session to avoid self-broadcasting issues
        $recentlyCreated = session('recently_created_records', []);
        $recentlyCreated[] = $patientRecord->id;
        session(['recently_created_records' => array_slice($recentlyCreated, -10)]); // Keep last 10
        broadcast(new PatientRecordCreated($patientRecord))->toOthers();

        return redirect()->route('admin.patient-records.index')->with('toast', [
            'type' => 'success',
            'title' => 'Patient Created',
            'message' => 'The patient record has been successfully created.',
            'time' => now()->diffForHumans(),
        ]);
    }



    public function edit(PatientRecord $patientRecord)
    {
        abort_if(Gate::denies('patient_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.patientRecords.edit', compact('patientRecord'));
    }

    public function update(UpdatePatientRecordRequest $request, PatientRecord $patientRecord)
    {
        $patientRecord->update($request->all());

        return redirect()->route('admin.patient-records.index')->with('toast', [
            'type' => 'success',
            'title' => 'Patient Updated',
            'message' => 'The patient record has been successfully updated.<br><strong>Control No:</strong> ' . $patientRecord->control_number,
            'time' => now()->diffForHumans(),
        ]);
    }


    public function show(PatientRecord $patientRecord)
    {
        abort_if(Gate::denies('patient_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $latestStatus = $patientRecord->statusLogs()->orderBy('created_at', 'desc')->first();
        $hasProcessTracking = $patientRecord->statusLogs()->exists();
        return view('admin.patientRecords.show', compact('patientRecord', 'latestStatus', 'hasProcessTracking'));
    }


    public function destroy(PatientRecord $patientRecord)
    {
        abort_if(Gate::denies('patient_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientRecord->delete();

        return redirect()->route('admin.patient-records.index')->with('toast', [
            'type' => 'danger',
            'title' => 'Patient Record Deleted',
            'message' => 'The patient record has been deleted.<br><strong>Control No:</strong> ' . $patientRecord->control_number,
            'time' => now()->diffForHumans(),
        ]);
    }

    public function massDestroy(MassDestroyPatientRecordRequest $request)
    {
        abort_if(Gate::denies('patient_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->input('ids', []);
        $deletedCount = 0;

        DB::beginTransaction();

        try {
            $patientRecords = PatientRecord::whereIn('id', $ids)->get();

            foreach ($patientRecords as $patientRecord) {
                $patientRecord->delete();
                $deletedCount++;
            }

            DB::commit();

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Mass Delete Completed',
                    'message' => "{$deletedCount} " . ($deletedCount === 1 ? "record was" : "records were") . " deleted successfully",
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Error Deleting Records',
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]);
        }
    }

      public function restore($id)
    {
        abort_if(Gate::denies('patient_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        // Find the soft-deleted record
        $patientRecord = PatientRecord::onlyTrashed()->findOrFail($id);
        $patientRecord->restore();
        
        return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
            ->with('toast', [
                'type' => 'success',
                'title' => 'Record Restored',
                'message' => "Patient record ({$patientRecord->control_number}) has been restored successfully.",
                'time' => now()->diffForHumans(),
            ]);
    }

    /**
     * Permanently delete a soft-deleted patient record
     */
    public function forceDelete($id)
    {
        abort_if(Gate::denies('patient_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Find the soft-deleted record
        $patientRecord = PatientRecord::onlyTrashed()->findOrFail($id);
        $controlNumber = $patientRecord->control_number;
        
        // Force delete the patient record
        $patientRecord->forceDelete();
        
        return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
            ->with('toast', [
                'type' => 'danger',
                'title' => 'Record Permanently Deleted',
                'message' => "Patient record ({$controlNumber}) has been permanently deleted.",
                'time' => now()->diffForHumans(),
            ]);
    }

    /**
     * Mass restore multiple soft-deleted records
     */
    public function massRestore(Request $request)
    {
        abort_if(Gate::denies('patient_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->input('ids', []);
        $restoredCount = 0;
        
        DB::beginTransaction();
        
        try {
            $patientRecords = PatientRecord::onlyTrashed()->whereIn('id', $ids)->get();
            
            foreach ($patientRecords as $patientRecord) {
                $patientRecord->restore();
                $restoredCount++;
            }
            
            DB::commit();
            
            return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Mass Restore Completed',
                    'message' => "{$restoredCount} " . ($restoredCount === 1 ? "record was" : "records were") . " restored successfully",
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Error Restoring Records',
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]);
        }
    }

    /**
     * Mass permanently delete multiple soft-deleted records
     */
    public function massForceDelete(Request $request)
    {
        abort_if(Gate::denies('patient_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $ids = $request->input('ids', []);
        $deletedCount = 0;
        
        DB::beginTransaction();
        
        try {
            $patientRecords = PatientRecord::onlyTrashed()->whereIn('id', $ids)->get();
            
            foreach ($patientRecords as $patientRecord) {
                
                // Force delete
                $patientRecord->forceDelete();
                $deletedCount++;
            }
            
            DB::commit();
            
            return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Mass Permanent Delete Completed',
                    'message' => "{$deletedCount} " . ($deletedCount === 1 ? "record was" : "records were") . " permanently deleted",
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.patient-records.index', ['show_deleted' => 1])
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Error Deleting Records',
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]);
        }
    }


    public function submit(Request $request, $id)
    {
        abort_if(Gate::denies('submit_patient_application'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'status' => 'required|string',
            'submitted_date' => 'required|date'
        ]);

        $status = $request->input('status');
        $statusDate = $request->input('submitted_date');

        // Create status log
        $statusLog = PatientStatusLog::create([
            'patient_id' => $id,
            'status' => $status,
            'user_id' => Auth::id(),
            'status_date' => $statusDate,
            'remarks' => $request->remarks,
            'created_at' => now(),
        ]);
        NotificationService::createStatusLogNotifications($statusLog);

        $patientRecord = PatientRecord::with('latestStatusLog')->findOrFail($id);

        $action = $status === 'Submitted' ? 'submitted' : 'updated';
        broadcast(new PatientStatusChanged($patientRecord, $action))->toOthers();

        if ($request->has('redirect_to_process_tracking')) {
            return redirect()
                ->route('admin.process-tracking.show', $id)
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Patient Submitted Successfully',
                    'message' => 'Application submitted successfully',
                    'time' => now()->diffForHumans(),
                ]);
        }

        return redirect()
            ->route('admin.patient-records.show', $id)
            ->with('toast', [
                'type' => 'success',
                'title' => 'Patient Submitted Successfully',
                'message' => 'Application submitted successfully',
                'time' => now()->diffForHumans(),
            ]);
    }
    public function massSubmit(Request $request)
    {
        abort_if(Gate::denies('submit_patient_application'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ids = $request->input('ids', []);
        $remarks = $request->input('remarks');
        $statusDate = $request->input('submitted_date');

        $submitted = [];
        $skipped = [];

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $patient = PatientRecord::with(['statusLogs' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }])->find($id);

                if (!$patient) continue;

                $latestStatus = $patient->statusLogs->first();

                // Check if latest status is Processing OR Rejected
                $allowedStatuses = [
                    PatientStatusLog::STATUS_PROCESSING,
                    PatientStatusLog::STATUS_REJECTED
                ];

                if (!$latestStatus || in_array($latestStatus->status, $allowedStatuses)) {
                    $statusLog = PatientStatusLog::create([
                        'patient_id' => $patient->id,
                        'status' => PatientStatusLog::STATUS_SUBMITTED,
                        'user_id' => Auth::id(),
                        'remarks' => $remarks,
                        'status_date' => $statusDate,
                        'created_at' => now(),
                    ]);

                    NotificationService::createStatusLogNotifications($statusLog);

                    // Broadcast the status change
                    $patient->load('latestStatusLog');
                    broadcast(new PatientStatusChanged($patient, 'submitted'))->toOthers();

                    $submitted[] = $patient->control_number;
                } else {
                    $skipped[] = $patient->control_number . " (status: {$latestStatus->status})";
                }
            }

            DB::commit();

            $messages = [];
            if (count($submitted)) {
                $messages[] = count($submitted) . " " . (count($submitted) === 1 ? "patient has" : "patients have") . " been submitted";
            }
            if (count($skipped)) {
                $messages[] = count($skipped) . " " . (count($skipped) === 1 ? "patient was" : "patients were") . " skipped";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => count($submitted) > 0 ? 'success' : 'warning',
                    'title' => 'Mass Submit Completed',
                    'message' => $toastMessage,
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Error Submitting Records',
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]);
        }
    }
    public function submitEmergency(Request $request, $id)
    {
        abort_if(Gate::denies('submit_patient_application'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'submitted_date' => 'required|date'
        ]);

        $statusDate = $request->input('submitted_date');

        $statusLog = PatientStatusLog::create([
            'patient_id'  => $id,
            'status'      => 'Submitted[Emergency]',
            'user_id'     => Auth::id(),
            'status_date' => $statusDate,
            'remarks'     => $request->remarks,
            'created_at'  => now(),
        ]);
        NotificationService::createStatusLogNotifications($statusLog);

        $patientRecord = PatientRecord::with('latestStatusLog')->findOrFail($id);

        broadcast(new PatientStatusChanged($patientRecord, 'submitted-emergency'))->toOthers();
        if ($request->has('redirect_to_process_tracking')) {
            return redirect()
                ->route('admin.process-tracking.show', $id)
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Patient Submitted Successfully (Emergency)',
                    'message' => 'Application submitted successfully',
                    'time' => now()->diffForHumans(),
                ]);
        }

        return redirect()
            ->route('admin.patient-records.show', $id)
            ->with('toast', [
                'type' => 'success',
                'title' => 'Patient Submitted Successfully (Emergency)',
                'message' => 'Application submitted successfully',
                'time' => now()->diffForHumans(),
            ]);
    }

    public function csvTemplate($type)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_{$type}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'date_processed',
            'case_type',
            'control_number',
            'claimant_name',
            'case_category',
            'patient_name',
            'diagnosis',
            'age',
            'address',
            'contact_number',
            'case_worker',
        ];

        if ($type === 'disbursed') {
            $columns = array_merge($columns, ['disbursed_date', 'amount', 'allocation_date']);
        }

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // header only
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function excelTemplate($type)
    {
        $filename = 'patient_template_' . $type . '_' . date('Y-m-d') . '.xlsx';

        $columns = [
            'date_processed',
            'case_type',
            'control_number',
            'claimant_name',
            'case_category',
            'patient_name',
            'diagnosis',
            'age',
            'address',
            'contact_number',
            'case_worker',
        ];

        if ($type === 'disbursed') {
            $columns = array_merge($columns, ['disbursed_date', 'amount', 'allocation_date']);
        }

        $path = storage_path('app/' . $filename);

        SimpleExcelWriter::create($path)
            ->addHeader($columns)
            ->close();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
