<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('partials.settings');
    }

    public function deleteAll()
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Delete all patient records (cascade will handle related tables)
        PatientRecord::query()->delete();

        return back()->with('success', 'All patient records and related data have been deleted successfully.');
    }
}
