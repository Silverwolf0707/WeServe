@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Patient Record Details
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th>Control Number</th><td>{{ $patient->control_number }}</td></tr>
                <tr><th>Date Processed</th><td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y') }}</td></tr>
                <tr><th>Patient Name</th><td>{{ $patient->patient_name }}</td></tr>
                <tr><th>Claimant Name</th><td>{{ $patient->claimant_name }}</td></tr>
                <tr><th>Diagnosis</th><td>{{ $patient->diagnosis }}</td></tr>
                <tr><th>Case Type</th><td>{{ $patient->case_type }}</td></tr>
                <tr><th>Case Category</th><td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patient->case_category] ?? '' }}</td></tr>
                <tr><th>Age</th><td>{{ $patient->age }}</td></tr>
                <tr><th>Address</th><td>{{ $patient->address }}</td></tr>
                <tr><th>Contact Number</th><td>{{ $patient->contact_number }}</td></tr>
                <tr><th>Case Worker</th><td>{{ $patient->case_worker }}</td></tr>
            </tbody>
        </table>

        {{-- Uploaded Images Preview --}}
        <div class="mt-4">
            <h5>Uploaded Documents</h5>
            @if($patient->documents->count())
                <div class="row">
                    @foreach($patient->documents as $doc)
                        <div class="col-md-3 mb-3 text-center position-relative">
                            <img src="{{ asset('storage/' . $doc->file_path) }}" 
                                 alt="{{ $doc->file_name }}" 
                                 class="img-thumbnail preview-image" 
                                 data-toggle="modal" 
                                 data-target="#imagePreviewModal" 
                                 data-image="{{ asset('storage/' . $doc->file_path) }}"
                                 style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;">

                            <small class="d-block mt-2 text-truncate">{{ $doc->file_name }}</small>

                            {{-- Trash icon --}}
                            <form action="{{ route('admin.document-management.destroy', $doc->id) }}" method="POST" class="position-absolute" style="top: 5px; right: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No documents uploaded.</p>
            @endif
        </div>

        {{-- Upload Document Modal Trigger --}}
        @can('documents_management')
        <button class="btn btn-success mt-3" data-toggle="modal" data-target="#uploadDocumentModal">
            Upload Document
        </button>
        @endcan

        <a href="{{ route('admin.document-management.index') }}" class="btn btn-default mt-3">
            Back to List
        </a>
    </div>
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.document-management.store') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Documents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="document_type" class="form-label">Document Type</label>
                    <input type="text" class="form-control" name="document_type" id="document_type">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="files" class="form-label">Files</label>
                    <input class="form-control" type="file" name="files[]" id="files" accept="image/*" multiple>
                    <small class="text-muted">You can select multiple images</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- Image Preview Modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid rounded" style="width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    document.querySelectorAll('.preview-image').forEach(image => {
        image.addEventListener('click', function () {
            const imageUrl = this.getAttribute('data-image');
            document.getElementById('modalImage').setAttribute('src', imageUrl);
        });
    });
</script>
@endsection
