<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePatientApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'online_patient_application'; 

    protected $fillable = [
        'case_type',
        'control_number',
        'claimant_name',
        'case_category',
        'applicant_name',
        'diagnosis',
        'age',
        'address',
        'contact_number',
        'tracking_number',
    ];
    
}
