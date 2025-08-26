<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

        // ⚠️ Drop all data but keep migrations table
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $key = "Tables_in_$dbName";

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            $tableName = $table->$key;
            if ($tableName === 'migrations') {
                continue; // Keep migrations table
            }
            DB::table($tableName)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return back()->with('success', 'All data has been deleted successfully.');
    }
}
