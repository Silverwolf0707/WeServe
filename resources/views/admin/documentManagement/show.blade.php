@extends('layouts.admin')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest:      #064e3b;
            --pr-forest-deep: #052e22;
            --pr-forest-mid:  #065f46;
            --pr-lime:        #74ff70;
            --pr-lime-dim:    #52e84e;
            --pr-lime-ghost:  rgba(116,255,112,.10);
            --pr-lime-border: rgba(116,255,112,.30);
            --pr-surface:     #ffffff;
            --pr-surface2:    #f0fdf4;
            --pr-muted:       #ecfdf5;
            --pr-border:      #d1fae5;
            --pr-border-dark: #a7f3d0;
            --pr-text:        #052e22;
            --pr-sub:         #3d7a62;
            --pr-danger:      #ef4444;
            --pr-radius:      12px;
            --pr-shadow:      0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg:   0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
            --pr-shadow-lime: 0 2px 12px rgba(116,255,112,.25);
        }

        .pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

        /* ── hero ── */
        .pr-hero {
            background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
            border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 16px;
            position: relative; overflow: hidden; box-shadow: var(--pr-shadow-lg);
        }
        .pr-hero::before {
            content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius);
            background:
                radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%),
                radial-gradient(ellipse 180px 100px at 5%  80%, rgba(116,255,112,.07) 0%, transparent 70%),
                radial-gradient(ellipse 250px 120px at 50% -20%, rgba(255,255,255,.04) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
        .pr-hero::after {
            content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px;
            background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
            border-radius: 2px; opacity: .55;
        }
        .pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
        .pr-hero-left  { display: flex; align-items: center; gap: 16px; }
        .pr-hero-icon  {
            width: 46px; height: 46px; background: rgba(116,255,112,.12);
            border: 1px solid rgba(116,255,112,.30); border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: var(--pr-lime); backdrop-filter: blur(4px); flex-shrink: 0;
        }
        .pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
        .pr-hero-meta  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-badge      { display: inline-flex; align-items: center; gap: 4px; border-radius: 20px; font-size: .72rem; font-weight: 600; padding: 2px 10px; letter-spacing: .03em; line-height: 1.6; }
        .pr-badge-count { background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); color: var(--pr-lime); }

        /* ── hero action buttons ── */
        .pr-hero-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 8px; padding: 7px 14px; font-size: .8rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .18s;
            white-space: nowrap; text-decoration: none; border: none;
        }
        .pr-hero-btn-ghost {
            background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
            border: 1px solid rgba(255,255,255,.18);
        }
        .pr-hero-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: rgba(116,255,112,.35); color: var(--pr-lime); }
        .pr-hero-btn-primary {
            background: var(--pr-lime); color: var(--pr-forest);
            border: 1px solid var(--pr-lime); box-shadow: var(--pr-shadow-lime);
        }
        .pr-hero-btn-primary:hover { background: var(--pr-lime-dim); color: var(--pr-forest); }

        /* ── info cards ── */
        .pr-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        @media (max-width: 768px) { .pr-info-grid { grid-template-columns: 1fr; } }

        .pr-info-card {
            background: var(--pr-surface); border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
        }
        .pr-info-card-header {
            background: var(--pr-forest); padding: 10px 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .pr-info-card-header span { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.85); }
        .pr-info-card-header i   { color: var(--pr-lime); font-size: .8rem; }
        .pr-info-card-body { padding: 14px 16px; }

        .pr-info-row { display: flex; gap: 8px; padding: 7px 0; border-bottom: 1px solid var(--pr-border); }
        .pr-info-row:last-child { border-bottom: none; }
        .pr-info-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--pr-sub); flex: 0 0 130px; padding-top: 1px; }
        .pr-info-value { font-size: .84rem; font-weight: 500; color: var(--pr-text); flex: 1; }

        /* ── documents section ── */
        .pr-docs-card {
            background: var(--pr-surface); border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
            margin-bottom: 16px;
        }
        .pr-docs-header {
            background: var(--pr-forest); padding: 10px 16px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .pr-docs-header-left { display: flex; align-items: center; gap: 8px; }
        .pr-docs-header-left span { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.85); }
        .pr-docs-header-left i   { color: var(--pr-lime); font-size: .8rem; }
        .pr-docs-body { padding: 16px; }

        /* ── document tiles ── */
        .pr-doc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }

        .pr-doc-tile {
            border: 1.5px solid var(--pr-border-dark); border-radius: 10px;
            overflow: hidden; background: var(--pr-surface);
            transition: box-shadow .18s, transform .18s; position: relative;
        }
        .pr-doc-tile:hover { box-shadow: var(--pr-shadow); transform: translateY(-2px); }

        .pr-doc-preview {
            height: 110px; background: var(--pr-surface2);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 6px; cursor: pointer; text-decoration: none;
            border-bottom: 1px solid var(--pr-border); transition: background .15s;
        }
        .pr-doc-preview:hover { background: var(--pr-muted); }
        .pr-doc-preview i     { font-size: 2.2rem; }
        .pr-doc-preview small { font-size: .68rem; color: var(--pr-sub); font-weight: 600; }

        .pr-doc-info { padding: 8px 10px; }
        .pr-doc-type { font-size: .72rem; font-weight: 700; color: var(--pr-forest); text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pr-doc-name { font-size: .7rem; color: var(--pr-sub); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }

        .pr-doc-delete {
            position: absolute; top: 6px; right: 6px;
            width: 24px; height: 24px; border-radius: 6px;
            background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25);
            color: var(--pr-danger); font-size: .65rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .15s; padding: 0;
        }
        .pr-doc-delete:hover { background: var(--pr-danger); color: #fff; border-color: var(--pr-danger); }

        /* ── empty state ── */
        .pr-empty {
            text-align: center; padding: 36px 20px; color: var(--pr-sub);
        }
        .pr-empty i    { font-size: 2.5rem; color: var(--pr-border-dark); margin-bottom: 10px; display: block; }
        .pr-empty span { font-size: .84rem; font-weight: 500; }

        /* ── bottom nav bar ── */
        .pr-nav-bar {
            background: var(--pr-surface2); border: 1px solid var(--pr-border);
            border-radius: var(--pr-radius); padding: 12px 18px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; box-shadow: var(--pr-shadow);
        }
        .pr-nav-bar-left  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-nav-bar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        .pr-nav-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 8px; padding: 7px 14px; font-size: .8rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .18s;
            white-space: nowrap; text-decoration: none;
        }
        .pr-nav-btn-back {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-back:hover { background: var(--pr-muted); color: var(--pr-forest); border-color: var(--pr-forest); }
        .pr-nav-btn-upload {
            background: var(--pr-forest); color: var(--pr-lime);
            border: 1.5px solid var(--pr-forest); box-shadow: 0 2px 8px rgba(6,78,59,.25);
        }
        .pr-nav-btn-upload:hover { background: var(--pr-forest-mid); color: var(--pr-lime); }
        .pr-nav-btn-info {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-info:hover { background: var(--pr-lime-ghost); border-color: var(--pr-forest); color: var(--pr-forest); }

        /* ── modal ── */
        .pr-modal-header {
            background: linear-gradient(135deg, #052e22 0%, #064e3b 100%);
            border-bottom: none; padding: 16px 20px;
        }
        .pr-modal-header .modal-title { color: #fff; font-size: .95rem; font-weight: 700; font-family: 'DM Sans', sans-serif; }
        .pr-modal-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--pr-sub); display: block; margin-bottom: 5px; }
        .pr-modal-input {
            border: 1.5px solid var(--pr-border-dark); border-radius: 8px;
            font-size: .82rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text);
            transition: border-color .2s, box-shadow .2s; width: 100%; padding: 7px 10px;
        }
        .pr-modal-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
        .pr-modal-footer { border-top: 1px solid var(--pr-border); background: var(--pr-surface2); padding: 14px 20px; gap: 8px; }

        /* file preview thumbnails */
        #filePreviewContainer { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .file-preview {
            position: relative; width: 90px; height: 90px;
            border: 1.5px solid var(--pr-border-dark); border-radius: 8px;
            overflow: hidden; background: var(--pr-surface2);
            display: flex; justify-content: center; align-items: center;
        }
        .file-preview img { width: 100%; height: 100%; object-fit: cover; }
        .file-preview .remove-btn {
            position: absolute; top: 3px; right: 3px;
            background: rgba(0,0,0,.55); color: #fff; border: none;
            border-radius: 50%; width: 20px; height: 20px; font-size: 13px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .file-preview .file-icon { font-size: 2rem; color: var(--pr-sub); }

        @media (max-width: 768px) {
            .pr-hero-inner { flex-direction: column; align-items: flex-start; }
            .pr-hero { padding: 16px 18px; }
            .pr-nav-bar { flex-direction: column; align-items: flex-start; }
        }
    </style>

    <div class="pr-page">

        {{-- ══ HERO ══ --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-left">
                    <div class="pr-hero-icon"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="pr-hero-title">{{ $patient->control_number }}</div>
                        <div class="pr-hero-meta">
                            <span class="pr-badge pr-badge-count">
                                {{ $patient->documents->count() }} {{ $patient->documents->count() === 1 ? 'document' : 'documents' }}
                            </span>
                            <span class="pr-badge" style="background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.75);">
                                <i class="fas fa-user" style="font-size:.6rem;"></i>
                                {{ $patient->claimant_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @can('documents_management')
                        <button class="pr-hero-btn pr-hero-btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i class="fas fa-upload" style="font-size:.72rem;"></i> Upload Document
                        </button>
                    @endcan
                    <a href="{{ route('admin.document-management.index') }}" class="pr-hero-btn pr-hero-btn-ghost">
                        <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ INFO CARDS ══ --}}
        <div class="pr-info-grid">
            {{-- Patient Info --}}
            <div class="pr-info-card">
                <div class="pr-info-card-header">
                    <i class="fas fa-user-injured"></i>
                    <span>Patient Info</span>
                </div>
                <div class="pr-info-card-body">
                    <div class="pr-info-row">
                        <div class="pr-info-label">Patient Name</div>
                        <div class="pr-info-value">{{ $patient->patient_name }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Age</div>
                        <div class="pr-info-value">{{ $patient->age }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Address</div>
                        <div class="pr-info-value">{{ $patient->address }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Contact</div>
                        <div class="pr-info-value">{{ $patient->contact_number }}</div>
                    </div>
                </div>
            </div>

            {{-- Case Info --}}
            <div class="pr-info-card">
                <div class="pr-info-card-header">
                    <i class="fas fa-folder-open"></i>
                    <span>Case Info</span>
                </div>
                <div class="pr-info-card-body">
                    <div class="pr-info-row">
                        <div class="pr-info-label">Control #</div>
                        <div class="pr-info-value" style="font-weight:700;color:var(--pr-forest);">{{ $patient->control_number }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Date Processed</div>
                        <div class="pr-info-value">{{ \Carbon\Carbon::parse($patient->date_processed)->format('M j, Y') }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Claimant</div>
                        <div class="pr-info-value">{{ $patient->claimant_name }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Diagnosis</div>
                        <div class="pr-info-value">{{ $patient->diagnosis }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Case Type</div>
                        <div class="pr-info-value">{{ $patient->case_type }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Case Category</div>
                        <div class="pr-info-value">{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patient->case_category] ?? '' }}</div>
                    </div>
                    <div class="pr-info-row">
                        <div class="pr-info-label">Case Worker</div>
                        <div class="pr-info-value">{{ $patient->case_worker }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ DOCUMENTS ══ --}}
        <div class="pr-docs-card">
            <div class="pr-docs-header">
                <div class="pr-docs-header-left">
                    <i class="fas fa-paperclip"></i>
                    <span>Uploaded Documents</span>
                </div>
                <span class="pr-badge pr-badge-count" style="font-size:.68rem;">
                    {{ $patient->documents->count() }}
                </span>
            </div>
            <div class="pr-docs-body">
                @if($patient->documents->count())
                    <div class="pr-doc-grid">
                        @foreach($patient->documents as $doc)
                            @php
                                $ext     = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                                $isPDF   = $ext === 'pdf';
                                $iconClass = $isImage ? 'fa-image text-info' : ($isPDF ? 'fa-file-pdf text-danger' : 'fa-file text-secondary');
                            @endphp
                            <div class="pr-doc-tile">
                                <a href="{{ route('admin.document-management.view', $doc->id) }}"
                                   target="_blank" class="pr-doc-preview">
                                    <i class="fas {{ $iconClass }}"></i>
                                    <small>{{ $isImage ? 'View Image' : ($isPDF ? 'View PDF' : 'Download') }}</small>
                                </a>
                                <div class="pr-doc-info">
                                    <div class="pr-doc-type" title="{{ $doc->document_type ?? 'Document' }}">
                                        {{ $doc->document_type ?? 'Document' }}
                                    </div>
                                    <div class="pr-doc-name" title="{{ $doc->file_name }}">
                                        {{ Str::limit($doc->file_name, 22) }}
                                    </div>
                                </div>
                                <form action="{{ route('admin.document-management.destroy', $doc->id) }}"
                                      method="POST" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="pr-doc-delete"
                                            onclick="return confirm('Delete this document?')"
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="pr-empty">
                        <i class="fas fa-folder-open"></i>
                        <span>No documents uploaded for this patient.</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══ BOTTOM NAV ══ --}}
        <div class="pr-nav-bar">
            <div class="pr-nav-bar-left">
                @can('documents_management')
                    <button class="pr-nav-btn pr-nav-btn-upload" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload" style="font-size:.72rem;"></i> Upload Document
                    </button>
                @endcan
                <a href="{{ route('admin.document-management.index') }}" class="pr-nav-btn pr-nav-btn-back">
                    <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back to List
                </a>
            </div>
            <div class="pr-nav-bar-right">
                <a href="{{ route('admin.process-tracking.show', $patient->id) }}" class="pr-nav-btn pr-nav-btn-info">
                    <i class="fas fa-history" style="font-size:.72rem;"></i> Process Tracking
                </a>
                <a href="{{ route('admin.patient-records.show', $patient->id) }}" class="pr-nav-btn pr-nav-btn-info">
                    <i class="fas fa-file-medical" style="font-size:.72rem;"></i> View Record
                </a>
            </div>
        </div>

    </div>{{-- /pr-page --}}

    {{-- ══ UPLOAD MODAL ══ --}}
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.document-management.store') }}"
                  enctype="multipart/form-data" class="modal-content"
                  style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="modal-header pr-modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload me-2" style="color:var(--pr-lime);"></i> Upload Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>

                <div class="modal-body" style="padding:20px 22px;">
                    <div class="mb-3">
                        <label class="pr-modal-label">Document Type <span style="color:var(--pr-danger);">*</span></label>
                        <input type="text" class="pr-modal-input" name="document_type"
                               placeholder="e.g. Medical Certificate" required>
                    </div>
                    <div class="mb-3">
                        <label class="pr-modal-label">Description <span style="color:var(--pr-sub);font-weight:400;">(optional)</span></label>
                        <textarea class="pr-modal-input" name="description" rows="2"
                                  placeholder="Add any remarks or notes..." style="resize:vertical;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="pr-modal-label">Select Files <span style="color:var(--pr-danger);">*</span></label>
                        <input type="file" name="files[]" id="files" class="pr-modal-input"
                               accept="image/*,.pdf" multiple required style="padding:6px 10px;cursor:pointer;">
                        <small style="font-size:.72rem;color:var(--pr-sub);margin-top:4px;display:block;">
                            Allowed: JPG, PNG, PDF. Max 20 MB per file.
                        </small>
                    </div>
                    <div id="filePreviewContainer"></div>
                </div>

                <div class="modal-footer pr-modal-footer" style="display:flex;">
                    <button type="button" class="btn"
                            data-bs-dismiss="modal"
                            style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">
                        <i class="fas fa-times-circle me-1"></i> Cancel
                    </button>
                    <button type="submit"
                            onclick="this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin me-1\'></i> Uploading...';this.form.submit();"
                            style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:700;background:var(--pr-forest);color:var(--pr-lime);border:none;padding:8px 18px;cursor:pointer;box-shadow:0 2px 8px rgba(6,78,59,.25);">
                        <i class="fas fa-check-circle me-1"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // File preview
    const fileInput = document.getElementById('files');
    const previewContainer = document.getElementById('filePreviewContainer');
    let selectedFiles = [];

    fileInput.addEventListener('change', function () {
        selectedFiles = selectedFiles.concat(Array.from(this.files));
        renderPreviews();
    });

    function renderPreviews() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const wrap = document.createElement('div');
            wrap.className = 'file-preview';

            const rm = document.createElement('button');
            rm.className = 'remove-btn';
            rm.type = 'button';
            rm.innerHTML = '&times;';
            rm.onclick = () => { selectedFiles.splice(i, 1); renderPreviews(); };

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                wrap.appendChild(img);
            } else {
                const ico = document.createElement('i');
                ico.className = 'fas fa-file-pdf file-icon';
                wrap.appendChild(ico);
            }
            wrap.appendChild(rm);
            previewContainer.appendChild(wrap);
        });

        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    // Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    });
</script>
@endsection