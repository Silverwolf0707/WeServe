<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function getAgeStatistics(Request $request)
    {
        $year = $request->input('year');

        $query = PatientRecord::query();

        if ($year) {
            $query->whereYear('date_processed', $year);
        }

        $ages = $query->pluck('age')->filter()->sort()->values();

        if ($ages->isEmpty()) {
            return response()->json(['message' => 'No age data for selected year.'], 404);
        }

        $count = $ages->count();
        $mean = $ages->avg();

        $median = ($count % 2 === 0)
            ? round(($ages[$count / 2 - 1] + $ages[$count / 2]) / 2, 2)
            : $ages[floor($count / 2)];

        $mode = $ages->countBy()->sortDesc()->keys()->first() ?? null;
        $variance = $ages->map(fn($x) => pow($x - $mean, 2))->avg();
        $stdDev = sqrt($variance);

        return response()->json([
            'mean' => round($mean),
            'median' => $median,
            'mode' => $mode,
            'variance' => round($variance, 2),
            'standard_deviation' => round($stdDev),
            'year' => $year
        ]);
    }
}
