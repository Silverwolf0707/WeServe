<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StatisticsController extends Controller
{
    public function getAgeStatistics(Request $request)
    {
        if (!Gate::allows('CSWD-ANALYTICS')) {
            abort(403);
        }
         if (!Gate::allows('BUDGET-ANALYTICS')) {
            abort(403);
        }
         if (!Gate::allows('TREASURY-ANALYTICS')) {
            abort(403);
        }
        if (!Gate::allows('ACCOUNTING-ANALYTICS')) {
            abort(403);
        }
       
    }
}