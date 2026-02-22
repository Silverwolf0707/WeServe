<?php

namespace App\Http\Controllers\Traits;

use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\SimpleExcel\SimpleExcelWriter;

trait ExportTrait
{
    /**
     * Export patient records to CSV or Excel, respecting all active filters.
     *
     * Query params honoured:
     *   show_deleted, status_filter, search,
     *   barangay, date_month (YYYY-MM), case_category, case_type
     *
     * Route params:
     *   format = 'csv' | 'excel'
     */
    public function export(Request $request, string $format = 'csv')
    {
        // ── 1. Resolve filters (same logic as index) ──────────────────
        $showDeleted        = $request->boolean('show_deleted');
        $statusFilter       = $request->get('status_filter', '');
        $searchTerm         = $request->get('search', '');
        $barangayFilter     = $request->get('barangay', '');
        $dateMonthFilter    = $request->get('date_month', '');
        $caseCategoryFilter = $request->get('case_category', '');
        $caseTypeFilter     = $request->get('case_type', '');

        $query = $showDeleted
            ? PatientRecord::onlyTrashed()
            : PatientRecord::query();

        $query->with(['latestStatusLog'])
              ->orderByDesc($showDeleted ? 'deleted_at' : 'date_processed');

        // Status filter
        if ($statusFilter && !$showDeleted) {
            $query->whereHas('latestStatusLog', function ($q) use ($statusFilter) {
                if ($statusFilter === 'Processing') {
                    $q->whereIn('status', ['Processing', 'Rejected']);
                } elseif ($statusFilter === 'Submitted') {
                    $q->where('status', 'like', 'Submitted%')
                      ->where('status', 'not like', '%[ROLLED BACK]%');
                }
            });
        }

        // Full-text search
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('control_number',   'like', "%{$searchTerm}%")
                  ->orWhere('patient_name',   'like', "%{$searchTerm}%")
                  ->orWhere('claimant_name',  'like', "%{$searchTerm}%")
                  ->orWhere('diagnosis',      'like', "%{$searchTerm}%")
                  ->orWhere('address',        'like', "%{$searchTerm}%")
                  ->orWhere('contact_number', 'like', "%{$searchTerm}%")
                  ->orWhere('case_worker',    'like', "%{$searchTerm}%")
                  ->orWhere('case_type',      'like', "%{$searchTerm}%")
                  ->orWhere('case_category',  'like', "%{$searchTerm}%");
            });
        }

        // Barangay
        if ($barangayFilter) {
            $query->where('address', 'like', "%{$barangayFilter}%");
        }

        // Month filter (YYYY-MM)
        if ($dateMonthFilter) {
            [$year, $month] = array_pad(explode('-', $dateMonthFilter), 2, null);
            if ($year && $month) {
                $query->whereYear('date_processed', (int) $year)
                      ->whereMonth('date_processed', (int) $month);
            }
        }

        // Case category / type
        if ($caseCategoryFilter) {
            $query->where('case_category', $caseCategoryFilter);
        }
        if ($caseTypeFilter) {
            $query->where('case_type', $caseTypeFilter);
        }

        // ── 2. Build rows ─────────────────────────────────────────────
        $records = $query->get();

        $headers = [
            'Control Number',
            'Date Processed',
            'Case Type',
            'Case Category',
            'Patient Name',
            'Claimant Name',
            'Diagnosis',
            'Age',
            'Address',
            'Barangay',
            'Contact Number',
            'Case Worker',
            'Status',
        ];

        if ($showDeleted) {
            $headers[] = 'Deleted At';
        }

        $rows = $records->map(function ($record) use ($showDeleted) {
            $latestStatus = $record->latestStatusLog;
            $statusValue  = $latestStatus->status ?? ($showDeleted ? 'Deleted' : 'Processing');
            $cleanStatus  = trim(preg_replace('/\[ROLLED BACK\]/i', '', $statusValue));

            // Use the model's barangay accessor
            $barangay = $record->barangay ?? 'Unknown';

            $row = [
                $record->control_number  ?? '',
                $record->date_processed
                    ? Carbon::parse($record->date_processed)->format('Y-m-d H:i:s')
                    : '',
                $record->case_type       ?? '',
                $record->case_category   ?? '',
                $record->patient_name    ?? '',
                $record->claimant_name   ?? '',
                $record->diagnosis       ?? '',
                $record->age             ?? '',
                $record->address         ?? '',
                $barangay,
                $record->contact_number  ?? '',
                $record->case_worker     ?? '',
                $cleanStatus,
            ];

            if ($showDeleted) {
                $row[] = $record->deleted_at
                    ? Carbon::parse($record->deleted_at)->format('Y-m-d H:i:s')
                    : '';
            }

            return $row;
        })->toArray();

        // ── 3. Build filename ─────────────────────────────────────────
        $parts = ['patient_records'];
        if ($showDeleted)        $parts[] = 'deleted';
        if ($statusFilter)       $parts[] = strtolower($statusFilter);
        if ($barangayFilter)     $parts[] = 'brgy_' . Str_slug($barangayFilter);
        if ($dateMonthFilter)    $parts[] = $dateMonthFilter;
        if ($caseCategoryFilter) $parts[] = Str_slug($caseCategoryFilter);
        if ($caseTypeFilter)     $parts[] = Str_slug($caseTypeFilter);
        $parts[] = Carbon::now()->format('Ymd_His');

        $filename = implode('_', $parts);

        // ── 4. Stream the file ────────────────────────────────────────
        if ($format === 'excel') {
            return $this->streamExcel($filename . '.xlsx', $headers, $rows);
        }

        return $this->streamCsv($filename . '.csv', $headers, $rows);
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    private function streamCsv(string $filename, array $headers, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens it correctly
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function streamExcel(string $filename, array $headers, array $rows): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $path = storage_path('app/exports/' . $filename);

        // Ensure exports directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = SimpleExcelWriter::create($path)
            ->addHeader($headers);

        foreach ($rows as $row) {
            // SimpleExcelWriter expects associative arrays — zip headers with values
            $writer->addRow(array_combine($headers, $row));
        }

        $writer->close();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Simple slug helper (avoids pulling in Str facade just for this).
     */
    private function Str_slug(string $value): string
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $value), '_'));
    }
}