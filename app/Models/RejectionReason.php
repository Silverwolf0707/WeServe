<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RejectionReason extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'patient_status_log_id',
        'reason',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRecord::class);
    }

    public function statusLog()
    {
        return $this->belongsTo(PatientStatusLog::class, 'patient_status_log_id');
    }
}
