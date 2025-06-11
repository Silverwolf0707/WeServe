<li class="c-header-nav-item dropdown">
    <a class="c-header-nav-link d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle fa-lg mr-2"></i>
        <span>{{ Auth::user()->name }}</span>
        <i class="fas fa-caret-down ml-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 mt-2">
        <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal" data-target="#profileModal">
            <i class="fas fa-id-badge fa-fw mr-2 text-primary"></i>
            View Profile
        </a>
    </div>
</li>
