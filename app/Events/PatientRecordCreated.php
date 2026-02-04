<?php

// app/Events/PatientRecordCreated.php
namespace App\Events;

use App\Models\PatientRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PatientRecordCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $patient;

    public function __construct(PatientRecord $patient)
    {
        $this->patient = $patient;
    }

    public function broadcastOn()

    {
        return new Channel('patients');
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->patient->id,
            'date_processed' => $this->patient->date_processed,
            'case_type' => $this->patient->case_type,
            'control_number' => $this->patient->control_number,
            'claimant_name' => $this->patient->claimant_name,
            'case_category' => $this->patient->case_category,
            'patient_name' => $this->patient->patient_name,
            'diagnosis' => $this->patient->diagnosis,
            'age' => $this->patient->age,
            'address' => $this->patient->address,
            'contact_number' => $this->patient->contact_number,
            'case_worker' => $this->patient->case_worker,
            'action' => 'patient created', 
        ];
    }
    public function broadcastAs()
    {
        return 'patientRecord.changed';
    }
}
