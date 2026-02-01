<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class BudgetRecordController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('budget_records'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $searchTerm = $request->get('search', '');
        
        $query = BudgetAllocation::with('patient')
            ->orderByDesc('allocation_date');
        
        // Apply search if term exists
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                // Search in budget allocation fields
                $q->where('amount', 'like', "%{$searchTerm}%")
                  ->orWhere('remarks', 'like', "%{$searchTerm}%")
                  ->orWhere('budget_status', 'like', "%{$searchTerm}%")
                  // Search in related patient fields
                  ->orWhereHas('patient', function ($patientQuery) use ($searchTerm) {
                      $patientQuery->where('patient_name', 'like', "%{$searchTerm}%")
                                   ->orWhere('control_number', 'like', "%{$searchTerm}%")
                                   ->orWhere('claimant_name', 'like', "%{$searchTerm}%")
                                   ->orWhere('case_category', 'like', "%{$searchTerm}%")
                                   ->orWhere('case_type', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $budgetAllocations = $query->paginate(100)->withQueryString();
        
        return view('admin.budgetRecords.index', compact('budgetAllocations', 'searchTerm'));
    }
}