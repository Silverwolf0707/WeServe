<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'patient_id',
        'file_name',
        'file_path',
        'document_type',
        'description'
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRecord::class);
    }
}
