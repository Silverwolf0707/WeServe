<?php

namespace App\Events;

use App\Models\PatientRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PatientStatusChanged implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $patient;
    public $action;
    public $rejectionReasons;

    public function __construct(PatientRecord $patient, $action, $rejectionReasons = null)
    {
        $this->patient = $patient->load([
            'latestStatusLog', 
            'rejectionReasons',
            'budgetAllocation',
            'disbursementVoucher',
            'latestStatusLog.user.roles'
        ]);
        $this->action = $action;
        $this->rejectionReasons = $rejectionReasons;
    }

    public function broadcastOn()
    {
        return new Channel('process-tracking');
    }

    public function broadcastAs()
    {
        return 'patient.status.changed';
    }

    public function broadcastWith()
    {
        $baseData = [
            'id' => $this->patient->id,
            'control_number' => $this->patient->control_number,
            'date_processed' => $this->patient->date_processed,
            'claimant_name' => $this->patient->claimant_name,
            'case_worker' => $this->patient->case_worker,
            'status' => $this->patient->latestStatusLog->status ?? 'Submitted',
            'status_date' => $this->patient->latestStatusLog->status_date ?? null,
            'remarks' => $this->patient->latestStatusLog->remarks,
            'action' => $this->action,
            'user_name' => $this->patient->latestStatusLog->user->name ?? 'System',
            'role' => $this->patient->latestStatusLog->user->roles->first()->title ?? 'Unknown Role',
            'user_id' => $this->patient->latestStatusLog->user->id ?? null,
        ];

        // Include budget data if status is Budget Allocated
        if ($this->patient->latestStatusLog && 
            $this->patient->latestStatusLog->status === \App\Models\PatientStatusLog::STATUS_BUDGET_ALLOCATED && 
            $this->patient->budgetAllocation) {
            $baseData['budget_amount'] = $this->patient->budgetAllocation->amount;
            $baseData['allocation_date'] = $this->patient->budgetAllocation->allocation_date;
        }

        // Include DV data if status is DV Submitted
        if ($this->patient->latestStatusLog && 
            $this->patient->latestStatusLog->status === \App\Models\PatientStatusLog::STATUS_DV_SUBMITTED && 
            $this->patient->disbursementVoucher) {
            $baseData['dv_code'] = $this->patient->disbursementVoucher->dv_code;
            $baseData['dv_date'] = $this->patient->disbursementVoucher->dv_date;
        }

        // Only include rejection reasons if action is 'rejected'
        if ($this->action === 'rejected' && $this->rejectionReasons) {
            $baseData['rejection_reasons'] = $this->rejectionReasons;
        }

        return $baseData;
    }
}