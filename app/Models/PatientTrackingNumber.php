<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientTrackingNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'tracking_number',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRecord::class, 'patient_id');
    }
    
}
