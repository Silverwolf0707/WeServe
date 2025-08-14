<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class BudgetRecordController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('budget_records'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.budgetRecords.index');
    }
}