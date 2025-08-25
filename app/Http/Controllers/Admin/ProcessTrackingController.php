<?php

namespace App\Http\Controllers\Admin;

use App\Events\PatientProcessUpdated;
use App\Events\PatientStatusChanged;
use App\Events\ProcessSummaryUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\OtpCode;
use App\Models\RejectionReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use App\Models\BudgetAllocation;
use App\Models\PatientStatusLog;
use App\Models\DisbursementVoucher;
use App\Models\Document;
use App\Services\VonageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            ->select('id', 'control_number', 'date_processed', 'claimant_name', 'case_worker', 'case_category')
            ->findOrFail($id);

        $latestStatus = $patient->statusLogs->last(); // or ->whereNotNull('created_at')->last()

        return view('admin.processTracking.show', compact('patient', 'latestStatus'));
    }


    public function decision(Request $request, $id)
    {
        abort_if(Gate::denies('approve_patient'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'remarks' => 'required|string|max:1000',
            'action'  => 'required|in:approve,reject',
            'status_date' => 'required|date',
        ];

        // Multi-select validation if rejecting
        if ($request->action === 'reject') {
            $rules['reasons']       = 'required|array|min:1';
            $rules['reasons.*']     = 'string|max:255';
            $rules['other_reason']  = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $patient = PatientRecord::findOrFail($id);

        $status = $request->action === 'approve'
            ? PatientStatusLog::STATUS_APPROVED
            : PatientStatusLog::STATUS_REJECTED;

        // 1️⃣ Create status log (remarks stored here)
        $statusLog = PatientStatusLog::create([
            'patient_id' => $patient->id,
            'status'     => $status,
            'user_id'    => Auth::id(),
            'remarks'    => $validated['remarks'],
            'status_date' => $validated['status_date'],
            'created_at' => now(),
        ]);

        // 2️⃣ Store multiple reasons if rejected
        if ($request->action === 'reject') {
            $reasons = $validated['reasons'] ?? [];

            // Append "Other Reason" if filled
            if (!empty($validated['other_reason'])) {
                $reasons[] = $validated['other_reason'];
            }

            foreach ($reasons as $reason) {
                RejectionReason::create([
                    'patient_id'            => $patient->id,
                    'patient_status_log_id' => $statusLog->id,
                    'reason'                => $reason,
                ]);
            }
        }

        // Reload patient with latest status log and user
        $patient->load('latestStatusLog.user');

        // Broadcast updates
        $action = $request->action === 'approve' ? 'approved' : 'rejected';
        broadcast(new PatientProcessUpdated($patient));
        broadcast(new PatientStatusChanged($patient, $action));



        return redirect()->route('admin.process-tracking.show', $id)->with('toast', [
            'type' => 'success',
            'title' => 'Patient Approved',
            'message' => 'The patient has been successfully approved.',
            'time' => now()->diffForHumans(),
        ]);
    }

    public function massDecision(Request $request)
    {
        abort_if(Gate::denies('approve_patient'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'integer|exists:patient_records,id',
            'remarks' => 'required|string|max:1000',
            'action'  => 'required|in:approve,reject',
            'status_date' => 'required|date',
        ];

        if ($request->action === 'reject') {
            $rules['reasons']      = 'required|array|min:1';
            $rules['reasons.*']    = 'string|max:255';
            $rules['other_reason'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $approved = [];
        $rejected = [];
        $skipped  = [];

        DB::beginTransaction();

        try {
            foreach ($validated['ids'] as $id) {
                $patient = PatientRecord::find($id);
                if (!$patient) {
                    $skipped[] = $id;
                    continue;
                }

                $latestStatus = $patient->statusLogs()->latest()->first();

                // Skip if not submitted or already approved/rejected
                if (!$latestStatus || $latestStatus->status !== PatientStatusLog::STATUS_SUBMITTED) {
                    $skipped[] = $patient->control_number;
                    continue;
                }

                $status = $validated['action'] === 'approve'
                    ? PatientStatusLog::STATUS_APPROVED
                    : PatientStatusLog::STATUS_REJECTED;

                // Create status log
                $statusLog = $patient->statusLogs()->create([
                    'status'     => $status,
                    'user_id'    => Auth::id(),
                    'remarks'    => $validated['remarks'],
                    'status_date' => $validated['status_date'],
                    'created_at' => now(),
                ]);

                if ($validated['action'] === 'reject') {
                    $reasons = $validated['reasons'] ?? [];
                    if (!empty($validated['other_reason'])) {
                        $reasons[] = $validated['other_reason'];
                    }

                    foreach ($reasons as $reason) {
                        RejectionReason::create([
                            'patient_id'            => $patient->id,
                            'patient_status_log_id' => $statusLog->id,
                            'reason'                => $reason,
                        ]);
                    }

                    $rejected[] = $patient->id;
                } else {
                    $approved[] = $patient->id;
                }

                $actionEvent = $validated['action'] === 'approve' ? 'approved' : 'rejected';
                broadcast(new PatientStatusChanged($patient, $actionEvent));
            }

            DB::commit();

            $messages = [];
            if (count($approved)) {
                $messages[] = "✅ " . count($approved) . " " . (count($approved) === 1 ? 'patient has' : 'patients have') . " been approved";
            }
            if (count($rejected)) {
                $messages[] = "❌ " . count($rejected) . " " . (count($rejected) === 1 ? 'patient has' : 'patients have') . " been rejected";
            }
            if (count($skipped)) {
                $messages[] = "⚠️ " . count($skipped) . " " . (count($skipped) === 1 ? 'patient was' : 'patients were') . " skipped (not submitted or already processed)";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()
                ->route('admin.process-tracking.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Mass Decision Completed',
                    'message' => $toastMessage,
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'toast' => [
                    'type' => 'danger',
                    'title' => 'Error in Mass Decision',
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]
            ], 500);
        }
    }



    public function storeDV(Request $request, $id)
    {
        abort_if(Gate::denies('accounting_dv_input'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'dv_code' => 'required|string|max:255',
            'dv_date' => 'required|date',
            'status_date' => 'required|date',
        ]);

        $patient = PatientRecord::findOrFail($id);

        // Prevent multiple DV entries
        if ($patient->disbursementVoucher) {
            return back()->with('error', 'DV already submitted for this patient.');
        }

        $dv = DisbursementVoucher::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'dv_code' => $request->dv_code,
            'dv_date' => $request->dv_date,
        ]);

        // Log status
        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DV_SUBMITTED,
            'remarks' => 'DV recorded: ' . $request->dv_code,
            'status_date' => $request->status_date,
        ]);

        // ✅ Generate DV PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.pdf.dv', [
            'patient' => $patient,
            'dv'      => $dv,
        ]);

        $fileName = 'DV_' . $patient->id . '_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'documents/' . $patient->id . '/' . $fileName;

        // Save the PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());

        // Save document record
        Document::create([
            'patient_id'    => $patient->id,
            'file_name'     => $fileName,
            'file_path'     => $filePath,
            'document_type' => 'DV',
            'description'   => 'Disbursement Voucher PDF',
        ]);

        // Refresh patient for broadcasting
        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'dv_submitted'));

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Disbursement Voucher added successfully, PDF generated.');
    }


    public function updateDV(Request $request, $id)
    {
        $patient = PatientRecord::findOrFail($id);

        $validated = $request->validate([
            'dv_code' => 'required|string|max:255',
            'dv_date' => 'required|date',
            'status_date' => 'required|date',
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
            'status_date' => $validated['status_date'],
        ]);

        // ✅ Regenerate PDF
        if ($dv) {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.pdf.dv', [
                'patient' => $patient,
                'dv'      => $dv,
            ]);

            $fileName = 'DV_' . $patient->id . '_' . now()->format('Ymd_His') . '.pdf';
            $filePath = 'documents/' . $patient->id . '/' . $fileName;

            // Save new PDF
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update or create Document record
            $document = Document::where('patient_id', $patient->id)
                ->where('document_type', 'DV')
                ->first();

            if ($document) {
                // Delete old file
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $document->update([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'description' => 'Disbursement Voucher PDF (updated)',
                ]);
            } else {
                Document::create([
                    'patient_id'    => $patient->id,
                    'file_name'     => $fileName,
                    'file_path'     => $filePath,
                    'document_type' => 'DV',
                    'description'   => 'Disbursement Voucher PDF',
                ]);
            }
        }

        // Refresh patient for broadcasting
        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'dv_submitted'));

        return back()->with('success', 'DV updated, PDF regenerated, and status progressed.');
    }

    public function massDVInput(Request $request)
    {
        abort_if(Gate::denies('accounting_dv_input'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:patient_records,id',
            'dv_date' => 'required|date',
            'status_date' => 'required|date',
        ]);

        $submitted = [];
        $skipped   = [];

        DB::beginTransaction();

        try {
            $patients = PatientRecord::with([
                'latestStatusLog',
                'disbursementVoucher',
                'budgetAllocation'
            ])->whereIn('id', $request->ids)->get();

            foreach ($patients as $patient) {
                // Skip if already has DV
                if ($patient->disbursementVoucher) {
                    $skipped[] = "{$patient->control_number} (Already has DV)";
                    continue;
                }
                // Skip if no budget allocation record exists
                if (!$patient->budgetAllocation) {
                    $skipped[] = "{$patient->control_number} (No budget allocation)";
                    continue;
                }


                // Skip if latest status is not Budget Allocated
                if (!$patient->latestStatusLog || $patient->latestStatusLog->status !== PatientStatusLog::STATUS_BUDGET_ALLOCATED) {
                    $skipped[] = "{$patient->control_number} (Latest status not Budget Allocated)";
                    continue;
                }

                // Generate unique DV code
                $dvCode = $this->generateUniqueDvCode();

                // Create DV
                $dv = DisbursementVoucher::create([
                    'patient_id' => $patient->id,
                    'user_id'    => Auth::id(),
                    'dv_code'    => $dvCode,
                    'dv_date'    => $request->dv_date,
                ]);

                // Status log
                PatientStatusLog::create([
                    'patient_id' => $patient->id,
                    'user_id'    => Auth::id(),
                    'status'     => PatientStatusLog::STATUS_DV_SUBMITTED,
                    'remarks'    => 'DV recorded: ' . $dvCode,
                    'status_date' => $request->status_date,
                ]);

                $patient->load('latestStatusLog');
                broadcast(new PatientStatusChanged($patient, 'dv_submitted'));

                $submitted[] = $patient->control_number;
            }

            DB::commit();

            // Build toast messages
            $messages = [];
            if (count($submitted)) {
                $messages[] = "✅ " . count($submitted) . " patient" . (count($submitted) > 1 ? "s" : "") . " DV submitted";
            }
            if (count($skipped)) {
                $messages[] = "⚠️ " . count($skipped) . " patient" . (count($skipped) > 1 ? "s" : "") . " skipped (" . implode(", ", $skipped) . ")";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()->route('admin.process-tracking.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Mass DV Submission',
                    'message' => $toastMessage,
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast', [
                'type' => 'danger',
                'title' => 'Error in Mass DV Submission',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'time' => now()->diffForHumans(),
            ]);
        }
    }



    /**
     * Generate a unique DV Code (random, no duplicates).
     */
    private function generateUniqueDvCode()
    {
        do {
            // Example format: DV-2025-XXXXX (random 5 alphanumeric)
            $code = 'DV-' . date('Y') . '-' . strtoupper(Str::random(5));
        } while (DisbursementVoucher::where('dv_code', $code)->exists());

        return $code;
    }


    public function storeBudget(Request $request, $id)
    {
        abort_if(Gate::denies('budget_allocate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
            'status_date' => 'required|date',
        ]);

        $patient = PatientRecord::findOrFail($id);

        if ($patient->budgetAllocation) {
            return back()->with('error', 'Budget already allocated for this patient.');
        }

        // ✅ 1. Save Budget Allocation
        BudgetAllocation::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'remarks' => $request->remarks,
            'budget_status' => 'Not Disbursed',
            'allocation_date' => $request->status_date,
        ]);

        // ✅ 2. Save Status Log
        PatientStatusLog::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_BUDGET_ALLOCATED,
            'remarks' => 'Budget allocated: ₱' . number_format($request->amount, 2),
            'status_date' => $request->status_date,
        ]);

        // ✅ 3. Auto-generate OBRE PDF
        $data = [
            'patient' => $patient,
            'address' => $patient->address,
            'amount' => $request->amount,
            'remarks' => $request->remarks,
            'status_date' => $request->status_date,
            'prepared_by' => Auth::user()->name,
        ];

        $pdf = Pdf::loadView('admin.pdf.obre', $data);

        $fileName = 'OBRE_' . $patient->id . '_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'documents/' . $patient->id . '/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        // ✅ 4. Save to documents table
        Document::create([
            'patient_id' => $patient->id,
            'file_name' => $fileName,
            'file_path' => 'storage/' . $filePath,
            'document_type' => 'OBRE',
            'description' => 'Obligation Request and Status document',
        ]);

        // ✅ 5. Broadcast event
        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'budget_allocated'));

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Budget allocated and OBRE generated successfully.');
    }

    public function updateBudget(Request $request, $id)
    {
        $patient = PatientRecord::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
            'status_date' => 'required|date',
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
            'status_date' => $request->status_date,
        ]);

        // ✅ Re-generate OBRE PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.pdf.obre', [
            'patient' => $patient,
            'budget'  => $budget,
        ]);

        $fileName = 'OBRE_' . $patient->id . '_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'documents/' . $patient->id . '/' . $fileName;

        // Delete old OBRE if exists
        $oldDoc = Document::where('patient_id', $patient->id)
            ->where('document_type', 'OBRE')
            ->first();

        if ($oldDoc) {
            Storage::disk('public')->delete($oldDoc->file_path); // remove file
            $oldDoc->update([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'description' => 'Updated OBRE PDF after budget modification'
            ]);
        } else {
            Document::create([
                'patient_id' => $patient->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'document_type' => 'OBRE',
                'description' => 'OBRE PDF after budget update',
            ]);
        }

        Storage::disk('public')->put($filePath, $pdf->output());

        // Refresh patient for broadcast
        $patient->load('latestStatusLog');
        broadcast(new PatientStatusChanged($patient, 'budget_allocated'));

        return back()->with('success', 'Budget updated and OBRE PDF regenerated.');
    }


    public function massBudgetAllocate(Request $request)
    {
        abort_if(Gate::denies('budget_allocate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:patient_records,id',
            'amount' => 'required|numeric|min:0',
            'status_date' => 'required|date',
            'remarks' => 'nullable|string|max:1000',

        ]);

        $amount = $request->amount;
        $remarks = $request->remarks;

        $allocated = [];
        $skipped = [];

        DB::beginTransaction();

        try {
            $patients = PatientRecord::whereIn('id', $request->ids)->get();
            $statusDate = $request->status_date;

            foreach ($patients as $patient) {
                $latestStatus = $patient->latestStatusLog?->status;

                // Skip if not approved or already has budget
                if ($latestStatus !== PatientStatusLog::STATUS_APPROVED || $patient->budgetAllocation) {
                    $skipped[] = $patient->control_number;
                    continue;
                }

                // Allocate budget
                BudgetAllocation::create([
                    'patient_id' => $patient->id,
                    'user_id' => Auth::id(),
                    'amount' => $amount,
                    'remarks' => $remarks,
                    'allocation_date' => $statusDate,
                    'budget_status' => 'Not Disbursed',
                ]);

                PatientStatusLog::create([
                    'patient_id' => $patient->id,
                    'user_id' => Auth::id(),
                    'status' => PatientStatusLog::STATUS_BUDGET_ALLOCATED,
                    'remarks' => 'Budget allocated: ₱' . number_format($amount, 2),
                    'status_date' => $statusDate,
                ]);

                broadcast(new PatientStatusChanged($patient, 'budget_allocated'));

                $allocated[] = $patient->control_number;
            }

            DB::commit();

            $messages = [];
            if (count($allocated)) {
                $messages[] = "✅ " . count($allocated) . " patient" . (count($allocated) > 1 ? "s" : "") . " allocated budget";
            }
            if (count($skipped)) {
                $messages[] = "⚠️ " . count($skipped) . " patient" . (count($skipped) > 1 ? "s" : "") . " skipped (not approved or already has budget)";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()->route('admin.process-tracking.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Mass Budget Allocation',
                    'message' => $toastMessage,
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast', [
                'type' => 'danger',
                'title' => 'Error in Mass Budget Allocation',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'time' => now()->diffForHumans(),
            ]);
        }
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

        broadcast(new PatientStatusChanged($patient, 'disbursed'));

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Budget status updated to Disbursed.');
    }
    public function quickDisburse(Request $request, $id)
    {
        abort_if(Gate::denies('treasury_disburse'), 403);

        $allocation = BudgetAllocation::where('patient_id', $id)->firstOrFail();

        $allocation->update([
            'budget_status' => 'Disbursed',
        ]);

        PatientStatusLog::create([
            'patient_id' => $id,
            'user_id' => Auth::id(),
            'status' => PatientStatusLog::STATUS_DISBURSED,
            'remarks' => 'Budget marked as disbursed (OTP bypassed).',
            'status_date' => $request->input('status_date'),
        ]);

        $patient = PatientRecord::with('latestStatusLog')->findOrFail($id);
        broadcast(new PatientStatusChanged($patient, 'disbursed'));

        return redirect()->route('admin.process-tracking.show', $id)
            ->with('status', 'Budget status updated to Disbursed (no OTP).');
    }

    public function massQuickDisburse(Request $request)
    {
        abort_if(Gate::denies('treasury_disburse'), 403);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:patient_records,id',
            'status_date' => 'required|date',
        ]);

        $disbursed = [];
        $skipped   = [];

        DB::beginTransaction();

        try {
            foreach ($request->ids as $id) {
                $allocation = BudgetAllocation::where('patient_id', $id)->first();

                // Skip if no allocation found
                if (!$allocation) {
                    $skipped[] = $id . " (no allocation)";
                    continue;
                }

                // Skip if already disbursed
                if ($allocation->budget_status === 'Disbursed') {
                    $skipped[] = $id . " (already disbursed)";
                    continue;
                }

                // Only allow quick disburse if DV is submitted
                $hasDv = DisbursementVoucher::where('patient_id', $id)->exists();
                if (!$hasDv) {
                    $skipped[] = $id . " (no DV submitted)";
                    continue;
                }

                // Update to Disbursed
                $allocation->update([
                    'budget_status' => 'Disbursed',
                ]);

                PatientStatusLog::create([
                    'patient_id' => $id,
                    'user_id' => Auth::id(),
                    'status' => PatientStatusLog::STATUS_DISBURSED,
                    'remarks' => 'Budget marked as disbursed (mass quick).',
                    'status_date' => $request->status_date,
                ]);

                $patient = PatientRecord::with('latestStatusLog')->find($id);
                if ($patient) {
                    broadcast(new PatientStatusChanged($patient, 'disbursed'));
                    $disbursed[] = $patient->control_number ?? $id;
                }
            }

            DB::commit();

            // Build messages
            $messages = [];
            if (count($disbursed)) {
                $messages[] = "✅ " . count($disbursed) . " patient" . (count($disbursed) > 1 ? "s" : "") . " disbursed";
            }
            if (count($skipped)) {
                $messages[] = "⚠️ " . count($skipped) . " skipped (no DV submitted or already disbursed)";
            }

            $toastMessage = implode('<br>', $messages);

            return redirect()->route('admin.process-tracking.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Mass Quick Disburse',
                    'message' => $toastMessage,
                    'time' => now()->diffForHumans(),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast', [
                'type' => 'danger',
                'title' => 'Error in Mass Quick Disburse',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'time' => now()->diffForHumans(),
            ]);
        }
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
            'status_date' => now(),
        ]);

        return redirect()->route('admin.process-tracking.show', $patient->id)
            ->with('status', 'OTP verified and disbursement completed.');
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
            'status_date' => now(),
        ]);

        // Reload latest status log before broadcasting
        $patient->load('latestStatusLog');

        // Broadcast base status only (for UI logic)
        broadcast(new PatientStatusChanged($patient, strtolower($rollbackTo)));

        return redirect()->back()->with('success', 'Process rolled back to ' . $rolledBackStatus);
    }

    public function returnToRollbacker($id)
    {
        $patient = PatientRecord::findOrFail($id);

        // Get the latest log (this should be the rollback one)
        $latestLog = $patient->statusLogs()->latest()->first();

        // Get the log before it
        $previousLog = $patient->statusLogs()
            ->where('id', '<', $latestLog->id)
            ->latest()
            ->first();

        if (!$previousLog) {
            return back()->with('error', 'No previous status found to return to.');
        }

        // Create a new log to restore previous status
        $restoredLog = PatientStatusLog::create([
            'patient_id' => $patient->id,
            'status'     => $previousLog->status, // no rollback tag
            'user_id'    => Auth::id(),
            'remarks'    => 'Returned to rollbacker: ' . $previousLog->status,
            'status_date' => now(),
        ]);

        broadcast(new PatientStatusChanged($patient, strtolower($previousLog->status)));

        return back()->with('status', 'Case returned to previous rollbacker.');
    }
}
