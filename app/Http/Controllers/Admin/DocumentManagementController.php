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

class DocumentManagementController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = PatientRecord::latest()->get();
        return view('admin.documentManagement.index', compact('patients'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'patient_id' => 'required|exists:patient_records,id',
            'files.*' => 'required|file|mimes:jpg,jpeg,png|max:2048', // limit to 2MB per image
            'document_type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('documents', 'public');

                Document::create([
                    'patient_id' => $request->patient_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'document_type' => $request->document_type,
                    'description' => $request->description,
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

    public function destroy($id)
    {
        abort_if(Gate::denies('documents_management'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doc = Document::findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
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
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
    }

    return response(null, 204);
}

}
