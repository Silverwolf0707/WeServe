<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientStatusLog extends Model
{
    use SoftDeletes, Auditable;

    public $timestamps = true;

    const STATUS_DRAFT = 'Draft';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_SUBMITTED = 'Submitted';
    const STATUS_SUBMITTED_EMERGENCY = 'Submitted[Emergency]';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_BUDGET_ALLOCATED = 'Budget Allocated';
    const STATUS_DV_SUBMITTED = 'DV Submitted';
    const STATUS_DISBURSED = 'Disbursed';
    const STATUS_ROLLED_BACK_TO_PROCESSING = 'Processing[ROLLED BACK]';
    const STATUS_READY_FOR_DISBURSEMENT = 'Ready for Disbursement';
    const STATUS_ROLLED_BACK_TO_DRAFT = 'Draft[ROLLED BACK]';
    const STATUS_ROLLED_BACK_TO_SUBMITTED = 'Submitted[ROLLED BACK]';
    const STATUS_ROLLED_BACK_TO_APPROVED = 'Approved[ROLLED BACK]';
    const STATUS_ROLLED_BACK_TO_BUDGET_ALLOCATED = 'Budget Allocated[ROLLED BACK]';

    protected $fillable = [
        'patient_id',
        'status',
        'user_id',
        'status_date',
        'created_at',
        'remarks',
    ];
    protected $casts = [
        'status_date' => 'datetime',
    ];


    public function patient()
    {
        return $this->belongsTo(PatientRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBaseStatusAttribute(): string
    {
        return str_replace('[ROLLED BACK]', '', $this->status);
    }
    public function statusLogs()
    {
        return $this->hasMany(\App\Models\PatientStatusLog::class, 'patient_id');
        // we'll temporarily use patient_id = null or handle via pivot later
    }

     public function userNotifications()
    {
        return $this->hasMany(UserNotification::class, 'status_log_id');
    }

       public function getNotificationData()
    {
        $patient = $this->patient;
        
        return [
            'type' => 'status_change',
            'title' => $this->getNotificationTitle(),
            'message' => $this->getNotificationMessage(),
            'patient_id' => $patient->id,
            'status' => $this->status,
            'control_number' => $patient->control_number,
            'patient_name' => $patient->patient_name,
            'created_at' => $this->created_at,
            'user_name' => $this->user->name ?? 'System',
            'user_id' => $this->user_id,
        ];
    }

    private function getNotificationTitle()
    {
        $titles = [
            'Processing' => 'New Patient Application',
            'Submitted' => 'Application Submitted',
            'Submitted[Emergency]' => 'Emergency Application Submitted',
            'Approved' => 'Application Approved',
            'Rejected' => 'Application Rejected',
            'Budget Allocated' => 'Budget Allocated',
            'DV Submitted' => 'DV Submitted',
            'Disbursed' => 'Funds Disbursed',
            'Ready for Disbursement' => 'Ready for Disbursement',
            
            // Rolled back titles
            'Processing[ROLLED BACK]' => 'Rolled Back to CSWD Office',
            'Submitted[ROLLED BACK]' => 'Rolled Back to Mayor\'s Office',
            'Approved[ROLLED BACK]' => 'Rolled Back to Budget Office',
            'Budget Allocated[ROLLED BACK]' => 'Rolled Back to Accounting Office',
           
            
        ];

        return $titles[$this->status] ?? 'Status Updated';
    }

    private function getNotificationMessage()
    {
        $patient = $this->patient;
        $baseMessage = "Patient: {$patient->patient_name} (Control #: {$patient->control_number})";

        $messages = [
            'Processing' => "{$baseMessage} - New application created",
            'Submitted' => "{$baseMessage} - Application has been submitted for review",
            'Submitted[Emergency]' => "{$baseMessage} - Emergency application submitted",
            'Approved' => "{$baseMessage} - Application has been approved",
            'Rejected' => "{$baseMessage} - Application has been rejected",
            'Budget Allocated' => "{$baseMessage} - Budget has been allocated",
            'DV Submitted' => "{$baseMessage} - Disbursement Voucher has been submitted",
            'Disbursed' => "{$baseMessage} - Funds have been disbursed",
            'Ready for Disbursement' => "{$baseMessage} - Ready for disbursement",
            
            // Rolled back messages
            'Processing[ROLLED BACK]' => "{$baseMessage} - Status rolled back to Processing",
            'Submitted[ROLLED BACK]' => "{$baseMessage} - Status rolled back to Submitted",
            'Approved[ROLLED BACK]' => "{$baseMessage} - Status rolled back to Approved",
            'Budget Allocated[ROLLED BACK]' => "{$baseMessage} - Status rolled back to Budget Allocated",
            

        ];

        return $messages[$this->status] ?? "{$baseMessage} - Status updated to: {$this->status}";
    }
}
