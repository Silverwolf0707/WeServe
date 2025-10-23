<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

            <!-- Profile Header -->
            <div class="position-relative text-center py-4"
                style="background: linear-gradient(135deg, #4e73df, #1cc88a);">

                <div class="position-relative mx-auto mb-2" style="width: 120px; height: 120px;">
                    <div class="rounded-circle border border-4 border-white shadow w-100 h-100 overflow-hidden"
                        id="profilePreview">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                                class="rounded-circle w-100 h-100 object-fit-cover">
                        @else
                            <i class="fas fa-user text-secondary" style="font-size: 80px; line-height: 120px;"></i>
                        @endif
                    </div>

                    <label for="profileImageInput"
                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow"
                        style="cursor: pointer;">
                        <i class="fas fa-camera text-primary"></i>
                    </label>
                    <input type="file" id="profileImageInput" class="d-none" accept="image/*">
                </div>

                <h5 class="text-white fw-bold mb-0">{{ Auth::user()->name }}</h5>
                <small class="text-white-50">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</small>
            </div>

            <div class="modal-body px-4 py-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-envelope text-info me-3 fa-lg"></i>
                        <div>
                            <strong>Email</strong>
                            <div class="text-muted">{{ Auth::user()->email }}</div>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-user-tag text-warning me-3 fa-lg"></i>
                        <div>
                            <strong>Role</strong>
                            <div class="text-muted">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="modal-footer border-0 justify-content-center py-3" style="background-color: #f1f3f7;">
                <button type="button" class="btn btn-primary btn-lg" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const profileInput = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profilePreview');

    profileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                profilePreview.innerHTML = `<img src="${event.target.result}" class="rounded-circle w-100 h-100 object-fit-cover">`;
            }
            reader.readAsDataURL(file);
        }
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
        /* Ensure spacing remains consistent */
        margin-left: 0 !important;
    }
</style>