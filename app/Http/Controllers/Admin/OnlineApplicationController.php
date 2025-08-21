<?php

namespace App\Http\Controllers\Admin;

use App\Models\OnlinePatientApplication;
use Illuminate\Http\Request;

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
            'diagnosis'      => 'nullable|string|max:500',
            'case_category'  => 'required|in:Educational Assistance,Medical Assistance,Burial Assistance,Emergency Assistance',
            'case_type'      => 'required|in:Student,PWD,Senior,Solo Parent',
        ]);

        $trackingNumber = 'WS' . now()->format('YmdHis') . rand(100, 999);

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

        return redirect()->back()->with('tracking_number', $trackingNumber);
    }
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $trackingNumber = $request->tracking_number;

        // Check transferred patient records via pivot table first
        $trackingRecord = \App\Models\PatientTrackingNumber::where('tracking_number', $trackingNumber)
            ->with('patient.statusLogs') // eager load logs
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

        // Check online applications (not yet transferred)
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
