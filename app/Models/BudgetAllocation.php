<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocation extends Model
{
         use Auditable;
        protected $fillable = [
        'patient_id',
        'user_id',
        'amount',
        'remarks',
        'budget_status',
        'allocation_date'
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
