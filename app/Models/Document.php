<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use Auditable;
    protected $fillable = [
        'patient_id',
        'file_name',
        'file_path',
        'document_type',
        'description',
        'file_size',
        'file_extension',
        'uploaded_by',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRecord::class);
    }
}
