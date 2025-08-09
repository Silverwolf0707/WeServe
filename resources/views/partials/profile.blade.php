<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow rounded-4 border-0 overflow-hidden">

            <!-- Profile Image -->
            <div class="position-relative text-center pt-3"
                style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
                <div class="rounded-circle border border-3 border-white shadow-sm mx-auto"
                    style="width: 90px; height: 90px; background-color: #ffffff; overflow: hidden;">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                            class="rounded-circle w-100 h-100 object-fit-cover">
                    @else
                        <i class="fas fa-user text-secondary" style="font-size: 90px; line-height: 90px;"></i>
                    @endif
                </div>
            </div>


            <div class="modal-body px-3 py-2">
                <ul class="list-group list-group-flush list-unstyled">
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-user text-primary me-2 fa-sm"></i>
                        <span class="text-dark"><strong>Name:</strong> {{ Auth::user()->name }}</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-envelope text-info me-2 fa-sm"></i>
                        <span class="text-dark"><strong>Email:</strong> {{ Auth::user()->email }}</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <i class="fas fa-user-tag text-warning me-2 fa-sm"></i>
                        <span class="text-dark"><strong>Role:</strong>
                            {{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</span>
                    </li>
                </ul>
            </div>


            <!-- Footer -->
            <div class="modal-footer px-3 py-2 d-flex justify-content-between" style="background-color: #f8f9fc;">
                <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>