<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\PatientRecord;
use Illuminate\Support\Facades\Storage;

class DocumentManagementController extends Controller
{
 public function index()
{
    $documents = Document::with('patient')->latest()->get();
    return view('admin.documentManagement.index', compact('documents'));
}

public function create()
{
    $patients = PatientRecord::all();
    return view('admin.documentManagement.create', compact('patients'));
}

public function store(Request $request)
{
    $request->validate([
        'patient_id' => 'required|exists:patient_records,id',
        'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx',
        'document_type' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
    ]);

    $file = $request->file('file');
    $path = $file->store('documents', 'public');

    Document::create([
        'patient_id' => $request->patient_id,
        'file_name' => $file->getClientOriginalName(),
        'file_path' => $path,
        'document_type' => $request->document_type,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.document-management.index')->with('status', 'Document uploaded.');
}
}
