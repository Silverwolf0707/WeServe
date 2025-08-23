@extends('layouts.admin')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="m-0">Patient Record Details</h5>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-primary">Patient Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Patient Name:</th>
                            <td>{{ $patient->patient_name }}</td>
                        </tr>
                        <tr>
                            <th>Age:</th>
                            <td>{{ $patient->age }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $patient->address }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number:</th>
                            <td>{{ $patient->contact_number }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">Case Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Control Number:</th>
                            <td>{{ $patient->control_number }}</td>
                        </tr>
                        <tr>
                            <th>Date Processed:</th>
                            <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Claimant Name:</th>
                            <td>{{ $patient->claimant_name }}</td>
                        </tr>
                        <tr>
                            <th>Diagnosis:</th>
                            <td>{{ $patient->diagnosis }}</td>
                        </tr>
                        <tr>
                            <th>Case Type:</th>
                            <td>{{ $patient->case_type }}</td>
                        </tr>
                        <tr>
                            <th>Case Category:</th>
                            <td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patient->case_category] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Case Worker:</th>
                            <td>{{ $patient->case_worker }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Uploaded Documents --}}
            <div class="mb-4">
                <h6 class="text-primary">Uploaded Documents</h6>
                @if($patient->documents->count())
                    <div class="row">
                        @foreach($patient->documents as $doc)
                            <div class="col-md-3 mb-3 text-center position-relative">
                                <div class="card h-100 shadow-sm">
                                    @php
                                        $extension = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                        {{-- Image Preview --}}
                                        <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->file_name }}"
                                            class="card-img-top preview-image" data-bs-toggle="modal"
                                            data-bs-target="#imagePreviewModal" data-image="{{ asset('storage/' . $doc->file_path) }}"
                                            style="height: 150px; object-fit: cover; cursor: pointer;">
                                    @elseif($extension === 'pdf')
    {{-- PDF Preview (icon, open in new tab) --}}
    <a href="{{ str_replace('storage/storage', 'storage', asset('storage/' . $doc->file_path)) }}" target="_blank">
        <div class="d-flex justify-content-center align-items-center"
             style="height: 150px; background: #f8f9fa; cursor: pointer;">
            <i class="fas fa-file-pdf fa-3x text-danger"></i>
        </div>
    </a>
@endif



                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">{{ $doc->file_name }}</small>
                                    </div>

                                    <form action="{{ route('admin.document-management.destroy', $doc->id) }}" method="POST"
                                        class="position-absolute" style="top: 5px; right: 10px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this file?')" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No documents uploaded.</p>
                @endif
            </div>

            {{-- Upload & Back Buttons --}}
            <div class="d-flex gap-2">
                @can('documents_management')
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-1"></i> Upload Document
                    </button>
                @endcan
                <a href="{{ route('admin.document-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('admin.process-tracking.show', $patient->id) }}" class="btn btn-secondary">
                    <i class="fas fa-history me-1"></i> View Process Tracking
                </a>
                <a href="{{ route('admin.patient-records.show', $patient->id) }}" class="btn btn-secondary">
                    <i class="fas fa-file-medical me-1"></i> View Record
                </a>
            </div>
        </div>
    </div>

    {{-- Upload Document Modal --}}
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.document-management.store') }}" enctype="multipart/form-data"
                class="modal-content shadow">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="uploadDocumentModalLabel">
                        <i class="fas fa-upload mr-2"></i> Upload Document
                    </h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="document_type">Document Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="document_type" id="document_type"
                            placeholder="e.g. Medical Certificate" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (optional)</label>
                        <textarea class="form-control" name="description" id="description" rows="2"
                            placeholder="Add any remarks or notes..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="files">Select Files <span class="text-danger">*</span></label>
                        <input type="file" name="files[]" id="files" class="form-control-file" accept="image/*,.pdf"
                            multiple required>
                        <small class="form-text text-muted">Allowed formats: JPG, PNG, PDF. You can select multiple
                            files.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Upload
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
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