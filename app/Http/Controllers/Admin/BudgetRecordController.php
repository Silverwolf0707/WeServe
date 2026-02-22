<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetAllocation;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class BudgetRecordController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('budget_records'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $searchTerm = $request->get('search', '');
        $caseCategory = $request->get('case_category', '');
        $caseType = $request->get('case_type', '');
        $dateProcessed = $request->get('date_processed', '');
        
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
        
        // Apply case category filter
        if ($caseCategory) {
            $query->whereHas('patient', function ($q) use ($caseCategory) {
                $q->where('case_category', $caseCategory);
            });
        }
        
        // Apply case type filter
        if ($caseType) {
            $query->whereHas('patient', function ($q) use ($caseType) {
                $q->where('case_type', $caseType);
            });
        }
        
        // Apply date processed filter
        if ($dateProcessed) {
            $query->whereDate('allocation_date', $dateProcessed);
        }
        
        $budgetAllocations = $query->paginate(100)->withQueryString();
        
        // ── DYNAMIC dropdown option lists ────────────────────────────
        // OPTION 1: Get from PatientRecord model (all patients) - shows ALL possible options
        $caseCategoryOptions = PatientRecord::distinct()
            ->whereNotNull('case_category')
            ->where('case_category', '!=', '')
            ->orderBy('case_category')
            ->pluck('case_category')
            ->mapWithKeys(function ($category) {
                // Check if there's a predefined label in the PatientRecord model
                $label = defined('App\Models\PatientRecord::CASE_CATEGORY_SELECT') 
                    ? (PatientRecord::CASE_CATEGORY_SELECT[$category] ?? $category)
                    : $category;
                return [$category => $label];
            })
            ->toArray();
        
        $caseTypeOptions = PatientRecord::distinct()
            ->whereNotNull('case_type')
            ->where('case_type', '!=', '')
            ->orderBy('case_type')
            ->pluck('case_type')
            ->mapWithKeys(function ($type) {
                // Check if there's a predefined label in the PatientRecord model
                $label = defined('App\Models\PatientRecord::CASE_TYPE_SELECT') 
                    ? (PatientRecord::CASE_TYPE_SELECT[$type] ?? $type)
                    : $type;
                return [$type => $label];
            })
            ->toArray();
        
        // OPTION 2: Get only from patients that actually have budget allocations (more efficient)
        // $caseCategoryOptions = BudgetAllocation::whereHas('patient', function($q) {
        //         $q->whereNotNull('case_category')->where('case_category', '!=', '');
        //     })
        //     ->with('patient')
        //     ->get()
        //     ->pluck('patient.case_category')
        //     ->unique()
        //     ->filter()
        //     ->values()
        //     ->mapWithKeys(function ($category) {
        //         $label = defined('App\Models\PatientRecord::CASE_CATEGORY_SELECT') 
        //             ? (PatientRecord::CASE_CATEGORY_SELECT[$category] ?? $category)
        //             : $category;
        //         return [$category => $label];
        //     })
        //     ->toArray();
        
        // $caseTypeOptions = BudgetAllocation::whereHas('patient', function($q) {
        //         $q->whereNotNull('case_type')->where('case_type', '!=', '');
        //     })
        //     ->with('patient')
        //     ->get()
        //     ->pluck('patient.case_type')
        //     ->unique()
        //     ->filter()
        //     ->values()
        //     ->mapWithKeys(function ($type) {
        //         $label = defined('App\Models\PatientRecord::CASE_TYPE_SELECT') 
        //             ? (PatientRecord::CASE_TYPE_SELECT[$type] ?? $type)
        //             : $type;
        //         return [$type => $label];
        //     })
        //     ->toArray();
        
        // Sort alphabetically
        asort($caseCategoryOptions);
        asort($caseTypeOptions);
        // ─────────────────────────────────────────────────────────────
        
        return view('admin.budgetRecords.index', compact(
            'budgetAllocations', 
            'searchTerm', 
            'caseCategory', 
            'caseType', 
            'dateProcessed',
            'caseCategoryOptions',
            'caseTypeOptions'
        ));
    }
}