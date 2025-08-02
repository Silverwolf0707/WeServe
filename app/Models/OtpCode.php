<?php

// app/Models/OtpCode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'otp_code',
        'sent_at',
        'is_verified',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

