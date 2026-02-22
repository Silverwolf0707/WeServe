<style>
    #profileModal .modal-content {
        font-family: 'DM Sans', sans-serif;
        border-radius: 16px !important;
        overflow: hidden;
        animation: prSlideIn 0.25s ease-out;
    }
    @keyframes prSlideIn {
        from { opacity:0; transform:translateY(-24px); }
        to   { opacity:1; transform:translateY(0); }
    }
    #profileModal .pr-info-row {
        display: flex; align-items: center; gap: 14px;
        padding: 10px 0; border-bottom: 1px solid #d1fae5;
    }
    #profileModal .pr-info-row:last-child { border-bottom: none; }
    #profileModal .pr-info-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .82rem;
    }
    #profileModal .pr-info-label {
        font-size: .67rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #3d7a62; margin-bottom: 1px;
    }
    #profileModal .pr-info-value { font-size: .84rem; font-weight: 600; color: #052e22; }
    #profileModal .pr-badge {
        display: inline-flex; align-items: center; gap: 4px;
        border-radius: 20px; font-size: .7rem; font-weight: 700; padding: 2px 10px;
    }
    #profileModal .pr-badge-success { background:rgba(5,150,105,.12); border:1px solid rgba(5,150,105,.35); color:#065f46; }
    #profileModal .pr-badge-warning { background:rgba(245,158,11,.12); border:1px solid rgba(245,158,11,.35); color:#78350f; }
    #profileModal .pr-badge-danger  { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.35);  color:#7f1d1d; }
    #profileModal .pr-thumb {
        width:56px; height:56px; border-radius:50%; object-fit:cover; cursor:pointer;
        border:3px solid #d1fae5; transition:all .2s;
    }
    #profileModal .pr-thumb:hover   { transform:scale(1.1); border-color:#74ff70; box-shadow:0 4px 12px rgba(6,78,59,.2); }
    #profileModal .pr-thumb.current { border-color:#52e84e; box-shadow:0 0 0 3px rgba(116,255,112,.3); }
    #profileModal .pr-camera-btn {
        position:absolute; bottom:0; right:0;
        width:30px; height:30px; border-radius:50%;
        background:#74ff70; border:2px solid #fff;
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; transition:all .18s; box-shadow:0 2px 8px rgba(6,78,59,.25);
    }
    #profileModal .pr-camera-btn:hover { background:#52e84e; transform:scale(1.1); }
    #profileModal .pr-alert {
        border-radius:9px; padding:10px 14px; font-size:.8rem; font-weight:500;
        border:none; display:flex; align-items:center; gap:8px;
    }
    #profileModal .pr-alert-info    { background:#eff6ff; color:#1e40af; }
    #profileModal .pr-alert-success { background:#f0fdf4; color:#065f46; }
    #profileModal .pr-alert-danger  { background:#fef2f2; color:#7f1d1d; }
    #profileModal .pr-close-btn {
        display:inline-flex; align-items:center; gap:6px;
        background:#f0fdf4; color:#3d7a62; border:1.5px solid #a7f3d0;
        border-radius:9px; padding:8px 22px; font-size:.82rem; font-weight:700;
        font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .18s;
    }
    #profileModal .pr-close-btn:hover { background:#d1fae5; color:#052e22; }
</style>

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">

            {{-- ── HERO ── --}}
            <div style="position:relative;text-align:center;background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%);padding:28px 24px 22px;">
                {{-- lime top line --}}
                <div style="position:absolute;top:0;left:28px;right:28px;height:2px;background:linear-gradient(to right,transparent,#74ff70,transparent);opacity:.55;border-radius:2px;"></div>
                {{-- glow --}}
                <div style="position:absolute;inset:0;border-radius:inherit;background:radial-gradient(ellipse 280px 140px at 90% 50%,rgba(116,255,112,.12) 0%,transparent 65%);pointer-events:none;"></div>

                {{-- Avatar --}}
                <div style="position:relative;width:100px;height:100px;margin:0 auto 14px;z-index:1;">
                    <div id="profilePreview"
                         style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:3px solid rgba(116,255,112,.5);box-shadow:0 0 0 4px rgba(116,255,112,.15),0 6px 24px rgba(0,0,0,.3);background:rgba(116,255,112,.08);display:flex;align-items:center;justify-content:center;">
                        @if(Auth::user()->currentProfileImage)
                            <img src="{{ Auth::user()->currentProfileImage->image_url }}" alt="Profile"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            <i class="fas fa-user" style="font-size:46px;color:rgba(116,255,112,.55);"></i>
                        @endif
                    </div>
                    <label for="profileImageInput" class="pr-camera-btn" title="Upload photo">
                        <i class="fas fa-camera" style="font-size:12px;color:#052e22;"></i>
                    </label>
                    <input type="file" id="profileImageInput" class="d-none" accept="image/*">
                </div>

                {{-- Name + role pill + last login --}}
                <div style="position:relative;z-index:1;">
                    <div style="font-size:1.05rem;font-weight:700;color:#fff;letter-spacing:-.01em;margin-bottom:6px;">
                        {{ Auth::user()->name }}
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);border-radius:20px;padding:2px 12px;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.85);">
                        <i class="fas fa-user-tag" style="font-size:.58rem;color:#74ff70;"></i>
                        {{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}
                    </div>
                    @if(Auth::user()->last_login_at)
                        <div style="margin-top:7px;font-size:.7rem;color:rgba(255,255,255,.45);">
                            <i class="fas fa-clock" style="font-size:.58rem;margin-right:3px;"></i>
                            Last login: {{ Auth::user()->last_login_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── BODY ── --}}
            <div style="padding:18px 22px;background:#fff;">

                {{-- Alerts --}}
                <div id="uploadProgress" class="pr-alert pr-alert-info d-none mb-3">
                    <div class="spinner-border spinner-border-sm" role="status" style="width:13px;height:13px;border-width:2px;flex-shrink:0;"></div>
                    Uploading profile image…
                </div>
                <div id="uploadSuccess" class="pr-alert pr-alert-success d-none mb-3">
                    <i class="fas fa-check-circle" style="flex-shrink:0;"></i><span></span>
                </div>
                <div id="uploadError" class="pr-alert pr-alert-danger d-none mb-3">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i><span></span>
                </div>

                {{-- Info rows --}}
                <div style="margin-bottom:16px;">

                    <div class="pr-info-row">
                        <div class="pr-info-icon" style="background:rgba(14,165,233,.10);color:#0369a1;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="pr-info-label">Email</div>
                            <div class="pr-info-value" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ Auth::user()->email }}
                            </div>
                        </div>
                        @if(Auth::user()->hasVerifiedEmail())
                            <span class="pr-badge pr-badge-success"><i class="fas fa-check-circle" style="font-size:.58rem;"></i> Verified</span>
                        @else
                            <span class="pr-badge pr-badge-warning"><i class="fas fa-exclamation-triangle" style="font-size:.58rem;"></i> Unverified</span>
                        @endif
                    </div>

                    <div class="pr-info-row">
                        <div class="pr-info-icon" style="background:rgba(245,158,11,.10);color:#b45309;">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div>
                            <div class="pr-info-label">Role</div>
                            <div class="pr-info-value">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</div>
                        </div>
                    </div>

                    <div class="pr-info-row">
                        <div class="pr-info-icon" style="background:rgba(5,150,105,.10);color:#065f46;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="pr-info-label">Status</div>
                            <div class="pr-info-value">
                                @php $st = Auth::user()->status; @endphp
                                <span class="pr-badge {{ $st === 'active' ? 'pr-badge-success' : ($st === 'inactive' ? 'pr-badge-warning' : 'pr-badge-danger') }}">
                                    {{ ucfirst($st) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pr-info-row">
                        <div class="pr-info-icon" style="background:rgba(116,255,112,.12);color:#064e3b;border:1px solid rgba(116,255,112,.28);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="pr-info-label">Member Since</div>
                            <div class="pr-info-value">{{ Auth::user()->created_at->format('M j, Y') }}</div>
                        </div>
                    </div>

                </div>

                {{-- Image history --}}
                @if(Auth::user()->profileImages->count() > 0)
                    <div style="border-top:1px solid #d1fae5;padding-top:14px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <div style="width:26px;height:26px;border-radius:7px;background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-history" style="font-size:.68rem;color:#064e3b;"></i>
                            </div>
                            <span style="font-size:.72rem;font-weight:700;color:#052e22;text-transform:uppercase;letter-spacing:.06em;">Image History</span>
                        </div>

                        <div style="display:flex;flex-wrap:wrap;gap:12px;">
                            @foreach(Auth::user()->profileImages as $image)
                                <div style="position:relative;text-align:center;">
                                    <div style="position:relative;">
                                        <img src="{{ $image->image_url }}"
                                             alt="Profile {{ $loop->iteration }}"
                                             class="pr-thumb {{ $image->is_current ? 'current' : '' }}"
                                             onclick="setAsCurrentImage({{ $image->id }})"
                                             title="{{ $image->is_current ? 'Current profile image' : 'Set as current' }}">
                                        @if($image->is_current)
                                            <div style="position:absolute;top:-5px;left:50%;transform:translateX(-50%);">
                                                <i class="fas fa-check-circle" style="font-size:13px;color:#52e84e;background:#fff;border-radius:50%;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!$image->is_current)
                                        <button type="button"
                                                style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;border-radius:50%;background:#ef4444;border:2px solid #fff;color:#fff;font-size:10px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;"
                                                onclick="deleteProfileImage({{ $image->id }})"
                                                title="Delete">×</button>
                                    @endif
                                    <small style="display:block;margin-top:4px;font-size:.67rem;color:#3d7a62;font-weight:600;">
                                        {{ $image->created_at->format('M j') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>

                        @if(Auth::user()->profileImages->count() > 1)
                            <div style="margin-top:8px;font-size:.72rem;color:#3d7a62;text-align:center;">
                                Click any image to set it as your current profile picture
                            </div>
                        @endif
                    </div>
                @else
                    <div style="text-align:center;padding:18px 0 6px;border-top:1px solid #d1fae5;">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(116,255,112,.10);border:1px solid rgba(116,255,112,.25);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                            <i class="fas fa-images" style="color:#3d7a62;font-size:1.1rem;"></i>
                        </div>
                        <div style="font-size:.84rem;font-weight:600;color:#052e22;margin-bottom:3px;">No profile images yet</div>
                        <div style="font-size:.75rem;color:#3d7a62;">Upload your first photo using the camera button above</div>
                    </div>
                @endif
            </div>

            {{-- ── FOOTER ── --}}
            <div style="padding:14px 22px;background:#f0fdf4;border-top:1px solid #d1fae5;display:flex;justify-content:center;">
                <button type="button" class="pr-close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times" style="font-size:.7rem;"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const profileInput   = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profilePreview');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadSuccess  = document.getElementById('uploadSuccess');
    const uploadError    = document.getElementById('uploadError');

    profileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) { showError('File size must be less than 2MB'); return; }
        if (!['image/jpeg','image/png','image/jpg','image/gif'].includes(file.type)) {
            showError('Please select a valid image (JPEG, PNG, JPG, GIF)'); return;
        }
        const reader = new FileReader();
        reader.onload = ev => {
            profilePreview.innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
        };
        reader.readAsDataURL(file);
        uploadProfileImage(file);
    });

    function uploadProfileImage(file) {
        const fd = new FormData();
        fd.append('profile_image', file);
        showProgress(); hideMessages();

        fetch('{{ route("admin.profile.image.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        })
        .then(async r => { const d = await r.json(); if (!r.ok) throw new Error(d.message || `Error ${r.status}`); return d; })
        .then(data => {
            hideProgress();
            if (data.success) {
                showSuccess(data.message);
                if (data.image_url) profilePreview.innerHTML = `<img src="${data.image_url}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
                setTimeout(() => location.reload(), 1500);
            } else showError(data.message || 'Upload failed');
        })
        .catch(err => {
            hideProgress();
            if (!navigator.onLine) showError('Network error. Check your connection.');
            else if (err.message.includes('413')) showError('File too large (max 2MB).');
            else if (err.message.includes('419')) showError('Session expired. Refresh and try again.');
            else showError(err.message || 'Upload failed. Please try again.');
        });
    }

    function setAsCurrentImage(imageId) {
        if (!confirm('Set this image as your current profile picture?')) return;
        fetch(`/admin/profile-image/${imageId}/set-current`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => { if (data.success) { showSuccess(data.message); setTimeout(() => location.reload(), 1000); } else showError(data.message); })
        .catch(() => showError('Failed to set profile image. Please try again.'));
    }

    function deleteProfileImage(imageId) {
        if (!confirm('Delete this profile image? This cannot be undone.')) return;
        fetch(`/admin/profile-image/${imageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => { if (data.success) { showSuccess(data.message); setTimeout(() => location.reload(), 1000); } else showError(data.message); })
        .catch(() => showError('Failed to delete image. Please try again.'));
    }

    function showProgress() { uploadProgress.classList.remove('d-none'); }
    function hideProgress() { uploadProgress.classList.add('d-none'); }
    function showSuccess(msg) { uploadSuccess.querySelector('span').textContent = msg; uploadSuccess.classList.remove('d-none'); uploadError.classList.add('d-none'); }
    function showError(msg)   { uploadError.querySelector('span').textContent = msg;   uploadError.classList.remove('d-none');   uploadSuccess.classList.add('d-none'); }
    function hideMessages()   { uploadSuccess.classList.add('d-none'); uploadError.classList.add('d-none'); }

    document.getElementById('profileModal').addEventListener('hidden.bs.modal', function() {
        profileInput.value = '';
        hideMessages();
        hideProgress();
    });
</script>