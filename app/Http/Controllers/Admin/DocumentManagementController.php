<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDocumentRequest;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\PatientRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DocumentManagementController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $searchTerm = $request->get('search', '');
        
        // Start with base query
        $query = PatientRecord::query();
        
        // Apply search if term exists
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('control_number', 'like', "%{$searchTerm}%")
                  ->orWhere('patient_name', 'like', "%{$searchTerm}%")
                  ->orWhere('claimant_name', 'like', "%{$searchTerm}%")
                  ->orWhere('diagnosis', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get paginated results with search applied
        $patients = $query->latest()->paginate(100)->withQueryString();

        return view('admin.documentManagement.index', compact('patients', 'searchTerm'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'patient_id' => 'required|exists:patient_records,id',
            'files.*' => 'required|file|max:20480',
            'document_type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate unique filename for security
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '_' . time() . '.' . $extension;
                
                // Store in private storage (not accessible via URL)
                $path = $file->storeAs(
                    'documents/' . $request->patient_id,
                    $filename,
                    'private' // This ensures it's stored in private disk
                );

                Document::create([
                    'patient_id' => $request->patient_id,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'document_type' => $request->document_type,
                    'description' => $request->description,
                    'uploaded_by' => auth('web')->user()->id,
                ]);
            }
        }

        return redirect()
            ->route('admin.document-management.show', $request->patient_id)
            ->with('status', 'Documents uploaded successfully.');
    }

    public function show($id)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient = PatientRecord::with('documents')->findOrFail($id);
        return view('admin.documentManagement.show', compact('patient'));
    }

    /**
     * Download/View a document with permission check
     */
    public function view($id)
    {
        $document = Document::findOrFail($id);
        
        // Check if user has permission to view documents
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if file exists in private storage
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        // Get file path and mime type
        $path = Storage::disk('private')->path($document->file_path);
        $mimeType = mime_content_type($path) ?: 'application/octet-stream';

        // For images and PDFs, display inline if possible
        if (Str::startsWith($mimeType, 'image/') || $mimeType === 'application/pdf') {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
            ]);
        }

        // For other files, force download
        return response()->download($path, $document->file_name);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doc = Document::findOrFail($id);
        
        // Delete from private storage
        Storage::disk('private')->delete($doc->file_path);
        $doc->delete();

        return back()->with('status', 'Document deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }

        $documents = Document::whereIn('patient_id', $ids)->get();

        foreach ($documents as $doc) {
            Storage::disk('private')->delete($doc->file_path);
            $doc->delete();
        }

        return response(null, 204);
    }
}