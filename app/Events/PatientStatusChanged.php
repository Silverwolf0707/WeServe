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

    public function __construct(PatientRecord $patient, $action)
    {
        $this->patient = $patient->load('latestStatusLog');
        $this->action = $action;
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
        return [
            'id' => $this->patient->id,
            'control_number' => $this->patient->control_number,
            'date_processed' => $this->patient->date_processed,
            'claimant_name' => $this->patient->claimant_name,
            'case_worker' => $this->patient->case_worker,
            'status' => $this->patient->latestStatusLog->status ?? 'Submitted',
            'action' => $this->action,
        ];
    }
}
