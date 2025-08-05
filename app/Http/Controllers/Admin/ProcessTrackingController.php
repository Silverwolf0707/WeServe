<?php

namespace App\Http\Controllers\Admin;

use App\Events\PatientStatusChanged;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use App\Models\BudgetAllocation;
use App\Models\PatientStatusLog;
use App\Models\DisbursementVoucher;
use App\Services\VonageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ProcessTrackingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('process_tracking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = PatientRecord::whereHas('statusLogs')->with(['latestStatusLog'])->get();


        return view('admin.processTracking.index', compact('patients'));
    }


    public function show($id)
    {
        abort_if(Gate::denies('process_tracking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient = PatientRecord::with(['statusLogs.user'])
            ->select('id', 'control_number', 'date_processed', 'claimant_name', 'case_worker')
            ->findOrFail($id);

        $latestStatus = $patient->statusLogs->last(); // or ->whereNotNull('created_at')->last()

        return view('admin.processTracking.show', compact('patient', 'latestStatus'));
    }


    public function decision(Request $request, $id)
    {
        abort_if(Gate::denies('approve_patient'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'remarks' => 'required|string|max:1000',
            'action' => 'required|in:approve,reject',
        ]);

        $patient = PatientRecord::findOrFail($id);

        $status = $request->action === 'approve'
            ? PatientStatusLog::STATUS_APPROVED
            : PatientStatusLog::STATUS_REJECTED;

        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'status' => $status,
            'user_id' => Auth::id(),
            'remarks' => $request->remarks,
            'created_at' => now(),
        ]);

        // Refresh patient record if you want to include relationships like latest status
        $patient->load('latestStatusLog');

        // Broadcast the event
        $action = $request->action === 'approve' ? 'approved' : 'rejected';
        broadcast(new PatientStatusChanged($patient, $action))->toOthers();

        return redirect()
            ->route('admin.process-tracking.show', $id)
            ->with('status', 'Patient has been ' . strtolower($status) . '.');
    }

    public function storeDV(Request $request, $id)
    {
        abort_if(Gate::denies('accounting_dv_input'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'dv_code' => 'required|string|max:255',
            'dv_date' => 'required|date',
        ]);

        $patient = PatientRecord::findOrFail($id);

        // Prevent multiple DV entries
        if ($patient->disbursementVoucher) {
            return back()->with('error', 'DV already submitted for this patient.');
        }

        DisbursementVoucher::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'dv_code' => $request->dv_code,
            'dv_date' => $request->dv_date,
        ]);

        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DV_SUBMITTED,
            'remarks' => 'DV recorded: ' . $request->dv_code,
            'created_at' => now(),
        ]);

        $patient->load('latestStatusLog'); // Optional: load if your frontend expects it

        broadcast(new PatientStatusChanged($patient, 'dv_submitted'))->toOthers();

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Disbursement Voucher added successfully.');
    }

    public function updateDV(Request $request, $id)
    {
        $patient = PatientRecord::findOrFail($id);

        $validated = $request->validate([
            'dv_code' => 'required|string|max:255',
            'dv_date' => 'required|date',
        ]);

        $dv = $patient->disbursementVoucher;
        if ($dv) {
            $dv->update($validated);
        }

        // Log status
        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DV_SUBMITTED,
            'remarks' => 'DV updated: ' . $validated['dv_code'],
            'created_at' => now(),
        ]);

        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'dv_submitted'))->toOthers();

        return back()->with('success', 'DV updated and status progressed.');
    }



    public function storeBudget(Request $request, $id)
    {
        abort_if(Gate::denies('budget_allocate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $patient = PatientRecord::findOrFail($id);

        if ($patient->budgetAllocation) {
            return back()->with('error', 'Budget already allocated for this patient.');
        }

        BudgetAllocation::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'remarks' => $request->remarks,
            'budget_status' => 'Not Disbursed',
        ]);

        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_BUDGET_ALLOCATED,
            'remarks' => 'Budget allocated: ₱' . number_format($request->amount, 2),
            'created_at' => now(),
        ]);

        $patient->load('latestStatusLog');

        broadcast(new PatientStatusChanged($patient, 'budget_allocated'))->toOthers();

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Budget allocated successfully.');
    }
    public function updateBudget(Request $request, $id)
    {
        $patient = PatientRecord::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $budget = $patient->budgetAllocation;
        if ($budget) {
            $budget->update($validated);
        }

        // Log status
        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'status' => PatientStatusLog::STATUS_BUDGET_ALLOCATED,
            'user_id' => Auth::id(),
            'remarks' => 'Budget updated: ₱' . number_format($validated['amount'], 2),
            'created_at' => now(),
        ]);

        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'budget_allocated'))->toOthers();

        return back()->with('success', 'Budget updated and status progressed.');
    }



    public function markBudgetAsDisbursed($id)
    {
        abort_if(Gate::denies('treasury_disburse'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocation = BudgetAllocation::where('patient_id', $id)->firstOrFail();

        if ($allocation->budget_status === 'Disbursed') {
            return back()->with('error', 'Budget already marked as disbursed.');
        }

        $allocation->update([
            'budget_status' => 'Disbursed',
        ]);

        PatientStatusLog::create([
            'patient_id' => $id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DISBURSED,
            'remarks' => 'Budget marked as disbursed by Treasury.',
            'created_at' => now(),
        ]);

        $patient = PatientRecord::with('latestStatusLog')->findOrFail($id);

        broadcast(new PatientStatusChanged($patient, 'disbursed'))->toOthers();

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Budget status updated to Disbursed.');
    }
    public function rollback(Request $request, PatientRecord $patient)
    {
        $request->validate([
            'rollback_to' => 'required|string',
            'rollback_remarks' => 'required|string',
        ]);

        $validStatuses = $patient->statusLogs->pluck('status')->map(function ($status) {
            return str_replace('[ROLLED BACK]', '', $status);
        })->unique();

        $rollbackTo = $request->rollback_to;

        if (!$validStatuses->contains($rollbackTo)) {
            return back()->with('error', 'Invalid rollback target.');
        }

        $rolledBackStatus = $rollbackTo . '[ROLLED BACK]';

        // Log new status with [ROLLED BACK] mark
        $patient->statusLogs()->create([
            'status' => $rolledBackStatus,
            'remarks' => '[ROLLED BACK] ' . $request->rollback_remarks,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        // Reload latest status log before broadcasting
        $patient->load('latestStatusLog');

        // Broadcast base status only (for UI logic)
        broadcast(new PatientStatusChanged($patient, strtolower($rollbackTo)))->toOthers();

        return redirect()->back()->with('success', 'Process rolled back to ' . $rolledBackStatus);
    }

    public function sendOtpForDisbursement($id)
    {
        abort_if(Gate::denies('treasury_disburse'), 403);

        $patient = PatientRecord::findOrFail($id);

        if (!$patient->contact_number) {
            return back()->with('error', 'No contact number found for this claimant.');
        }

        // Format phone number to E.164
        $phone = $patient->contact_number;
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '09')) {
                $phone = '+63' . substr($phone, 1);
            }
        }

        $otp = random_int(100000, 999999);

        OtpCode::create([
            'user_id' => Auth::id(),
            'patient_id' => $patient->id,
            'otp_code' => Hash::make($otp),
            'sent_at' => now(),
        ]);

        $patient->budgetAllocation()->update([
            'budget_status' => 'Confirmation of Disbursement',
        ]);

        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_READY_FOR_DISBURSEMENT,
            'remarks' => 'OTP sent to claimant.',
        ]);

        $message = "San Pedro City of Laguna\n" .
            "Claimant: {$patient->claimant_name}\n" .
            "Date: " . now()->format('F j, Y') . "\n" .
            "Location: City Hall, Treasury Office\n" .
            "OTP Code: $otp\n" .
            "Please show this OTP to confirm disbursement.";

        try {
            app(VonageService::class)->sendSms($phone, $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP SMS: ' . $e->getMessage());
        }

        return back()->with('status', 'OTP sent and disbursement process started.');
    }
    public function verifyOtp(Request $request, $id)
    {
        abort_if(Gate::denies('treasury_disburse'), 403);

        $request->validate([
            'otp_code' => 'required|numeric',
        ]);

        $patient = PatientRecord::findOrFail($id);

        $latestOtp = $patient->otpCodes()->latest()->first();

        if (!$latestOtp || $latestOtp->is_verified) {
            return back()->with('error', 'No valid OTP to verify.');
        }

        if (!Hash::check($request->otp_code, $latestOtp->otp_code)) {
            return back()->with('error', 'Incorrect OTP code.');
        }

        // Mark OTP as verified
        $latestOtp->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // Update budget status
        $patient->budgetAllocation()->update([
            'budget_status' => 'Disbursed',
        ]);

        // Log status
        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DISBURSED,
            'remarks' => 'OTP verified. Marked as disbursed.',
        ]);

        return redirect()->route('admin.process-tracking.show', $patient->id)
            ->with('status', 'OTP verified and disbursement completed.');
    }
}
