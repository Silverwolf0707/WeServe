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
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientRecordsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('patient_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientRecords = PatientRecord::all();

        return view('admin.patientRecords.index', compact('patientRecords'));
    }

    public function create()
    {
        abort_if(Gate::denies('patient_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Auto-generate control number
        $latestId = PatientRecord::withTrashed()->max('id') + 1;
        $today = now()->format('Ymd');
        $controlNumber = 'CSWD-' . $today . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $dateProcessed = now();

        return view('admin.patientRecords.create', compact('controlNumber', 'dateProcessed'));
    }


    public function store(StorePatientRecordRequest $request)
    {
        // Retrieve the most recent patient record with the same name
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

        // Create the patient record if eligible
        $patientRecord = PatientRecord::create($request->all());

        // Create a status log entry
        // PatientStatusLog::create([
        //     'patient_id' => $patientRecord->id,
        //     'status' => PatientStatusLog::STATUS_SUBMITTED,
        //     'user_id' => Auth::id(),
        //     'created_at' => now(),
        // ]);
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
            'type' => 'warning',
            'title' => 'Patient Deleted',
            'message' => 'The patient record has been deleted.<br><strong>Control No:</strong> ' . $patientRecord->control_number,
            'time' => now()->diffForHumans(),
        ]);
    }

    public function massDestroy(MassDestroyPatientRecordRequest $request)
    {
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

            // Redirect with toast
            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Mass Delete Completed',
                    'message' => "✅ {$deletedCount} " . ($deletedCount === 1 ? "record was" : "records were") . " deleted successfully",
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


    public function submit(Request $request, $id)
    {
        abort_if(Gate::denies('submit_patient_application'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'remarks' => 'required|string|max:1000',
            'status' => 'required|string',
            'submitted_date' => 'required|date'
        ]);

        $status = $request->input('status');
        $statusDate = $request->input('submitted_date');

        // Create status log
        PatientStatusLog::create([
            'patient_id' => $id,
            'status' => $status,
            'user_id' => Auth::id(),
            'status_date' => $statusDate,
            'remarks' => $request->remarks,
            'created_at' => now(),
        ]);

        $patientRecord = PatientRecord::with('latestStatusLog')->findOrFail($id);

        $action = $status === 'Submitted' ? 'submitted' : 'updated';
        broadcast(new PatientStatusChanged($patientRecord, $action))->toOthers();

        return redirect()
            ->route('admin.patient-records.show', $id)
            ->with('success', 'Application submitted successfully with remarks.');
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
                $patient = PatientRecord::find($id);
                if (!$patient) continue;

                $latestStatus = $patient->statusLogs()->latest()->first();

                if (!$latestStatus || $latestStatus->status === PatientStatusLog::STATUS_REJECTED) {
                    $patient->statusLogs()->create([
                        'status' => PatientStatusLog::STATUS_SUBMITTED,
                        'user_id' => Auth::id(),
                        'remarks' => $remarks,
                        'status_date' => $statusDate,
                        'created_at' => now(),
                    ]);
                    $submitted[] = $patient->control_number;
                } else {
                    $skipped[] = $patient->control_number;
                }
            }

            DB::commit();

            // Prepare toast message
            $messages = [];
            if (count($submitted)) {
                $messages[] = "✅ " . count($submitted) . " " . (count($submitted) === 1 ? "patient has" : "patients have") . " been submitted";
            }
            if (count($skipped)) {
                $messages[] = "⚠️ " . count($skipped) . " " . (count($skipped) === 1 ? "patient was" : "patients were") . " skipped (already submitted)";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'success',
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
            'remarks' => 'required|string|max:1000',
            'submitted_date' => 'required|date'
        ]);

        $statusDate = $request->input('submitted_date');

        // Create status log with emergency flag
        PatientStatusLog::create([
            'patient_id'  => $id,
            'status'      => 'Submitted[Emergency]',
            'user_id'     => Auth::id(),
            'status_date' => $statusDate,
            'remarks'     => $request->remarks,
            'created_at'  => now(),
        ]);

        $patientRecord = PatientRecord::with('latestStatusLog')->findOrFail($id);

        broadcast(new PatientStatusChanged($patientRecord, 'submitted-emergency'))->toOthers();

        return redirect()
            ->route('admin.patient-records.show', $id)
            ->with('success', 'Emergency application submitted successfully with remarks.');
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
}
