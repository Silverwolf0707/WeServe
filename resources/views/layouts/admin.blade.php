<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>WeServe</title>
  <link rel="icon" type="image/png+xml" href="{{ asset('home-icon (1).png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
  <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  <link href="{{ asset('css/context-menu.css') }}" rel="stylesheet">

  <style>
    /* ═══════════════════════════════════════════════
       WeServe — Top Navbar & Notification Panel
       Forest-green system / DM Sans
       ═══════════════════════════════════════════════ */
    :root {
      --ws-forest:       #064e3b;
      --ws-forest-deep:  #052e22;
      --ws-forest-mid:   #065f46;
      --ws-lime:         #74ff70;
      --ws-lime-dim:     #52e84e;
      --ws-lime-ghost:   rgba(116,255,112,.10);
      --ws-lime-border:  rgba(116,255,112,.28);
      --ws-sidebar-w:    248px;
      --ws-sidebar-w-col: 62px;
      --ws-navbar-h:     54px;
      --ws-transition:   0.22s cubic-bezier(.4,0,.2,1);
    }

    /* ── Base reset for navbar ── */
    .ws-navbar {
      position: fixed;
      top: 0;
      left: var(--ws-sidebar-w);
      right: 0;
      height: var(--ws-navbar-h);
      background: #fff;
      border-bottom: 1px solid #e8f5e9;
      display: flex;
      align-items: center;
      padding: 0 18px 0 16px;
      gap: 8px;
      z-index: 1020;
      transition: left var(--ws-transition);
      font-family: 'DM Sans', sans-serif;
      box-shadow: 0 1px 0 rgba(6,78,59,.06), 0 2px 12px rgba(6,78,59,.05);
    }

    body.sidebar-collapse .ws-navbar { left: var(--ws-sidebar-w-col); }

    /* hide the old AdminLTE navbar if present */
    nav.main-header.navbar:not(.ws-navbar) { display: none !important; }

    /* ── Toggle button ── */
    .ws-toggle {
      width: 34px; height: 34px;
      border: none;
      background: transparent;
      border-radius: 8px;
      color: #6b7280;
      font-size: .88rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition: all var(--ws-transition);
      flex-shrink: 0;
    }
    .ws-toggle:hover {
      background: #f0fdf4;
      color: var(--ws-forest);
    }

    /* ── Breadcrumb / page context ── */
    .ws-breadcrumb {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: .78rem;
      font-weight: 500;
      color: #9ca3af;
      margin-left: 2px;
      flex: 1;
    }
    .ws-breadcrumb a {
      color: #9ca3af;
      text-decoration: none;
      transition: color .15s;
    }
    .ws-breadcrumb a:hover { color: var(--ws-forest); }
    .ws-breadcrumb .ws-bc-sep { color: #d1d5db; font-size: .65rem; }
    .ws-breadcrumb .ws-bc-current {
      color: var(--ws-forest);
      font-weight: 600;
    }

    /* ── Logo in navbar ── */
    .ws-navbar-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      flex-shrink: 0;
    }
    .ws-navbar-logo img { height: 28px; width: auto; }

    /* ── Right cluster ── */
    .ws-navbar-right {
      display: flex;
      align-items: center;
      gap: 4px;
      margin-left: auto;
    }

    /* ── Icon buttons (bell, etc) ── */
    .ws-icon-btn {
      position: relative;
      width: 36px; height: 36px;
      border: none;
      background: transparent;
      border-radius: 9px;
      color: #6b7280;
      font-size: .88rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition: all var(--ws-transition);
      text-decoration: none;
    }
    .ws-icon-btn:hover {
      background: #f0fdf4;
      color: var(--ws-forest);
    }
    .ws-icon-btn.ws-active-bell {
      background: var(--ws-lime-ghost);
      color: var(--ws-forest);
    }

    /* ── Notification badge ── */
    .ws-notif-badge {
      position: absolute;
      top: 4px; right: 4px;
      min-width: 16px; height: 16px;
      background: #ef4444;
      border-radius: 8px;
      font-size: .58rem;
      font-weight: 700;
      color: #fff;
      display: flex; align-items: center; justify-content: center;
      padding: 0 4px;
      border: 2px solid #fff;
      line-height: 1;
      font-family: 'DM Sans', sans-serif;
    }

    /* ── Divider ── */
    .ws-navbar-divider {
      width: 1px; height: 22px;
      background: #e5e7eb;
      margin: 0 4px;
      flex-shrink: 0;
    }

    /* ── Notification Panel ── */
    .ws-notif-panel {
      position: fixed;
      top: calc(var(--ws-navbar-h) + 6px);
      right: 12px;
      width: 390px;
      background: #fff;
      border-radius: 14px;
      border: 1px solid #e8f5e9;
      box-shadow: 0 8px 32px rgba(6,78,59,.14), 0 2px 8px rgba(0,0,0,.06);
      z-index: 1040;
      font-family: 'DM Sans', sans-serif;
      overflow: hidden;
      opacity: 0;
      transform: translateY(-8px) scale(.98);
      pointer-events: none;
      transition: opacity .18s ease, transform .18s ease;
    }
    .ws-notif-panel.ws-open {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: all;
    }

    /* Panel header */
    .ws-notif-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 16px 10px;
      border-bottom: 1px solid #f0fdf4;
      background: linear-gradient(135deg, #052e22 0%, #064e3b 100%);
    }
    .ws-notif-header-left {
      display: flex;
      align-items: center;
      gap: 9px;
    }
    .ws-notif-header-icon {
      width: 30px; height: 30px;
      background: var(--ws-lime-ghost);
      border: 1px solid var(--ws-lime-border);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: var(--ws-lime);
      font-size: .78rem;
    }
    .ws-notif-title {
      font-size: .88rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: -.01em;
    }
    .ws-notif-subtitle {
      font-size: .68rem;
      color: rgba(255,255,255,.5);
      margin-top: 1px;
    }
    .ws-notif-count-pill {
      display: inline-flex;
      align-items: center;
      background: rgba(116,255,112,.14);
      border: 1px solid var(--ws-lime-border);
      color: var(--ws-lime);
      border-radius: 20px;
      font-size: .64rem;
      font-weight: 700;
      padding: 2px 9px;
      letter-spacing: .04em;
    }

    /* Panel filter row */
    .ws-notif-filter {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      border-bottom: 1px solid #f0fdf4;
      background: #f8fffe;
    }
    .ws-notif-filter select {
      flex: 1;
      border: 1.5px solid #d1fae5;
      border-radius: 8px;
      padding: 5px 10px;
      font-size: .76rem;
      font-family: 'DM Sans', sans-serif;
      color: #052e22;
      background: #fff;
      cursor: pointer;
      transition: border-color .15s;
      outline: none;
    }
    .ws-notif-filter select:focus { border-color: var(--ws-forest); }
    .ws-notif-clear-btn {
      width: 28px; height: 28px;
      border: 1.5px solid #d1fae5;
      border-radius: 7px;
      background: #fff;
      color: #6b7280;
      font-size: .7rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition: all .15s;
      flex-shrink: 0;
    }
    .ws-notif-clear-btn:hover {
      background: #fef2f2;
      border-color: #fca5a5;
      color: #ef4444;
    }

    /* Panel items scroll area */
    .ws-notif-scroll {
      max-height: 360px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #d1fae5 transparent;
    }
    .ws-notif-scroll::-webkit-scrollbar { width: 4px; }
    .ws-notif-scroll::-webkit-scrollbar-track { background: transparent; }
    .ws-notif-scroll::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }

    /* Notification item */
    .ws-notif-item {
      display: flex;
      align-items: flex-start;
      gap: 11px;
      padding: 12px 14px;
      cursor: pointer;
      transition: background .15s;
      border-bottom: 1px solid #f0fdf4;
      text-decoration: none;
      color: inherit;
    }
    .ws-notif-item:last-child { border-bottom: none; }
    .ws-notif-item:hover { background: #f8fffe; text-decoration: none; }
    .ws-notif-item.ws-unread { background: #f0fdf4; }
    .ws-notif-item.ws-unread:hover { background: #dcfce7; }

    .ws-notif-item-icon {
      width: 36px; height: 36px;
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: .78rem;
      flex-shrink: 0;
    }
    .ws-notif-item-body { flex: 1; min-width: 0; }
    .ws-notif-item-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 8px;
      margin-bottom: 2px;
    }
    .ws-notif-item-title {
      font-size: .8rem;
      font-weight: 600;
      color: #052e22;
      line-height: 1.3;
    }
    .ws-notif-item.ws-unread .ws-notif-item-title { color: var(--ws-forest); }
    .ws-notif-item-time {
      font-size: .66rem;
      color: #9ca3af;
      white-space: nowrap;
      flex-shrink: 0;
    }
    .ws-notif-item-msg {
      font-size: .74rem;
      color: #6b7280;
      line-height: 1.4;
      margin-bottom: 5px;
    }
    .ws-notif-item-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .ws-notif-item-by { font-size: .67rem; color: #9ca3af; }
    .ws-notif-dept-tag {
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .04em;
      padding: 1px 7px;
      border-radius: 5px;
      background: #f0fdf4;
      border: 1px solid #d1fae5;
      color: var(--ws-forest-mid);
    }
    .ws-unread-dot {
      width: 7px; height: 7px;
      background: var(--ws-lime-dim);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 5px;
      box-shadow: 0 0 5px rgba(116,255,112,.5);
    }

    /* Loading / empty states */
    .ws-notif-state {
      padding: 28px 16px;
      text-align: center;
      color: #9ca3af;
      font-size: .8rem;
    }
    .ws-notif-state i { font-size: 1.8rem; margin-bottom: 8px; display: block; }

    /* Load more */
    .ws-notif-load-more {
      padding: 10px 14px;
      border-top: 1px solid #f0fdf4;
    }
    .ws-notif-load-more-btn {
      width: 100%;
      border: 1.5px solid #d1fae5;
      background: #f8fffe;
      border-radius: 8px;
      padding: 7px;
      font-size: .76rem;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      color: var(--ws-forest);
      cursor: pointer;
      transition: all .15s;
      display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .ws-notif-load-more-btn:hover {
      background: var(--ws-lime-ghost);
      border-color: var(--ws-lime-border);
    }

    /* Stats row */
    .ws-notif-stats {
      padding: 6px 14px;
      border-top: 1px solid #f0fdf4;
      font-size: .68rem;
      color: #9ca3af;
      text-align: center;
      background: #f8fffe;
    }

    /* Footer actions */
    .ws-notif-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 14px;
      border-top: 1px solid #e8f5e9;
      background: #f8fffe;
      gap: 8px;
    }
    .ws-notif-footer-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 1.5px solid #d1fae5;
      background: #fff;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: .74rem;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      color: var(--ws-forest);
      cursor: pointer;
      transition: all .15s;
      text-decoration: none;
    }
    .ws-notif-footer-btn:hover {
      background: var(--ws-lime-ghost);
      border-color: var(--ws-lime-border);
      color: var(--ws-forest);
      text-decoration: none;
    }
    .ws-notif-footer-btn.ws-btn-mark {
      color: #3d7a62;
    }

    /* Panel backdrop */
    .ws-notif-backdrop {
      position: fixed;
      inset: 0;
      z-index: 1035;
      display: none;
    }
    .ws-notif-backdrop.ws-open { display: block; }

    /* ── Content wrapper offset ── */
    .content-wrapper {
      margin-left: var(--ws-sidebar-w) !important;
      margin-top: var(--ws-navbar-h) !important;
      transition: margin-left var(--ws-transition);
    }
    body.sidebar-collapse .content-wrapper {
      margin-left: var(--ws-sidebar-w-col) !important;
    }

    /* ── Toast improvements ── */
    .custom-toast {
      font-family: 'DM Sans', sans-serif;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e8f5e9;
      box-shadow: 0 8px 24px rgba(6,78,59,.12);
      min-width: 300px;
    }
    .toast-success { border-left: 3px solid #10b981; }
    .toast-danger  { border-left: 3px solid #ef4444; }
    .toast-warning { border-left: 3px solid #f59e0b; }
    .toast-info    { border-left: 3px solid #3b82f6; }
    .toast-header  { font-size: .82rem; font-weight: 600; border-bottom: 1px solid #f0fdf4; }
    .toast-body    { font-size: .8rem; padding: 10px 14px; }
    .toast-icon    { margin-right: 8px; font-size: .9rem; }
    .toast-progress {
      height: 3px;
      background: linear-gradient(to right, var(--ws-forest), var(--ws-lime));
      animation: toastProgress 5s linear forwards;
    }
    @keyframes toastProgress {
      from { width: 100%; }
      to   { width: 0%; }
    }

    /* ── Loading overlay ── */
    /* FIX: use explicit properties instead of `inset` shorthand, and override
       any AdminLTE stacking-context or margin that could offset a fixed element */
    #loading-overlay {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      bottom: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      margin: 0 !important;
      padding: 0 !important;
      transform: none !important;
      background: rgba(5,46,34,.92);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity .4s ease;
    }
  </style>

  @yield('styles')
</head>

@if(session('toast'))
  @php
    $toast = session('toast');
    $bgClass = match ($toast['type']) {
        'success' => 'toast-success',
        'danger'  => 'toast-danger',
        'warning' => 'toast-warning',
        'info'    => 'toast-info',
        default   => 'bg-secondary',
    };
    $icons = [
        'success' => 'fas fa-check-circle',
        'danger'  => 'fas fa-exclamation-triangle',
        'warning' => 'fas fa-exclamation-circle',
        'info'    => 'fas fa-info-circle',
    ];
    $icon = $icons[$toast['type']] ?? 'fas fa-bell';
  @endphp
  <div class="position-fixed top-0 end-0 p-3" style="z-index:1055;">
    <div id="liveToast" class="toast custom-toast {{ $bgClass }}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-progress"></div>
      <div class="toast-header bg-white text-dark">
        <i class="{{ $icon }} toast-icon text-{{ $toast['type'] }}"></i>
        <strong class="me-auto">{{ $toast['title'] ?? 'Notification' }}</strong>
        <small class="text-muted" id="toast-timer">Just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">{!! session('toast')['message'] !!}</div>
    </div>
  </div>
@endif

<body class="sidebar-mini layout-fixed" style="height:auto;">

{{-- Loading Screen — kept as direct child of <body>, BEFORE .wrapper,
     so no parent transform/overflow can interfere with position:fixed --}}
<div id="loading-overlay">
  <div class="loader-wrapper">
    <div class="circle"></div>
    <div class="loader-logo"></div>
  </div>
</div>

<div class="wrapper">

  {{-- ═══════════════════════════════════════
       TOP NAVBAR
       ═══════════════════════════════════════ --}}
  <nav class="ws-navbar">

    {{-- Hamburger --}}
    <button class="ws-toggle" data-widget="pushmenu" title="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>

    {{-- Logo --}}
    <a href="{{ route('admin.home') }}" class="ws-navbar-logo ms-1">
      <img src="{{ asset('home-logo.png') }}" alt="WeServe">
    </a>  

    {{-- Right cluster --}}
    <div class="ws-navbar-right">

      {{-- Notification bell --}}
      <button class="ws-icon-btn" id="wsNotifToggle" title="Notifications">
        <i class="fas fa-bell"></i>
        <span class="ws-notif-badge" id="notification-badge" style="display:none;">0</span>
      </button>

      <div class="ws-navbar-divider"></div>

      {{-- User avatar shortcut --}}
      <button class="ws-icon-btn" data-bs-toggle="modal" data-bs-target="#profileModal" title="Profile">
        @if(Auth::user()->currentProfileImage)
          <img src="{{ Auth::user()->currentProfileImage->image_url }}"
               style="width:26px;height:26px;border-radius:7px;object-fit:cover;border:1.5px solid #d1fae5;">
        @else
          <div style="width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,#064e3b,#065f46);display:flex;align-items:center;justify-content:center;border:1.5px solid #d1fae5;">
            <i class="fas fa-user" style="color:#74ff70;font-size:.62rem;"></i>
          </div>
        @endif
      </button>

    </div>
  </nav>

  {{-- ═══════════════════════════════════════
       NOTIFICATION PANEL (custom dropdown)
       ═══════════════════════════════════════ --}}
  <div class="ws-notif-backdrop" id="wsNotifBackdrop"></div>

  <div class="ws-notif-panel" id="wsNotifPanel">

    {{-- Header --}}
    <div class="ws-notif-header">
      <div class="ws-notif-header-left">
        <div class="ws-notif-header-icon"><i class="fas fa-bell"></i></div>
        <div>
          <div class="ws-notif-title">Notifications <span style="font-size:.62rem;background:rgba(245,158,11,.2);color:#fbbf24;border:1px solid rgba(245,158,11,.3);border-radius:4px;padding:1px 5px;font-weight:800;letter-spacing:.05em;vertical-align:middle;">BETA</span></div>
          <div class="ws-notif-subtitle">Stay updated on case progress</div>
        </div>
      </div>
      <span class="ws-notif-count-pill" id="notification-count" style="display:none;">0 new</span>
    </div>

    {{-- Filter --}}
    <div class="ws-notif-filter">
      <i class="fas fa-filter" style="font-size:.7rem;color:#a7f3d0;flex-shrink:0;"></i>
      <select id="department-filter">
        <option value="">All Departments</option>
        <option value="CSWD Office">CSWD Office</option>
        <option value="Mayor's Office">Mayor's Office</option>
        <option value="Budget Office">Budget Office</option>
        <option value="Accounting Office">Accounting Office</option>
        <option value="Treasury Office">Treasury Office</option>
      </select>
      <button class="ws-notif-clear-btn" id="clear-filter" style="display:none;" title="Clear filter">
        <i class="fas fa-times"></i>
      </button>
    </div>

    {{-- Items --}}
    <div class="ws-notif-scroll">
      <div id="notification-items">
        <div class="ws-notif-state">
          <div class="spinner-border text-success spinner-border-sm" role="status"></div>
          <div style="margin-top:8px;font-size:.76rem;">Loading notifications…</div>
        </div>
      </div>

      {{-- Load more --}}
      <div class="ws-notif-load-more" id="load-more-container" style="display:none;">
        <button class="ws-notif-load-more-btn" id="load-more-notifications">
          <i class="fas fa-arrow-down" style="font-size:.7rem;"></i> Load More
        </button>
      </div>
    </div>

    {{-- Stats --}}
    <div class="ws-notif-stats" id="notification-stats" style="display:none;">
      Showing <strong id="shown-count">0</strong> of <strong id="total-count">0</strong> notifications
    </div>

    {{-- Footer --}}
    <div class="ws-notif-footer">
      <button class="ws-notif-footer-btn ws-btn-mark" id="mark-all-read">
        <i class="fas fa-check-double"></i> Mark all read
      </button>
      <a class="ws-notif-footer-btn">
        <i class="fas fa-list" style="font-size:.7rem;"></i> View all
      </a>
    </div>

  </div>
  {{-- /notification panel --}}

  @if(count(config('panel.available_languages', [])) > 1)
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">{{ strtoupper(app()->getLocale()) }}</a>
        <div class="dropdown-menu dropdown-menu-right">
          @foreach(config('panel.available_languages') as $langLocale => $langName)
            <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">
              {{ strtoupper($langLocale) }} ({{ $langName }})
            </a>
          @endforeach
        </div>
      </li>
    </ul>
  @endif

  @include('partials.menu')
  @include('partials.profile')

  <div class="content-wrapper" style="min-height:917px;">
    <section class="content" style="padding-top:20px;">
      @if(session('message'))
        <div class="row mb-2">
          <div class="col-lg-12">
            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
          </div>
        </div>
      @endif
      @if($errors->count() > 0)
        <div class="alert alert-danger">
          <ul class="list-unstyled">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @yield('content')
    </section>
  </div>

  <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
    {{ csrf_field() }}
  </form>

  {{-- Logout Modal --}}
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius:12px;overflow:hidden;font-family:'DM Sans',sans-serif;border:none;box-shadow:0 8px 32px rgba(0,0,0,.15);">
        <div class="modal-header" style="background:linear-gradient(135deg,#7f1d1d,#ef4444);border:none;">
          <h5 class="modal-title" style="color:#fff;font-size:.92rem;font-weight:700;">
            <i class="fas fa-sign-out-alt me-2"></i>Confirm Logout
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
        </div>
        <div class="modal-body" style="padding:20px 22px;">
          <p style="font-size:.85rem;color:#374151;margin:0 0 6px;">Are you sure you want to logout?</p>
          <p style="font-size:.76rem;color:#9ca3af;margin:0;">You will need to login again to access the system.</p>
        </div>
        <div class="modal-footer" style="background:#f9fafb;border-top:1px solid #f0fdf4;gap:8px;padding:12px 18px;">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancel
          </button>
          <button type="button" class="btn btn-danger btn-sm"
                  onclick="const b=this;b.disabled=true;b.innerHTML='<i class=\'fas fa-spinner fa-spin me-1\'></i> Logging out...';setTimeout(()=>document.getElementById('logoutform').submit(),500);">
            <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
          </button>
        </div>
      </div>
    </div>
  </div>

</div>{{-- /wrapper --}}

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
@vite(['resources/js/app.js'])

<script>
  $(function () {
    let copyButtonTrans    = '{{ trans('global.datatables.copy') }}'
    let csvButtonTrans     = '{{ trans('global.datatables.csv') }}'
    let excelButtonTrans   = '{{ trans('global.datatables.excel') }}'
    let pdfButtonTrans     = '{{ trans('global.datatables.pdf') }}'
    let printButtonTrans   = '{{ trans('global.datatables.print') }}'
    let colvisButtonTrans  = '{{ trans('global.datatables.colvis') }}'
    let selectAllButtonTrans  = '{{ trans('global.select_all') }}'
    let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'
    let languages = { 'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json' };
    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn btn-sm' });
    $.extend(true, $.fn.dataTable.defaults, {
      language: { url: languages['{{ app()->getLocale() }}'] },
      columnDefs: [
        { orderable: false, className: 'select-checkbox text-center', targets: 0 },
        { orderable: false, searchable: false, targets: -1 }
      ],
      select: { style: 'multi+shift', selector: 'td:first-child' },
      order: [], scrollX: true, pageLength: 100,
      dom: '<"row mb-3 align-items-center custom-datatable-header"<"col-md-8 d-flex align-items-center gap-2 flex-wrap"B><"col-md-4 d-flex justify-content-end align-items-center gap-3"l>>rt<"row mt-3 align-items-center"<"col-md-6"i><"col-md-6"p>>',
      buttons: [
        { extend:'selectAll', className:'btn btn-primary me-1', text:'<i class="fas fa-check-square me-1"></i> '+selectAllButtonTrans, exportOptions:{columns:':visible:not(:first-child):not(:last-child)'}, action:function(e,dt){e.preventDefault();dt.rows().deselect();dt.rows({search:'applied'}).select();} },
        { extend:'selectNone', className:'btn btn-primary me-1', text:'<i class="fas fa-square me-1"></i> '+selectNoneButtonTrans, exportOptions:{columns:':visible:not(:first-child):not(:last-child)'} },
        { extend:'collection', text:'<i class="fas fa-ellipsis-h"></i> More', className:'btn btn-secondary',
          buttons:[
            { extend:'copy',   className:'dt-button dropdown-item d-flex align-items-center gap-2', text:'<i class="fas fa-copy text-primary"></i> '+copyButtonTrans,   exportOptions:{columns:':visible:not(:first-child):not(:last-child)'} },
            { extend:'csv',    className:'dt-button dropdown-item d-flex align-items-center gap-2', text:'<i class="fas fa-file-csv text-success"></i> '+csvButtonTrans,  exportOptions:{columns:':visible:not(:first-child):not(:last-child)'} },
            { extend:'excel',  className:'dt-button dropdown-item d-flex align-items-center gap-2', text:'<i class="fas fa-file-excel text-success"></i> '+excelButtonTrans, exportOptions:{columns:':visible:not(:first-child):not(:last-child)'} },
            { extend:'print',  className:'dt-button dropdown-item d-flex align-items-center gap-2', text:'<i class="fas fa-print text-secondary"></i> '+printButtonTrans,  exportOptions:{columns:':visible:not(:first-child):not(:last-child)'} },
            { extend:'colvis', className:'dt-button dropdown-item d-flex align-items-center gap-2', text:'<i class="fas fa-columns text-warning"></i> '+colvisButtonTrans, columns:':not(:first-child)', columnText:function(dt,idx,title){return title;}, collectionLayout:'fixed two-column' }
          ]
        }
      ]
    });
    $.fn.dataTable.ext.classes.sPageButton = '';
  });
</script>

@yield('scripts')

<script>
  document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('body').classList.toggle('c-sidebar-minimized');
  });
</script>

<script>
  /* ── AdminLTE bundle (unchanged) ── */
  !function(e,t){"object"==typeof exports&&"undefined"!=typeof module?t(exports):"function"==typeof define&&define.amd?define(["exports"],t):t(e.adminlte={})}(this,function(e){"use strict";var i,t,o,n,r,a,s,c,f,l,u,d,h,p,_,g,y,m,v,C,D,E,A,O,w,b,L,S,j,T,I,Q,R,P,x,B,M,k,H,N,Y,U,V,G,W,X,z,F,q,J,K,Z,$,ee,te,ne="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},ie=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")},oe=(i=jQuery,t="ControlSidebar",o="lte.control.sidebar",n=i.fn[t],r=".control-sidebar",a='[data-widget="control-sidebar"]',s=".main-header",c="control-sidebar-open",f="control-sidebar-slide-open",l={slide:!0},u=function(){function n(e,t){ie(this,n),this._element=e,this._config=this._getConfig(t)}return n.prototype.show=function(){this._config.slide?i("body").removeClass(f):i("body").removeClass(c)},n.prototype.collapse=function(){this._config.slide?i("body").addClass(f):i("body").addClass(c)},n.prototype.toggle=function(){this._setMargin(),i("body").hasClass(c)||i("body").hasClass(f)?this.show():this.collapse()},n.prototype._getConfig=function(e){return i.extend({},l,e)},n.prototype._setMargin=function(){i(r).css({top:i(s).outerHeight()})},n._jQueryInterface=function(t){return this.each(function(){var e=i(this).data(o);if(e||(e=new n(this,i(this).data()),i(this).data(o,e)),"undefined"===e[t])throw new Error(t+" is not a function");e[t]()})},n}(),i(document).on("click",a,function(e){e.preventDefault(),u._jQueryInterface.call(i(this),"toggle")}),i.fn[t]=u._jQueryInterface,i.fn[t].Constructor=u,i.fn[t].noConflict=function(){return i.fn[t]=n,u._jQueryInterface},u),re=(d=jQuery,h="Layout",p="lte.layout",_=d.fn[h],g=".main-sidebar",y=".main-header",m=".content-wrapper",v=".main-footer",C="hold-transition",D=function(){function n(e){ie(this,n),this._element=e,this._init()}return n.prototype.fixLayoutHeight=function(){var e={window:d(window).height(),header:d(y).outerHeight(),footer:d(v).outerHeight(),sidebar:d(g).height()},t=this._max(e);d(m).css("min-height",e.window-e.header-e.footer),d(g).css("min-height",e.window-e.header)},n.prototype._init=function(){var e=this;d("body").removeClass(C),this.fixLayoutHeight(),d(g).on("collapsed.lte.treeview expanded.lte.treeview collapsed.lte.pushmenu expanded.lte.pushmenu",function(){e.fixLayoutHeight()}),d(window).resize(function(){e.fixLayoutHeight()}),d("body, html").css("height","auto")},n.prototype._max=function(t){var n=0;return Object.keys(t).forEach(function(e){t[e]>n&&(n=t[e])}),n},n._jQueryInterface=function(t){return this.each(function(){var e=d(this).data(p);e||(e=new n(this),d(this).data(p,e)),t&&e[t]()})},n}(),d(window).on("load",function(){D._jQueryInterface.call(d("body"))}),d.fn[h]=D._jQueryInterface,d.fn[h].Constructor=D,d.fn[h].noConflict=function(){return d.fn[h]=_,D._jQueryInterface},D),ae=(E=jQuery,A="PushMenu",w="."+(O="lte.pushmenu"),b=E.fn[A],L={COLLAPSED:"collapsed"+w,SHOWN:"shown"+w},S={screenCollapseSize:768},j={TOGGLE_BUTTON:'[data-widget="pushmenu"]',SIDEBAR_MINI:".sidebar-mini",SIDEBAR_COLLAPSED:".sidebar-collapse",BODY:"body",OVERLAY:"#sidebar-overlay",WRAPPER:".wrapper"},T="sidebar-collapse",I="sidebar-open",Q=function(){function n(e,t){ie(this,n),this._element=e,this._options=E.extend({},S,t),E(j.OVERLAY).length||this._addOverlay()}return n.prototype.show=function(){E(j.BODY).addClass(I).removeClass(T);var e=E.Event(L.SHOWN);E(this._element).trigger(e)},n.prototype.collapse=function(){E(j.BODY).removeClass(I).addClass(T);var e=E.Event(L.COLLAPSED);E(this._element).trigger(e)},n.prototype.toggle=function(){(E(window).width()>=this._options.screenCollapseSize?!E(j.BODY).hasClass(T):E(j.BODY).hasClass(I))?this.collapse():this.show()},n.prototype._addOverlay=function(){var e=this,t=E("<div/>",{id:"sidebar-overlay"});t.on("click",function(){e.collapse()}),E(j.WRAPPER).append(t)},n._jQueryInterface=function(t){return this.each(function(){var e=E(this).data(O);e||(e=new n(this),E(this).data(O,e)),t&&e[t]()})},n}(),E(document).on("click",j.TOGGLE_BUTTON,function(e){e.preventDefault();var t=e.currentTarget;"pushmenu"!==E(t).data("widget")&&(t=E(t).closest(j.TOGGLE_BUTTON)),Q._jQueryInterface.call(E(t),"toggle")}),E.fn[A]=Q._jQueryInterface,E.fn[A].Constructor=Q,E.fn[A].noConflict=function(){return E.fn[A]=b,Q._jQueryInterface},Q),se=(R=jQuery,P="Treeview",B="."+(x="lte.treeview"),M=R.fn[P],k={SELECTED:"selected"+B,EXPANDED:"expanded"+B,COLLAPSED:"collapsed"+B,LOAD_DATA_API:"load"+B},H=".nav-item",N=".nav-treeview",Y=".menu-open",V="menu-open",G={trigger:(U='[data-widget="treeview"]')+" "+".nav-link",animationSpeed:300,accordion:!0},W=function(){function i(e,t){ie(this,i),this._config=t,this._element=e}return i.prototype.init=function(){this._setupListeners()},i.prototype.expand=function(e,t){var n=this,i=R.Event(k.EXPANDED);if(this._config.accordion){var o=t.siblings(Y).first(),r=o.find(N).first();this.collapse(r,o)}e.slideDown(this._config.animationSpeed,function(){t.addClass(V),R(n._element).trigger(i)})},i.prototype.collapse=function(e,t){var n=this,i=R.Event(k.COLLAPSED);e.slideUp(this._config.animationSpeed,function(){t.removeClass(V),R(n._element).trigger(i),e.find(Y+" > "+N).slideUp(),e.find(Y).removeClass(V)})},i.prototype.toggle=function(e){var t=R(e.currentTarget),n=t.next();if(n.is(N)){e.preventDefault();var i=t.parents(H).first();i.hasClass(V)?this.collapse(R(n),i):this.expand(R(n),i)}},i.prototype._setupListeners=function(){var t=this;R(document).on("click",this._config.trigger,function(e){t.toggle(e)})},i._jQueryInterface=function(n){return this.each(function(){var e=R(this).data(x),t=R.extend({},G,R(this).data());e||(e=new i(R(this),t),R(this).data(x,e)),"init"===n&&e[n]()})},i}(),R(window).on(k.LOAD_DATA_API,function(){R(U).each(function(){W._jQueryInterface.call(R(this),"init")})}),R.fn[P]=W._jQueryInterface,R.fn[P].Constructor=W,R.fn[P].noConflict=function(){return R.fn[P]=M,W._jQueryInterface},W),ce=(X=jQuery,z="Widget",q="."+(F="lte.widget"),J=X.fn[z],K={EXPANDED:"expanded"+q,COLLAPSED:"collapsed"+q,REMOVED:"removed"+q},$="collapsed-card",ee={animationSpeed:"normal",collapseTrigger:(Z={DATA_REMOVE:'[data-widget="remove"]',DATA_COLLAPSE:'[data-widget="collapse"]',CARD:".card",CARD_HEADER:".card-header",CARD_BODY:".card-body",CARD_FOOTER:".card-footer",COLLAPSED:".collapsed-card"}).DATA_COLLAPSE,removeTrigger:Z.DATA_REMOVE},te=function(){function n(e,t){ie(this,n),this._element=e,this._parent=e.parents(Z.CARD).first(),this._settings=X.extend({},ee,t)}return n.prototype.collapse=function(){var e=this;this._parent.children(Z.CARD_BODY+", "+Z.CARD_FOOTER).slideUp(this._settings.animationSpeed,function(){e._parent.addClass($)});var t=X.Event(K.COLLAPSED);this._element.trigger(t,this._parent)},n.prototype.expand=function(){var e=this;this._parent.children(Z.CARD_BODY+", "+Z.CARD_FOOTER).slideDown(this._settings.animationSpeed,function(){e._parent.removeClass($)});var t=X.Event(K.EXPANDED);this._element.trigger(t,this._parent)},n.prototype.remove=function(){this._parent.slideUp();var e=X.Event(K.REMOVED);this._element.trigger(e,this._parent)},n.prototype.toggle=function(){this._parent.hasClass($)?this.expand():this.collapse()},n.prototype._init=function(e){var t=this;this._parent=e,X(this).find(this._settings.collapseTrigger).click(function(){t.toggle()}),X(this).find(this._settings.removeTrigger).click(function(){t.remove()})},n._jQueryInterface=function(t){return this.each(function(){var e=X(this).data(F);e||(e=new n(X(this),e),X(this).data(F,"string"==typeof t?e:t)),"string"==typeof t&&t.match(/remove|toggle/)?e[t]():"object"===("undefined"==typeof t?"undefined":ne(t))&&e._init(X(this))})},n}(),X(document).on("click",Z.DATA_COLLAPSE,function(e){e&&e.preventDefault(),te._jQueryInterface.call(X(this),"toggle")}),X(document).on("click",Z.DATA_REMOVE,function(e){e&&e.preventDefault(),te._jQueryInterface.call(X(this),"remove")}),X.fn[z]=te._jQueryInterface,X.fn[z].Constructor=te,X.fn[z].noConflict=function(){return X.fn[z]=J,te._jQueryInterface},te);e.ControlSidebar=oe,e.Layout=re,e.PushMenu=ae,e.Treeview=se,e.Widget=ce,Object.defineProperty(e,"__esModule",{value:!0})});
</script>

<script>
  /* ── Loading overlay ── */
  document.addEventListener("DOMContentLoaded", function () {
    const loader = document.getElementById("loading-overlay");
    document.querySelectorAll(".ws-nav-item, .nav-link").forEach(item => {
      item.addEventListener("click", function (e) {
        const href = this.getAttribute("href");
        if (href && href !== "#" && !this.hasAttribute("data-bs-toggle") && !this.hasAttribute("data-widget")) {
          loader.style.display = "flex";
          loader.style.opacity = "1";
        }
      });
    });
    window.addEventListener("load", function () {
      loader.style.opacity = "0";
      setTimeout(() => loader.style.display = "none", 500);
    });

    /* ── Dark mode ── */
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.classList.toggle('dark-mode', savedTheme === 'dark');

    /* ── Breadcrumb auto-fill ── */
    const pageTitleEl = document.getElementById('ws-page-title');
    if (pageTitleEl) {
      const h1 = document.querySelector('h1, .pr-hero-title, .page-title');
      if (h1) pageTitleEl.textContent = h1.textContent.trim();
      else pageTitleEl.textContent = document.title.replace(' | WeServe','').replace('WeServe','').trim();
    }

    /* ── Toast ── */
    const toastEl = document.getElementById('liveToast');
    if (toastEl) {
      new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
      const timerEl = document.getElementById('toast-timer');
      if (timerEl) {
        let s = 0;
        setInterval(() => { s++; timerEl.textContent = s < 60 ? `${s}s ago` : `${Math.floor(s/60)}m ago`; }, 1000);
      }
    }
  });

  /* ═══════════════════════════════════════════
     NOTIFICATION PANEL TOGGLE
     ═══════════════════════════════════════════ */
  const wsNotifToggle  = document.getElementById('wsNotifToggle');
  const wsNotifPanel   = document.getElementById('wsNotifPanel');
  const wsNotifBackdrop = document.getElementById('wsNotifBackdrop');

  function wsOpenNotif() {
    wsNotifPanel.classList.add('ws-open');
    wsNotifBackdrop.classList.add('ws-open');
    wsNotifToggle.classList.add('ws-active-bell');
  }
  function wsCloseNotif() {
    wsNotifPanel.classList.remove('ws-open');
    wsNotifBackdrop.classList.remove('ws-open');
    wsNotifToggle.classList.remove('ws-active-bell');
  }

  wsNotifToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    wsNotifPanel.classList.contains('ws-open') ? wsCloseNotif() : wsOpenNotif();
  });
  wsNotifBackdrop?.addEventListener('click', wsCloseNotif);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') wsCloseNotif(); });
</script>

<script>
/* ═══════════════════════════════════════════
   NOTIFICATION MANAGER (all functionality preserved)
   ═══════════════════════════════════════════ */
class NotificationManager {
  constructor() {
    this.notificationBadge = document.getElementById('notification-badge');
    this.notificationCount = document.getElementById('notification-count');
    this.notificationItemsContainer = document.getElementById('notification-items');
    this.departmentFilter = document.getElementById('department-filter');
    this.clearFilterBtn   = document.getElementById('clear-filter');
    this.loadMoreBtn      = document.getElementById('load-more-notifications');
    this.loadMoreContainer = document.getElementById('load-more-container');
    this.notificationStats = document.getElementById('notification-stats');
    this.shownCount = document.getElementById('shown-count');
    this.totalCount = document.getElementById('total-count');
    this.currentFilter = '';
    this.currentPage   = 1;
    this.hasMore       = false;
    this.isLoading     = false;
    this.notifications = [];
    this.shownNotifications = 0;
    this.totalNotifications = 0;
    this.init();
  }

  init() { this.loadNotifications(); this.setupEventListeners(); this.startPolling(); }

  setupEventListeners() {
    document.addEventListener('click', (e) => {
      const item = e.target.closest('.ws-notif-item');
      if (!item) return;
      e.preventDefault();
      this.markAsRead(item.dataset.id);
      if (item.dataset.patientId) window.location.href = `/admin/process-tracking/${item.dataset.patientId}`;
    });
    this.departmentFilter?.addEventListener('change', (e) => this.resetAndLoad(e.target.value));
    this.clearFilterBtn?.addEventListener('click', () => this.clearFilter());
    this.loadMoreBtn?.addEventListener('click', () => this.loadMore());
    document.getElementById('mark-all-read')?.addEventListener('click', () => this.markAllAsRead());
    const container = document.querySelector('.ws-notif-scroll');
    if (container) {
      container.addEventListener('scroll', () => {
        const { scrollTop, scrollHeight, clientHeight } = container;
        if (scrollTop + clientHeight >= scrollHeight - 10 && this.hasMore && !this.isLoading) this.loadMore();
      });
    }
  }

  resetAndLoad(filterValue = '') {
    this.currentFilter = filterValue;
    this.currentPage   = 1;
    this.notifications = [];
    this.shownNotifications = 0;
    if (this.departmentFilter) this.departmentFilter.value = filterValue;
    if (this.clearFilterBtn)   this.clearFilterBtn.style.display = filterValue ? 'flex' : 'none';
    this.loadMoreContainer.style.display = 'none';
    this.loadNotifications();
  }

  clearFilter() { this.resetAndLoad(''); }

  async loadNotifications(isLoadMore = false) {
    if (this.isLoading) return;
    this.isLoading = true;
    try {
      const params = new URLSearchParams();
      if (this.currentFilter) params.append('department', this.currentFilter);
      if (isLoadMore) { params.append('load_more', 'true'); params.append('page', this.currentPage); }
      if (!isLoadMore) {
        this.notificationItemsContainer.innerHTML = `<div class="ws-notif-state"><div class="spinner-border text-success spinner-border-sm" role="status"></div><div style="margin-top:8px;font-size:.76rem;">Loading…</div></div>`;
        this.loadMoreContainer.style.display = 'none';
      } else {
        this.loadMoreBtn.disabled = true;
        this.loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:.7rem;"></i> Loading…';
      }
      const response = await fetch(`/admin/notifications/list?${params}`);
      const data = await response.json();
      this.updateNotificationBadge(data.notifications);
      this.renderNotifications(data.notifications, isLoadMore);
      this.hasMore = data.has_more;
      this.totalNotifications = data.total;
      this.shownNotifications = this.notifications.length;
      this.currentPage = data.next_page ? data.current_page + 1 : data.current_page;
      this.updateStats();
      if (this.hasMore) {
        this.loadMoreContainer.style.display = 'block';
        this.loadMoreBtn.disabled = false;
        this.loadMoreBtn.innerHTML = '<i class="fas fa-arrow-down" style="font-size:.7rem;"></i> Load More';
      } else {
        this.loadMoreContainer.style.display = 'none';
      }
    } catch (err) {
      console.error('Failed to load notifications:', err);
      this.showErrorMessage();
    } finally { this.isLoading = false; }
  }

  loadMore() { if (this.hasMore && !this.isLoading) this.loadNotifications(true); }

  updateNotificationBadge(notifications) {
    const count = notifications.filter(n => !n.is_read).length;
    if (this.notificationBadge) { this.notificationBadge.textContent = count; this.notificationBadge.style.display = count > 0 ? 'flex' : 'none'; }
    if (this.notificationCount) { this.notificationCount.textContent = `${count} new`; this.notificationCount.style.display = count > 0 ? 'inline-flex' : 'none'; }
  }

  updateStats() {
    if (this.shownCount) this.shownCount.textContent = this.shownNotifications;
    if (this.totalCount) this.totalCount.textContent = this.totalNotifications;
    if (this.notificationStats) this.notificationStats.style.display = 'block';
  }

  renderNotifications(notifications, append = false) {
    if (!append) { this.notificationItemsContainer.innerHTML = ''; this.notifications = []; }
    if (notifications.length === 0 && !append) { this.showEmptyMessage(); return; }
    notifications.forEach(n => this.notifications.push(n));
    this.notificationItemsContainer.innerHTML = '';
    this.notifications.forEach(n => {
      this.notificationItemsContainer.insertAdjacentHTML('beforeend', this.createNotificationElement(n));
    });
  }

  createNotificationElement(n) {
    const unread = !n.is_read ? 'ws-unread' : '';
    const colorMap = { success:'#10b981', primary:'#3b82f6', warning:'#f59e0b', danger:'#ef4444', info:'#0ea5e9', secondary:'#6b7280' };
    const bg  = colorMap[n.icon_color] || '#6b7280';
    const dot = !n.is_read ? '<span class="ws-unread-dot"></span>' : '';
    return `
      <a href="/admin/process-tracking/${n.patient_id}"
         class="ws-notif-item ${unread}"
         data-id="${n.id}" data-patient-id="${n.patient_id}">
        <div class="ws-notif-item-icon" style="background:${bg}20;border:1px solid ${bg}40;">
          <i class="${this.escapeHtml(n.icon)}" style="color:${bg};"></i>
        </div>
        <div class="ws-notif-item-body">
          <div class="ws-notif-item-top">
            <div class="ws-notif-item-title">${this.escapeHtml(n.title)}</div>
            <div class="ws-notif-item-time">${this.escapeHtml(n.time_ago)}</div>
          </div>
          <div class="ws-notif-item-msg">${this.escapeHtml(n.message)}</div>
          <div class="ws-notif-item-meta">
            <span class="ws-notif-item-by">By: ${this.escapeHtml(n.user_name)}</span>
            <span class="ws-notif-dept-tag">${this.escapeHtml(n.department)}</span>
          </div>
        </div>
        ${dot}
      </a>`;
  }

  showEmptyMessage() {
    this.notificationItemsContainer.innerHTML = `
      <div class="ws-notif-state">
        <i class="fas fa-bell-slash" style="color:#d1fae5;"></i>
        ${this.currentFilter ? `No notifications for <strong>${this.currentFilter}</strong>` : 'You\'re all caught up!'}
        ${this.currentFilter ? `<br><button class="ws-notif-footer-btn" style="margin:8px auto 0;font-size:.72rem;" onclick="notificationManager.clearFilter()"><i class="fas fa-eye" style="font-size:.65rem;"></i> Show all</button>` : ''}
      </div>`;
    if (this.notificationStats) this.notificationStats.style.display = 'none';
    this.loadMoreContainer.style.display = 'none';
  }

  showErrorMessage() {
    this.notificationItemsContainer.innerHTML = `
      <div class="ws-notif-state">
        <i class="fas fa-exclamation-triangle" style="color:#fbbf24;"></i>
        Failed to load notifications
        <br><button class="ws-notif-footer-btn" style="margin:8px auto 0;font-size:.72rem;" onclick="notificationManager.loadNotifications()"><i class="fas fa-redo" style="font-size:.65rem;"></i> Retry</button>
      </div>`;
  }

  escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString().replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;");
  }

  async markAsRead(id) {
    try {
      await fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      });
      const el = document.querySelector(`.ws-notif-item[data-id="${id}"]`);
      if (el) el.classList.remove('ws-unread');
      const n = this.notifications.find(n => n.id == id);
      if (n) n.is_read = true;
      this.updateNotificationBadge(this.notifications);
    } catch(e) { console.error('markAsRead error:', e); }
  }

  async markAllAsRead() {
    try {
      await fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      });
      document.querySelectorAll('.ws-notif-item').forEach(el => el.classList.remove('ws-unread'));
      document.querySelectorAll('.ws-unread-dot').forEach(d => d.remove());
      this.notifications.forEach(n => n.is_read = true);
      this.updateNotificationBadge(this.notifications);
    } catch(e) { console.error('markAllAsRead error:', e); }
  }

  startPolling() { setInterval(() => this.updateUnreadCount(), 30000); }

  async updateUnreadCount() {
    try {
      const data = await (await fetch('/admin/notifications/unread-count')).json();
      if (this.notificationBadge) { this.notificationBadge.textContent = data.count; this.notificationBadge.style.display = data.count > 0 ? 'flex' : 'none'; }
      if (this.notificationCount) { this.notificationCount.textContent = `${data.count} new`; this.notificationCount.style.display = data.count > 0 ? 'inline-flex' : 'none'; }
    } catch(e) { console.error('updateUnreadCount error:', e); }
  }
}

let notificationManager;
document.addEventListener('DOMContentLoaded', function () {
  notificationManager = new NotificationManager();
});
</script>

@stack('scripts')

{{-- Context Menu --}}
<div id="contextMenu" class="context-menu">
  <div class="context-menu-header">Actions</div>
  <div class="context-menu-item" data-action="view"><i class="fas fa-eye"></i><span>View Details</span></div>
  <div class="context-menu-divider"></div>
  <div class="context-menu-item" data-action="approve"><i class="fas fa-check-circle text-success"></i><span>Approve</span></div>
  <div class="context-menu-item" data-action="reject"><i class="fas fa-times-circle text-danger"></i><span>Reject</span></div>
  <div class="context-menu-divider"></div>
  <div class="context-menu-item" data-action="budget"><i class="fas fa-money-bill-wave text-warning"></i><span>Allocate Budget</span></div>
  <div class="context-menu-item" data-action="dv"><i class="fas fa-file-invoice text-info"></i><span>Submit DV</span></div>
  <div class="context-menu-divider"></div>
  <div class="context-menu-item" data-action="ready"><i class="fas fa-exclamation-circle text-warning"></i><span>Ready for Disbursement</span></div>
  <div class="context-menu-item" data-action="disburse"><i class="fas fa-money-bill-wave text-success"></i><span>Quick Disburse</span></div>
</div>

</body>
</html>