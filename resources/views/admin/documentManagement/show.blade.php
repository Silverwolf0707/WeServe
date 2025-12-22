@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <!-- Modernized Header (same design as index patient) -->
        <div class="card-header custom-header d-flex align-items-center">
            <h4 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-users me-2"></i> Patient Record Details
            </h4>
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

            <div class="mb-4">
                <h6 class="text-primary">Uploaded Documents</h6>
                <!-- In the Blade view, replace the document display section -->
@if ($patient->documents->count())
    <div class="row">
        @foreach ($patient->documents as $doc)
            <div class="col-md-3 mb-3 text-center position-relative">
                <div class="card h-100 shadow-sm" data-bs-toggle="tooltip" data-bs-html="true" title="
                    <strong>Type:</strong> {{ $doc->document_type ?? 'No Type' }}<br>
                    <strong>File:</strong> {{ $doc->file_name }}<br>
                    <strong>Size:</strong> {{ number_format($doc->file_size / 1024, 2) }} KB<br>
                    <strong>Description:</strong> {{ $doc->description ?? 'No description' }}
                ">

                    @php 
                        $extension = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)); 
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                        $isPDF = $extension === 'pdf';
                    @endphp

                    @if ($isImage)
                        <a href="{{ route('admin.document-management.view', $doc->id) }}" target="_blank">
                            <div class="document-preview">
                                <i class="fas fa-eye text-primary"></i>
                                <small>Click to view</small>
                            </div>
                        </a>
                    @elseif($isPDF)
                        <a href="{{ route('admin.document-management.view', $doc->id) }}" target="_blank">
                            <div class="document-preview">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                <small>View PDF</small>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('admin.document-management.view', $doc->id) }}" target="_blank">
                            <div class="document-preview">
                                <i class="fas fa-file fa-3x text-secondary"></i>
                                <small>Download File</small>
                            </div>
                        </a>
                    @endif

                    <div class="card-body p-2 text-center">
                        <small class="fw-bold text-primary d-block text-truncate">
                            {{ $doc->document_type ?? 'Document' }}
                        </small>
                        <small class="text-muted d-block text-truncate" title="{{ $doc->file_name }}">
                            {{ Str::limit($doc->file_name, 20) }}
                        </small>
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
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> No documents uploaded for this patient.
    </div>
@endif
            </div>

            {{-- Upload & Navigation Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                <!-- Left Side: Upload -->
                <div>
                    @can('documents_management')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i class="fas fa-upload me-1"></i> Upload Document
                        </button>
                    @endcan
                    <a href="{{ route('admin.document-management.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <!-- Right Side: Navigation -->
                <div class="d-flex gap-2 mt-2 mt-md-0">

                    <a href="{{ route('admin.process-tracking.show', $patient->id) }}" class="btn btn-info">
                        <i class="fas fa-history me-1"></i> View Process Tracking
                    </a>
                    <a href="{{ route('admin.patient-records.show', $patient->id) }}" class="btn btn-primary">
                        <i class="fas fa-file-medical me-1"></i> View Record
                    </a>
                </div>
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
                        <i class="fas fa-upload me-2"></i> Upload Document
                    </h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="document_type">Document Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="document_type" id="document_type"
                            placeholder="e.g. Medical Certificate" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description (optional)</label>
                        <textarea class="form-control" name="description" id="description" rows="2"
                            placeholder="Add any remarks or notes..."></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="files">Select Files <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-paperclip"></i>
                            </span>
                            <input type="file" name="files[]" id="files" class="form-control" accept="image/*,.pdf" multiple
                                required>
                        </div>
                        <small class="form-text text-muted">Allowed formats: JPG, PNG, PDF and more. Max 20mb per
                            file.</small>
                    </div>

                    <div id="filePreviewContainer" class="mt-3 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"
                        onclick="this.disabled = true; this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Uploading...'; this.form.submit();">
                        <i class="fas fa-check-circle me-1"></i> Upload
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle me-1"></i> Cancel
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

    <style>
        #filePreviewContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .file-preview {
            position: relative;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-preview .remove-btn {
            position: absolute;
            top: 3px;
            right: 3px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-preview .file-icon {
            font-size: 40px;
            color: #6c757d;
        }
            .document-preview {
        height: 150px;
        background: #f8f9fa;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
        border-bottom: 1px solid #dee2e6;
    }
    
    .document-preview:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .document-preview i {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    .document-preview small {
        color: #6c757d;
        font-size: 12px;
    }
    </style>

@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.preview-image').forEach(image => {
            image.addEventListener('click', function () {
                const imageUrl = this.getAttribute('data-image');
                document.getElementById('modalImage').setAttribute('src', imageUrl);
            });
        });

        const fileInput = document.getElementById('files');
        const previewContainer = document.getElementById('filePreviewContainer');
        let selectedFiles = [];

        fileInput.addEventListener('change', function () {
            const files = Array.from(this.files);
            selectedFiles = selectedFiles.concat(files);
            displayPreviews();
        });

        function displayPreviews() {
            previewContainer.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const previewDiv = document.createElement('div');
                previewDiv.classList.add('file-preview');

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('remove-btn');
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', () => removeFile(index));

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    previewDiv.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.classList.add('fas', 'fa-file-pdf', 'file-icon');
                    previewDiv.appendChild(icon);
                }

                previewDiv.appendChild(removeBtn);
                previewContainer.appendChild(previewDiv);
            });
            updateFileInput();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            displayPreviews();
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

    </script>
@endsection