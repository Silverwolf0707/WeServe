<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePatientApplication;
use App\Models\PatientRecord;
use App\Models\PatientStatusLog;
use App\Models\PatientTrackingNumber;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnlinePatientApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = OnlinePatientApplication::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('claimant_name', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Case category filter
        if ($request->filled('case_category')) {
            $query->where('case_category', $request->case_category);
        }

        // Case type filter
        if ($request->filled('case_type')) {
            $query->where('case_type', $request->case_type);
        }

        // Single date filter
        if ($request->filled('application_date')) {
            $query->whereDate('created_at', $request->application_date);
        }

        // Order by latest first
        $query->latest();

        $applications = $query->paginate(100)->withQueryString();

       $caseCategoryOptions = PatientRecord::distinct()
    ->whereNotNull('case_category')
    ->where('case_category', '!=', '')
    ->orderBy('case_category')
    ->pluck('case_category')
    ->mapWithKeys(function ($category) {
        return [$category => $category];
    })
    ->toArray();

$caseTypeOptions = PatientRecord::distinct()
    ->whereNotNull('case_type')
    ->where('case_type', '!=', '')
    ->orderBy('case_type')
    ->pluck('case_type')
    ->mapWithKeys(function ($type) {
        return [$type => $type];
    })
    ->toArray();

        return view('admin.onlineapplication.index', compact(
            'applications',
            'caseCategoryOptions',
            'caseTypeOptions'
        ));
    }

    public function show($id)
    {
        $application = OnlinePatientApplication::findOrFail($id);

        return view('admin.onlineapplication.show', compact('application'));
    }
    
    public function confirmTransfer($applicationId)
    {
        DB::transaction(function () use ($applicationId) {
            $application = OnlinePatientApplication::findOrFail($applicationId);

            $today = now()->format('Ymd');
            $latestId = PatientRecord::max('id') + 1;
            $controlNumber = 'CSWD-' . $today . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

            while (PatientRecord::where('control_number', $controlNumber)->exists()) {
                $latestId++;
                $controlNumber = 'CSWD-' . $today . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
            }

            $patient = PatientRecord::create([
                'control_number' => $controlNumber,
                'case_type'      => $application->case_type,
                'claimant_name'  => $application->claimant_name,
                'patient_name'   => $application->applicant_name,
                'diagnosis'      => $application->diagnosis,
                'age'            => $application->age,
                'address'        => $application->address,
                'contact_number' => $application->contact_number,
                'case_category'  => ucfirst($application->case_category),
                'date_processed' => now(),
                'case_worker'    => Auth::user()->name,
            ]);

            PatientTrackingNumber::create([
                'patient_id' => $patient->id,
                'tracking_number' => $application->tracking_number,
               
            ]);

            $statusLog = PatientStatusLog::create([
                'patient_id' => $patient->id,
                'status' => 'Processing',
                'remarks' => 'Application accepted please go to the CSWD office for further processing.',
                'status_date' => now(),
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            NotificationService::createNewPatientNotifications($statusLog);

            $application->delete();
        });

        return redirect()->route('admin.online-applications.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Transfer Successful',
                'message' => 'Application transferred successfully.',
                'time' => now()->diffForHumans(),
            ]);
    }
}