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

        PatientRecord::withTrashed()->forceDelete();

        $files = [
            storage_path('app/public/stl_output.json'),
            storage_path('app/public/stl_output_meta.json'),
            storage_path('app/public/stl_budget_output.json'),
            storage_path('app/public/stl_budget_output_meta.json'),

            storage_path('app/public/age_stats_output.json'),
            storage_path('app/public/age_stats_meta.json'),
            storage_path('app/public/budget_stats_output.json'),
            storage_path('app/public/budget_stats_meta.json'),
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return redirect()->route('admin.settings.index')->with('toast', [
            'type' => 'success',
            'title' => 'All Records Deleted',
            'message' => 'All patient records have been permanently deleted.',
            'time' => now()->diffForHumans(),
        ]);
    }
}
