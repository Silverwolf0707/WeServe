<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetAllocation extends Model
{
        protected $fillable = [
        'patient_id',
        'user_id',
        'amount',
        'remarks',
        'budget_status'
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
