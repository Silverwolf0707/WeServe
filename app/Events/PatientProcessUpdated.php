<?php

namespace App\Events;

use App\Models\PatientRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PatientProcessUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $patient;
    public $latestLog;

    public function __construct(PatientRecord $patient)
    {
        $this->patient = $patient->load('latestStatusLog'); // load latest log
        $this->latestLog = $this->patient->latestStatusLog;
    }

    public function broadcastOn()
    {
        return new Channel('process-tracking');
    }

    public function broadcastAs()
    {
        return 'patient.process.updated';
    }

    public function broadcastWith()
    {

        return [
            'id' => $this->patient->id,
            'status' => $this->latestLog->status ?? 'Submitted',
            'status_date' => $this->latestLog->status_date
                ? $this->latestLog->status_date->format('Y-m-d H:i:s')
                : null,
            'remarks' => $this->latestLog->remarks ?? '-',
            'updated_by' => $this->latestLog->user->name ?? 'System',
        ];
    }
}
