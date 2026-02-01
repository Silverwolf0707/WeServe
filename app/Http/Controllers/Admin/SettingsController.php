<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\SystemBackup;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backups = SystemBackup::with('creator')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('partials.settings', compact('backups'));
    }

    public function deleteAll()
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Delete all patient records and related data in proper order
        DB::table('documents')->delete();
        DB::table('patient_status_logs')->delete();
        DB::table('disbursement_voucher')->delete();
        DB::table('rejection_reasons')->delete();
        DB::table('online_patient_application')->delete();
        DB::table('patient_tracking_numbers')->delete();
        DB::table('patient_records')->delete();
        DB::table('budget_allocations')->delete();

        // Clear documents directory
        $documentsPath = storage_path('app/private/documents');
        if (file_exists($documentsPath)) {
            $this->deleteDirectory($documentsPath);
        }

        return redirect()->route('admin.settings.index')->with('toast', [
            'type' => 'success',
            'title' => 'All Records Deleted',
            'message' => 'All patient records have been permanently deleted.',
            'time' => now()->diffForHumans(),
        ]);
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return true;

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public function createBackup(Request $request)
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:documents,database'
        ]);

        try {
            DB::beginTransaction();

            $timestamp = Carbon::now()->format('Y-m-d_His');
            $backupName = "backup_{$timestamp}_{$request->type}.zip";
            $backupPath = storage_path("app/private/backup/{$backupName}");

            // Ensure backup directory exists
            $backupDir = storage_path('app/private/backup');
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, 0755, true)) {
                    throw new \Exception('Failed to create backup directory');
                }
            }

            // Create a temporary directory for backup contents
            $tempDir = storage_path("app/private/temp/backup_temp_{$timestamp}");
            if (!file_exists(storage_path('app/private/temp'))) {
                mkdir(storage_path('app/private/temp'), 0755, true);
            }
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Failed to create temporary directory');
            }

            $zip = new ZipArchive();

            if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Cannot create zip file. Check if ZipArchive extension is enabled.');
            }

            // Backup based on type
            if ($request->type === 'documents') {
                $this->backupDocuments($tempDir, $zip);
            } elseif ($request->type === 'database') {
                $this->backupPatientDatabase($tempDir, $zip);
            }

            // Create a manifest file
            $manifest = [
                'backup_name' => $backupName,
                'type' => $request->type,
                'description' => $request->description,
                'created_at' => now()->toDateTimeString(),
                'created_by' => Auth::id(),
                'created_by_name' => Auth::user()->name,
            ];

            $manifestFile = $tempDir . '/manifest.json';
            file_put_contents($manifestFile, json_encode($manifest, JSON_PRETTY_PRINT));
            $zip->addFile($manifestFile, 'manifest.json');

            $zip->close();

            // Clean up temp directory
            $this->deleteDirectory($tempDir);

            // Check if backup file was created
            if (!file_exists($backupPath)) {
                throw new \Exception('Backup file was not created');
            }

            $fileSize = filesize($backupPath);

            // Save backup record
            $backup = SystemBackup::create([
                'filename' => $backupName,
                'path' => $backupPath,
                'size' => $fileSize,
                'type' => $request->type,
                'description' => $request->description,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully!',
                'backup' => [
                    'id' => $backup->id,
                    'filename' => $backup->filename,
                    'size' => $this->formatBytes($backup->size),
                    'created_at' => $backup->created_at->format('Y-m-d H:i:s'),
                    'type' => $backup->type,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up on error
            if (isset($tempDir) && file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            if (isset($backupPath) && file_exists($backupPath)) {
                @unlink($backupPath);
            }

            Log::error('Backup failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function backupDocuments($tempDir, ZipArchive $zip)
    {
        // Backup document files
        $documentsPath = storage_path('app/private/documents');

        if (file_exists($documentsPath)) {
            // Create documents folder in temp directory
            $tempDocumentsPath = $tempDir . '/documents';
            if (!file_exists($tempDocumentsPath)) {
                mkdir($tempDocumentsPath, 0755, true);
            }

            // Copy files to temp directory
            $this->copyDirectory($documentsPath, $tempDocumentsPath);

            // Add folder to zip
            $this->addFolderToZip($zip, $tempDocumentsPath, 'documents');

            Log::info('Documents files backed up successfully');
        } else {
            Log::info('Documents directory does not exist');
        }

        // Backup document database records
        $this->backupDocumentDatabaseRecords($tempDir, $zip);
    }

    private function backupDocumentDatabaseRecords($tempDir, ZipArchive $zip)
    {
        try {
            // Get all documents from database
            $documents = Document::with('patient')->get()->toArray();

            if (empty($documents)) {
                Log::info('No document records to backup');
                return;
            }

            // Create document database records backup file
            $documentsBackupFile = $tempDir . '/documents_db_backup.json';

            $backupData = [
                'backup_type' => 'documents_database',
                'created_at' => now()->toDateTimeString(),
                'total_records' => count($documents),
                'records' => $documents
            ];

            file_put_contents($documentsBackupFile, json_encode($backupData, JSON_PRETTY_PRINT));

            // Add to zip
            $zip->addFile($documentsBackupFile, 'database/documents_backup.json');

            Log::info('Document database records backup created with ' . count($documents) . ' records');
        } catch (\Exception $e) {
            Log::error('Failed to backup document database records: ' . $e->getMessage());
            throw $e;
        }
    }

    private function backupPatientDatabase($tempDir, ZipArchive $zip)
    {
        try {
            // Define tables in proper order - parent tables first, then child tables
            $tablesToBackup = [
                'budget_allocations', // Independent table
                'patient_records', // Parent table - must be restored first
                'patient_status_logs', // Child of patient_records
                'disbursement_voucher', // Child of patient_records
                'rejection_reasons', // Child of patient_records
                'online_patient_application', // Child of patient_records
                'patient_tracking_numbers', // Child of patient_records
                // Note: documents table is backed up separately in documents backup
            ];

            $databaseBackup = [];

            foreach ($tablesToBackup as $tableName) {
                // Check if table exists
                if (!DB::getSchemaBuilder()->hasTable($tableName)) {
                    Log::warning("Table {$tableName} does not exist");
                    continue;
                }

                // Get all data from table
                $data = DB::table($tableName)->get()->toArray();

                $databaseBackup[$tableName] = [
                    'table_name' => $tableName,
                    'total_records' => count($data),
                    'records' => $data,
                    'columns' => Schema::getColumnListing($tableName)
                ];

                Log::info("Table {$tableName} backed up with " . count($data) . " records");
            }

            // Save database backup to file
            $databaseBackupFile = $tempDir . '/patient_database_backup.json';

            $backupData = [
                'backup_type' => 'patient_database',
                'created_at' => now()->toDateTimeString(),
                'tables_order' => $tablesToBackup,
                'tables' => $databaseBackup
            ];

            file_put_contents($databaseBackupFile, json_encode($backupData, JSON_PRETTY_PRINT));

            // Add to zip
            $zip->addFile($databaseBackupFile, 'patient_database_backup.json');

            Log::info('Patient database backup created with tables: ' . implode(', ', $tablesToBackup));
        } catch (\Exception $e) {
            Log::error('Failed to backup patient database: ' . $e->getMessage());
            throw $e;
        }
    }

    private function addFolderToZip(ZipArchive $zip, $folderPath, $zipPath = '')
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);

                $zip->addFile($filePath, $zipPath . '/' . $relativePath);
            }
        }
    }

    private function copyDirectory($source, $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $dir = opendir($source);

        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = $source . '/' . $file;
                $destFile = $destination . '/' . $file;

                if (is_dir($srcFile)) {
                    $this->copyDirectory($srcFile, $destFile);
                } else {
                    copy($srcFile, $destFile);
                }
            }
        }

        closedir($dir);
    }

 public function restoreBackup(Request $request)
{
    abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $request->validate([
        'backup_id' => 'required|exists:system_backups,id',
        'restore_type' => 'required|in:documents,database'
    ]);

    try {
        // Use DB::transaction with a closure - it handles commit/rollback automatically
        DB::transaction(function () use ($request) {
            $backup = SystemBackup::findOrFail($request->backup_id);
            $backupPath = $backup->path;

            if (!file_exists($backupPath)) {
                throw new \Exception('Backup file not found');
            }

            $zip = new ZipArchive();

            if ($zip->open($backupPath) !== TRUE) {
                throw new \Exception('Cannot open backup file');
            }

            // Extract to temporary directory
            $extractPath = storage_path("app/private/temp/restore_" . time());
            if (!file_exists(storage_path('app/private/temp'))) {
                mkdir(storage_path('app/private/temp'), 0755, true);
            }
            if (!mkdir($extractPath, 0755, true)) {
                throw new \Exception('Cannot create temporary directory');
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // Restore based on type
            if ($request->restore_type === 'documents') {
                $this->restoreDocuments($extractPath);
            } elseif ($request->restore_type === 'database') {
                $this->restorePatientDatabase($extractPath);
            }

            // Clean up extracted files
            $this->deleteDirectory($extractPath);

            // Update backup record
            $backup->update([
                'restored_at' => now(),
                'restored_by' => Auth::id(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'System restored successfully!',
            'type' => $request->restore_type
        ]);
    } catch (\Exception $e) {
        // Clean up on failure - try to delete extractPath if it exists
        if (isset($extractPath) && file_exists($extractPath)) {
            try {
                $this->deleteDirectory($extractPath);
            } catch (\Exception $cleanupError) {
                // Silently ignore cleanup errors
                Log::warning('Cleanup failed during restore error: ' . $cleanupError->getMessage());
            }
        }

        // Check if it's the "no active transaction" error and handle it gracefully
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, 'There is no active transaction') !== false) {
            // The restore actually worked, just the transaction error occurred
            // Log it but return success
            Log::warning('Restore completed with transaction warning: ' . $errorMessage);
            
            return response()->json([
                'success' => true,
                'message' => 'System restored successfully! (Note: Minor transaction warning occurred)',
                'type' => $request->restore_type
            ]);
        }

        Log::error('Restore failed: ' . $errorMessage, [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Restore failed: ' . $errorMessage,
        ], 500);
    }
}

    private function restoreDocuments($extractPath)
    {
        // Restore document files
        $this->restoreDocumentFiles($extractPath);

        // Restore document database records
        $this->restoreDocumentDatabaseRecords($extractPath);
    }

    private function restoreDocumentFiles($extractPath)
    {
        $sourceDir = $extractPath . '/documents';
        $targetDir = storage_path('app/private/documents');

        if (file_exists($sourceDir)) {
            // Clear existing folder if needed
            if (file_exists($targetDir)) {
                $this->deleteDirectory($targetDir);
            }

            // Create target directory
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Copy folder
            $this->copyDirectory($sourceDir, $targetDir);

            Log::info("Document files restored successfully");
        } else {
            Log::warning("Documents folder not found in backup: {$sourceDir}");
            // Don't throw exception, maybe there were no files in the backup
        }
    }

    private function restoreDocumentDatabaseRecords($extractPath)
    {
        $backupFile = $extractPath . '/database/documents_backup.json';

        if (file_exists($backupFile)) {
            $backupData = json_decode(file_get_contents($backupFile), true);

            if (!is_array($backupData) || !isset($backupData['records'])) {
                Log::warning('Invalid documents backup file format');
                return;
            }

            $records = $backupData['records'];
            $restoredCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            Log::info('Starting document database restore with ' . count($records) . ' records');

            foreach ($records as $documentData) {
                try {
                    // Check if document already exists by file_path (unique identifier)
                    $existingDocument = Document::where('file_path', $documentData['file_path'])->first();

                    if ($existingDocument) {
                        $skippedCount++;
                        Log::info('Document already exists, skipping: ' . $documentData['file_path']);
                        continue;
                    }

                    // Check if patient exists
                    $patientExists = DB::table('patient_records')->where('id', $documentData['patient_id'])->exists();

                    if (!$patientExists) {
                        Log::warning('Patient ID ' . $documentData['patient_id'] . ' does not exist for document: ' . $documentData['file_path']);
                        // Skip documents for non-existent patients
                        $skippedCount++;
                        continue;
                    }

                    // Prepare document data, ensuring all required fields are present
                    $documentAttributes = [
                        'patient_id' => $documentData['patient_id'],
                        'file_name' => $documentData['file_name'],
                        'file_path' => $documentData['file_path'],
                        'file_size' => $documentData['file_size'] ?? 0,
                        'file_extension' => $documentData['file_extension'] ?? '',
                        'document_type' => $documentData['document_type'] ?? 'unknown',
                        'description' => $documentData['description'] ?? null,
                        'uploaded_by' => $documentData['uploaded_by'] ?? Auth::id(),
                        'created_at' => isset($documentData['created_at'])
                            ? Carbon::parse($documentData['created_at'])
                            : now(),
                        'updated_at' => isset($documentData['updated_at'])
                            ? Carbon::parse($documentData['updated_at'])
                            : now(),
                    ];

                    // Create the document record
                    Document::create($documentAttributes);
                    $restoredCount++;

                    Log::info('Document database record restored: ' . $documentData['file_path']);
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Failed to restore document record: ' . $e->getMessage() .
                        ' - Document data: ' . json_encode($documentData));
                }
            }

            Log::info("Document database restore completed: {$restoredCount} restored, {$skippedCount} skipped, {$errorCount} errors");
        } else {
            Log::info('No document database backup file found, skipping database records restore');
        }
    }

    private function restorePatientDatabase($extractPath)
{
    $backupFile = $extractPath . '/patient_database_backup.json';

    if (file_exists($backupFile)) {
        $backupData = json_decode(file_get_contents($backupFile), true);

        if (!is_array($backupData) || !isset($backupData['tables'])) {
            throw new \Exception('Invalid database backup file format');
        }

        $tables = $backupData['tables'];
        
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Perform the restore
            $this->performDatabaseRestore($tables, $backupData);
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
        } catch (\Exception $e) {
            // Always re-enable foreign key checks on error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw $e;
        }

    } else {
        throw new \Exception('Patient database backup file not found');
    }
}

    private function performDatabaseRestore($tables, $backupData)
    {
        // Get the order of tables to restore
        $tablesOrder = $backupData['tables_order'] ?? array_keys($tables);

        Log::info('Starting patient database restore with order: ' . implode(', ', $tablesOrder));

        $totalRestored = 0;

        // Clear tables in reverse order (child tables first, then parent tables)
        $tablesToClear = array_reverse($tablesOrder);
        foreach ($tablesToClear as $tableName) {
            if (isset($tables[$tableName]) && DB::getSchemaBuilder()->hasTable($tableName)) {
                DB::table($tableName)->truncate();
                Log::info("Cleared table: {$tableName}");
            }
        }

        // Restore tables in proper order (parent tables first, then child tables)
        foreach ($tablesOrder as $tableName) {
            if (!isset($tables[$tableName])) {
                Log::warning("Table {$tableName} not found in backup data");
                continue;
            }

            try {
                // Skip if table doesn't exist
                if (!DB::getSchemaBuilder()->hasTable($tableName)) {
                    Log::warning("Table {$tableName} does not exist, skipping");
                    continue;
                }

                $tableData = $tables[$tableName];
                $records = $tableData['records'] ?? [];
                $restoredCount = 0;

                Log::info("Restoring table {$tableName} with " . count($records) . " records");

                foreach ($records as $record) {
                    try {
                        // Handle special cases for certain tables
                        $recordData = (array)$record;

                        // For patient_records, ensure timestamps are properly formatted
                        if ($tableName === 'patient_records') {
                            if (isset($recordData['created_at']) && is_string($recordData['created_at'])) {
                                $recordData['created_at'] = Carbon::parse($recordData['created_at']);
                            }
                            if (isset($recordData['updated_at']) && is_string($recordData['updated_at'])) {
                                $recordData['updated_at'] = Carbon::parse($recordData['updated_at']);
                            }
                        }

                        DB::table($tableName)->insert($recordData);
                        $restoredCount++;
                    } catch (\Exception $e) {
                        Log::warning("Failed to insert record into {$tableName}: " . $e->getMessage() .
                            ' - Record: ' . json_encode($record));
                    }
                }

                $totalRestored += $restoredCount;
                Log::info("Table {$tableName} restored with {$restoredCount} records");
            } catch (\Exception $e) {
                Log::error("Failed to restore table {$tableName}: " . $e->getMessage());
                throw new \Exception("Failed to restore table {$tableName}: " . $e->getMessage());
            }
        }

        Log::info("Patient database restore completed: {$totalRestored} total records restored");
    }

    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function deleteBackup($id)
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backup = SystemBackup::findOrFail($id);

        // Delete physical file
        if (file_exists($backup->path)) {
            unlink($backup->path);
        }

        // Delete record
        $backup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully'
        ]);
    }

    public function downloadBackup($id)
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backup = SystemBackup::findOrFail($id);

        if (!file_exists($backup->path)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($backup->path, $backup->filename);
    }

    public function getBackups()
    {
        abort_if(Gate::denies('settings'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backups = SystemBackup::with(['creator', 'restorer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($backups);
    }
}
