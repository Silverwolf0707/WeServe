<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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

                return redirect()->back()->with('error', $message . '.');
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

        return redirect()->route('admin.patient-records.index')->with('status', 'Patient record created and submitted.');
    }



    public function edit(PatientRecord $patientRecord)
    {
        abort_if(Gate::denies('patient_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.patientRecords.edit', compact('patientRecord'));
    }

    public function update(UpdatePatientRecordRequest $request, PatientRecord $patientRecord)
    {
        $patientRecord->update($request->all());

        return redirect()->route('admin.patient-records.index');
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

        return back();
    }

    public function massDestroy(MassDestroyPatientRecordRequest $request)
    {
        $patientRecords = PatientRecord::find(request('ids'));

        foreach ($patientRecords as $patientRecord) {
            $patientRecord->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function submit(Request $request, $id)
    {
        abort_if(Gate::denies('submit_patient_application'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'remarks' => 'required|string|max:1000',
            'status' => 'required|string',
        ]);

        PatientStatusLog::create([
            'patient_id' => $id,
            'status' => PatientStatusLog::STATUS_SUBMITTED,  // "Submitted"
            'user_id' => Auth::id(),
            'remarks' => $request->remarks,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.patient-records.show', $id)
            ->with('success', 'Application submitted successfully with remarks.');
    }
}
