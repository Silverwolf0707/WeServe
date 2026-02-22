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
        $dateFrom   = $request->get('date_from', '');

        $query = PatientRecord::query();

        // Text search
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('control_number', 'like', "%{$searchTerm}%")
                  ->orWhere('patient_name',  'like', "%{$searchTerm}%")
                  ->orWhere('claimant_name', 'like', "%{$searchTerm}%")
                  ->orWhere('diagnosis',     'like', "%{$searchTerm}%")
                  ->orWhere('address',       'like', "%{$searchTerm}%");
            });
        }

        // Date filter — exact match on date_processed date
        if ($dateFrom) {
            $query->whereDate('date_processed', $dateFrom);
        }

        $patients = $query->latest('date_processed')->paginate(100)->withQueryString();

        return view('admin.documentManagement.index', compact('patients', 'searchTerm', 'dateFrom'));
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
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '_' . time() . '.' . $extension;

                $path = $file->storeAs(
                    'documents/' . $request->patient_id,
                    $filename,
                    'private'
                );

                Document::create([
                    'patient_id'     => $request->patient_id,
                    'file_name'      => $originalName,
                    'file_path'      => $path,
                    'file_size'      => $file->getSize(),
                    'file_extension' => $extension,
                    'document_type'  => $request->document_type,
                    'description'    => $request->description,
                    'uploaded_by'    => auth('web')->user()->id,
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

    public function view($id)
    {
        $document = Document::findOrFail($id);

        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        $path     = Storage::disk('private')->path($document->file_path);
        $mimeType = mime_content_type($path) ?: 'application/octet-stream';

        if (Str::startsWith($mimeType, 'image/') || $mimeType === 'application/pdf') {
            return response()->file($path, [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
            ]);
        }

        return response()->download($path, $document->file_name);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doc = Document::findOrFail($id);
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