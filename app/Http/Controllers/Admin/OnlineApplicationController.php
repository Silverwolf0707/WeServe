<?php

namespace App\Http\Controllers\Admin;

use App\Models\OnlinePatientApplication;
use App\Models\PatientTrackingNumber;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OnlineApplicationController
{
    public function index()
    {
        return view('homepage');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'age'            => 'required|integer|min:1|max:120',
            'address'        => 'required|string|max:500',
            'contact_number' => 'required|string|max:11',
            'claimant_name'  => 'required|string|max:255',
            'diagnosis'      => 'nullable|string|max:1000',
            'case_category'  => 'required|in:' . implode(',', array_keys(PatientRecord::CASE_CATEGORY_SELECT)),
            'case_type'      => 'required|in:' . implode(',', array_keys(PatientRecord::CASE_TYPE_SELECT)),
        ]);

        // Check for duplicate applications and eligibility
        $eligibilityCheck = $this->checkEligibility($validated['applicant_name']);

        if ($eligibilityCheck['ineligible']) {
            return redirect()->back()->with('toast', [
                'type' => 'danger',
                'title' => 'Eligibility Check',
                'message' => $eligibilityCheck['message'],
                'time' => now()->diffForHumans(),
            ]);
        }


        // Check for pending online applications with same name
        $pendingApplication = OnlinePatientApplication::where('applicant_name', $validated['applicant_name'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->first();

        if ($pendingApplication) {
            return redirect()->back()->with('toast', [
                'type' => 'warning',
                'title' => 'Duplicate Application',
                'message' => 'You already have a pending application. Please wait for your current application to be processed before submitting a new one.',
            ]);
        }

        do {
            $trackingNumber = 'WS' . now()->format('YmdHis') . rand(100, 999);
        } while (PatientTrackingNumber::where('tracking_number', $trackingNumber)->exists());

        $application = OnlinePatientApplication::create([
            'tracking_number' => $trackingNumber,
            'applicant_name'  => $validated['applicant_name'],
            'age'             => $validated['age'],
            'address'         => $validated['address'],
            'contact_number'  => $validated['contact_number'],
            'claimant_name'   => $validated['claimant_name'],
            'diagnosis'       => $validated['diagnosis'] ?? null,
            'case_category'   => $validated['case_category'],
            'case_type'       => $validated['case_type'],
        ]);

        return redirect()->back()->with([
            'tracking_number' => $trackingNumber,
            'toast' => [
                'type' => 'success',
                'title' => 'Application Submitted',
                'message' => 'Your application has been submitted successfully! Please save your tracking number.',
            ]
        ]);
    }

    /**
     * Check eligibility based on previous applications
     */
    private function checkEligibility($applicantName)
    {
        // Check patient records (approved applications)
        $patient = PatientRecord::where('patient_name', $applicantName)
            ->orderBy('date_processed', 'desc')
            ->first();

        if ($patient) {
            $lastApplicationDate = Carbon::parse($patient->date_processed);
            $nextEligibleDate = $lastApplicationDate->copy()->addMonths(6);
            $currentDate = Carbon::now();

            if ($currentDate->lt($nextEligibleDate)) {
                $diff = $currentDate->diff($nextEligibleDate);

                $remainingMonths = $diff->m;
                $remainingDays = $diff->d;

                $message = 'You are not eligible to apply yet. Please wait for ';

                if ($remainingMonths > 0) {
                    $message .= $remainingMonths . ' ' . Str::plural('month', $remainingMonths);
                }

                if ($remainingMonths > 0 && $remainingDays > 0) {
                    $message .= ' and ';
                }

                if ($remainingDays > 0) {
                    $message .= $remainingDays . ' ' . Str::plural('day', $remainingDays);
                }

                return [
                    'ineligible' => true,
                    'message' => $message . '.'
                ];
            }
        }

        return [
            'ineligible' => false,
            'message' => ''
        ];
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $trackingNumber = $request->tracking_number;

        $trackingRecord = PatientTrackingNumber::where('tracking_number', $trackingNumber)
            ->with('patient.statusLogs')
            ->first();

        if ($trackingRecord && $trackingRecord->patient) {
            $patient = $trackingRecord->patient;
            $logs = $patient->statusLogs()->orderBy('status_date')->get();

            return view('trackingpage')->with([
                'status' => 'Application has been transferred to patient records.',
                'application' => $patient,
                'logs' => $logs,
            ]);
        }

        $application = OnlinePatientApplication::where('tracking_number', $trackingNumber)->first();
        if ($application) {
            return view('trackingpage')->with([
                'status' => 'Application is still on process.',
                'application' => $application,
                'logs' => [
                    (object)[
                        'status_date' => $application->created_at,
                        'status' => 'Please wait for further announcement',
                        'remarks' => null
                    ]
                ],
            ]);
        }

        return view('trackingpage')->with([
            'status' => 'Tracking number not found.',
            'logs' => [],
        ]);
    }
}
