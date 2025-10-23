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
}
