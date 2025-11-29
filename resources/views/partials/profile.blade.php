<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

            <!-- Profile Header -->
            <div class="position-relative text-center py-4"
                style="background: linear-gradient(135deg, #4e73df, #1cc88a);">

                <div class="position-relative mx-auto mb-2" style="width: 120px; height: 120px;">
                    <div class="rounded-circle border border-4 border-white shadow w-100 h-100 overflow-hidden"
                        id="profilePreview">
                        @if(Auth::user()->currentProfileImage)
                            <img src="{{ Auth::user()->currentProfileImage->image_url }}" alt="Profile"
                                class="rounded-circle w-100 h-100 object-fit-cover">
                        @else
                            <i class="fas fa-user text-white" style="font-size: 80px; line-height: 120px;"></i>
                        @endif
                    </div>

                    <label for="profileImageInput"
                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow"
                        style="cursor: pointer; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-camera text-primary" style="font-size: 14px;"></i>
                    </label>
                    <input type="file" id="profileImageInput" class="d-none" accept="image/*">
                </div>

                <h5 class="text-white fw-bold mb-0">{{ Auth::user()->name }}</h5>
                <small class="text-white-50">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</small>
                
                <!-- Last Login Info -->
                @if(Auth::user()->last_login_at)
                    <div class="mt-1">
                        <small class="text-white-50">
                            Last login: {{ Auth::user()->last_login_at->diffForHumans() }}
                        </small>
                    </div>
                @endif
            </div>

            <div class="modal-body px-4 py-3">
                <!-- Upload Status Messages -->
                <div id="uploadProgress" class="alert alert-info d-none mb-3">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <span>Uploading profile image...</span>
                    </div>
                </div>

                <div id="uploadSuccess" class="alert alert-success d-none mb-3"></div>

                <div id="uploadError" class="alert alert-danger d-none mb-3"></div>

                <!-- User Information -->
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-envelope text-info me-3 fa-lg"></i>
                        <div class="flex-grow-1">
                            <strong>Email</strong>
                            <div class="text-muted">{{ Auth::user()->email }}</div>
                        </div>
                        <span class="badge badge-{{ Auth::user()->hasVerifiedEmail() ? 'success' : 'warning' }}">
                            {{ Auth::user()->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                        </span>
                    </li>
                    
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-user-tag text-warning me-3 fa-lg"></i>
                        <div>
                            <strong>Role</strong>
                            <div class="text-muted">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</div>
                        </div>
                    </li>
                    
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-user-check text-success me-3 fa-lg"></i>
                        <div>
                            <strong>Status</strong>
                            <div class="text-muted">
                                <span class="badge badge-{{ Auth::user()->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(Auth::user()->status) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-calendar-alt text-primary me-3 fa-lg"></i>
                        <div>
                            <strong>Member Since</strong>
                            <div class="text-muted">{{ Auth::user()->created_at->format('M j, Y') }}</div>
                        </div>
                    </li>
                </ul>

                <!-- Profile Images History -->
                @if(Auth::user()->profileImages->count() > 0)
                <div class="border-top pt-3">
                    <h6 class="text-muted mb-3 d-flex align-items-center">
                        <i class="fas fa-history me-2"></i>
                        Profile Image History
                    </h6>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach(Auth::user()->profileImages as $image)
                            <div class="position-relative text-center">
                                <div class="position-relative">
                                    <img src="{{ $image->image_url }}" alt="Profile image {{ $loop->iteration }}" 
                                         class="rounded-circle border-2 {{ $image->is_current ? 'border-primary' : 'border-light' }}"
                                         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border-width: 3px !important;"
                                         onclick="setAsCurrentImage({{ $image->id }})"
                                         title="{{ $image->is_current ? 'Current profile image' : 'Set as current profile image' }}">
                                    
                                    @if($image->is_current)
                                        <div class="position-absolute top-0 start-50 translate-middle mt-1">
                                            <i class="fas fa-check-circle text-success bg-white rounded-circle" style="font-size: 12px;"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                @if(!$image->is_current)
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-0"
                                            style="width: 20px; height: 20px; font-size: 10px; line-height: 1; transform: translate(30%, -30%);"
                                            onclick="deleteProfileImage({{ $image->id }})"
                                            title="Delete this image">
                                        ×
                                    </button>
                                @endif
                                
                                <small class="d-block mt-1 text-muted" style="font-size: 10px;">
                                    {{ $image->created_at->format('M j') }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(Auth::user()->profileImages->count() > 1)
                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                Click on any image to set it as your current profile picture
                            </small>
                        </div>
                    @endif
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-images text-muted fa-3x mb-2"></i>
                    <p class="text-muted mb-0">No profile images yet</p>
                    <small class="text-muted">Upload your first profile image using the camera button</small>
                </div>
                @endif
            </div>

            <div class="modal-footer border-0 justify-content-center py-3" style="background-color: #f8f9fa;">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <a href="{{ route('profile.password.edit') }}" class="btn btn-primary">
                    <i class="fas fa-key me-1"></i> Change Password
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Elements
    const profileInput = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profilePreview');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadSuccess = document.getElementById('uploadSuccess');
    const uploadError = document.getElementById('uploadError');

    // File input change handler
    profileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showError('File size must be less than 2MB');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showError('Please select a valid image file (JPEG, PNG, JPG, GIF)');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function (event) {
                profilePreview.innerHTML = `<img src="${event.target.result}" class="rounded-circle w-100 h-100 object-fit-cover">`;
            };
            reader.readAsDataURL(file);

            // Upload to server
            uploadProfileImage(file);
        }
    });

    // Upload profile image to server
   function uploadProfileImage(file) {
    const formData = new FormData();
    formData.append('profile_image', file);

    // Show progress
    showProgress();
    hideMessages();

    fetch('{{ route("admin.profile.image.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        
        // Check if response is successful (2xx status)
        if (response.ok) {
            return data;
        } else {
            // Server returned error with message
            throw new Error(data.message || `Server error: ${response.status}`);
        }
    })
    .then(data => {
        hideProgress();
        
        if (data.success) {
            showSuccess(data.message);
            // Update preview with new image URL
            if (data.image_url) {
                profilePreview.innerHTML = `<img src="${data.image_url}" class="rounded-circle w-100 h-100 object-fit-cover">`;
            }
            
            // Reload the images history after a short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showError(data.message || 'Upload failed');
        }
    })
    .catch(error => {
        hideProgress();
        console.error('Upload error details:', error);
        
        // More specific error messages
        if (error.message.includes('Network') || !navigator.onLine) {
            showError('Network error. Please check your internet connection.');
        } else if (error.message.includes('413')) {
            showError('File too large. Please select a smaller image (max 2MB).');
        } else if (error.message.includes('419')) {
            showError('Session expired. Please refresh the page and try again.');
        } else {
            showError(error.message || 'An error occurred during upload. Please try again.');
        }
    });
}

   // Set image as current profile picture
function setAsCurrentImage(imageId) {
    if (confirm('Set this image as your current profile picture?')) {
        fetch(`/admin/profile-image/${imageId}/set-current`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            showError('Failed to set profile image. Please try again.');
            console.error('Set current error:', error);
        });
    }
}

// Delete profile image
function deleteProfileImage(imageId) {
    if (confirm('Are you sure you want to delete this profile image? This action cannot be undone.')) {
        fetch(`/admin/profile-image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            showError('Failed to delete profile image. Please try again.');
            console.error('Delete error:', error);
        });
    }
}

    // Helper functions for message display
    function showProgress() {
        uploadProgress.classList.remove('d-none');
    }

    function hideProgress() {
        uploadProgress.classList.add('d-none');
    }

    function showSuccess(message) {
        uploadSuccess.textContent = message;
        uploadSuccess.classList.remove('d-none');
        uploadError.classList.add('d-none');
    }

    function showError(message) {
        uploadError.textContent = message;
        uploadError.classList.remove('d-none');
        uploadSuccess.classList.add('d-none');
    }

    function hideMessages() {
        uploadSuccess.classList.add('d-none');
        uploadError.classList.add('d-none');
    }

    // Reset form when modal is closed
    document.getElementById('profileModal').addEventListener('hidden.bs.modal', function () {
        profileInput.value = '';
        hideMessages();
        hideProgress();
    });
</script>

<style>
    /* Fix for layout reversal when viewing Process Tracking show page */
    #profileModal .list-group-item {
        flex-direction: row !important;
        text-align: left !important;
        justify-content: flex-start !important;
    }

    #profileModal .list-group-item i {
        margin-right: 0.75rem !important;
        margin-left: 0 !important;
        width: 20px;
        text-align: center;
    }

    /* Hover effects for profile images */
    #profileModal img[style*="width: 60px"] {
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }

    #profileModal img[style*="width: 60px"]:hover {
        transform: scale(1.1);
        border-color: #4e73df !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Current image highlight */
    #profileModal .border-primary {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 2px #4e73df;
    }

    /* Badge styles */
    #profileModal .badge {
        font-size: 0.7em;
        padding: 0.25em 0.6em;
    }

    /* Camera button hover effect */
    #profileModal label[for="profileImageInput"]:hover {
        background-color: #e9ecef !important;
        transform: scale(1.1);
        transition: all 0.2s ease;
    }

    /* Modal content animations */
    #profileModal .modal-content {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        #profileModal .modal-dialog {
            margin: 20px;
        }
        
        #profileModal .position-relative.mx-auto.mb-2 {
            width: 100px !important;
            height: 100px !important;
        }
        
        #profileModal .fa-user {
            font-size: 60px !important;
            line-height: 100px !important;
        }
    }
</style>