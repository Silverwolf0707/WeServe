<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>WeServe</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png+xml" href="{{ asset('logo.png') }}">

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (latest 5.x) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

  <!-- DateTime Picker -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet">

  <!-- DataTables Core + Plugins -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">

  <!-- Dropzone -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">

  <!-- AdminLTE and Custom Styles -->
  <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  @if(app()->isLocal())
    @vite(['resources/js/app.js', 'resources/css/app.css'])
@else
    <!-- Production assets -->
    <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
    <script src="{{ asset('resources/js/app.js') }}" defer></script>
@endif


  @yield('styles')
</head>
<script>
  document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('body').classList.toggle('c-sidebar-minimized');
  });
</script>


@if(session('toast'))
  @php
    $toast = session('toast');
    $bgClass = match ($toast['type']) {
        'success' => 'toast-success',
        'danger' => 'toast-danger',
        'warning' => 'toast-warning',
        'info' => 'toast-info',
        default => 'bg-secondary',
    };
    
    $icons = [
        'success' => 'fas fa-check-circle',
        'danger' => 'fas fa-exclamation-triangle',
        'warning' => 'fas fa-exclamation-circle',
        'info' => 'fas fa-info-circle'
    ];
    $icon = $icons[$toast['type']] ?? 'fas fa-bell';
  @endphp

  <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="liveToast" class="toast custom-toast {{ $bgClass }}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-progress"></div>
      <div class="toast-header bg-white text-dark">
        <i class="{{ $icon }} toast-icon text-{{ $toast['type'] }}"></i>
        <strong class="me-auto">{{ $toast['title'] ?? 'Notification' }}</strong>
        <small class="text-muted" id="toast-timer">Just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        {!! session('toast')['message'] !!}
      </div>
    </div>
  </div>
@endif


<body class="sidebar-mini layout-fixed" style="height: auto;">
      <!-- Loading Screen -->
    <div id="loading-overlay">
        <div class="loader-wrapper">
            <div class="circle"></div>
            <div class="loader-text">WS</div>
        </div>
    </div>
  <div class="wrapper">
<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
    </ul>

<!-- Right navbar links -->
<ul class="navbar-nav ms-auto">
    <!-- Notification Bell -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" id="notificationDropdown">
            <i class="fa fa-bell"></i>
            <!-- Notification Badge -->
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notification-badge" style="display: none;">
                0
                <span class="visually-hidden">unread notifications</span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg notification-dropdown" style="width: 400px;">
            <!-- Dropdown Header -->
            <div class="dropdown-header bg-light py-3 notification-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications<span class="beta-tag">BETA</span></h6>
                    <div>
                        <span class="badge bg-primary" id="notification-count" style="display: none;">0 new</span>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="refresh-notifications" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Add this right after the notification header div -->
<div class="dropdown-header bg-light py-2 border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">Filter by Department</small>
        <button class="btn btn-sm btn-outline-secondary" id="clear-filter" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<div class="px-3 py-2">
    <select class="form-select form-select-sm" id="department-filter">
        <option value="">All Departments</option>
        <option value="CSWD Office">CSWD Office</option>
        <option value="Mayor's Office">Mayor's Office</option>
        <option value="Budget Office">Budget Office</option>
        <option value="Accounting Office">Accounting Office</option>
        <option value="Treasury Office">Treasury Office</option>
    </select>
</div>
            
            <!-- Notification Items Container with Scroll -->
            <div class="notification-items-container">
                <div class="notification-items">
                    <!-- Notifications will be dynamically loaded here -->
                    <div class="dropdown-item text-center py-4 notification-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        
                    </div>
                </div>
            </div>
            
           
            <div class="dropdown-divider"></div>
            <div class="d-flex justify-content-between px-3 py-2 notification-actions">
                
                <button class="btn btn-sm btn-outline-secondary" id="mark-all-read">
                    <i class="fas fa-check-double me-1"></i> Mark All Read
                </button>
            </div>
        </div>
    </li>
</ul>
</nav>

      <!-- Right navbar links -->
      @if(count(config('panel.available_languages', [])) > 1)
      <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
        {{ strtoupper(app()->getLocale()) }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
        @foreach(config('panel.available_languages') as $langLocale => $langName)
      <a class="dropdown-item"
        href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }}
        ({{ $langName }})</a>
      @endforeach
        </div>
      </li>
      </ul>
    @endif

    </nav>

    @include('partials.menu')
    @include('partials.profile')

    <div class="content-wrapper" style="min-height: 917px;">
      <!-- Main content -->
      <section class="content" style="padding-top: 20px">
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
      <!-- /.content -->
    </div>
    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
    <!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt text-danger me-2"></i>
                    Confirm Logout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout?</p>
                <p class="text-muted small">You will need to login again to access the system.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" 
                        onclick="
                            const btn = this;
                            btn.disabled = true;
                            btn.innerHTML = '<i class=\'fas fa-spinner fa-spin me-1\'></i> Logging out...';
                            setTimeout(() => {
                                document.getElementById('logoutform').submit();
                            }, 500);
                        ">
                    <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
                </button>
            </div>
        </div>
    </div>
</div>
  </div>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- Bootstrap 5 Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Moment.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

  <!-- Select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

  <!-- DateTime Picker -->
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

  <!-- Dropzone -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

  <!-- DataTables Core + Bootstrap 5 Integration -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <!-- DataTables Extensions -->
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

  <!-- Dependencies for DataTables export -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <!-- CKEditor -->
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


  <script src="{{ asset('js/main.js') }}"></script>

  {{-- <script>
    $(document).ready(function () {
      $('.suggested-amount').on('click', function () {
        const value = $(this).data('value');
        $('#amount').val(value); 
      });
    });
  </script> --}}

  <script>
    $(function () {
      let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
      let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
      let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
      let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
      let printButtonTrans = '{{ trans('global.datatables.print') }}'
      let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
      let selectAllButtonTrans = '{{ trans('global.select_all') }}'
      let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

      let languages = {
        'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
      };

      $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn btn-sm' });

      $.extend(true, $.fn.dataTable.defaults, {
        language: {
          url: languages['{{ app()->getLocale() }}']
        },
        columnDefs: [
          {
            orderable: false,
            className: 'select-checkbox text-center',
            targets: 0
          },
          {
            orderable: false,
            searchable: false,
            targets: -1
          }
        ],
        select: {
          style: 'multi+shift',
          selector: 'td:first-child'
        },
        order: [],
        scrollX: true,
        pageLength: 100,

        dom:
          '<"row mb-3 align-items-center"' +
          '<"col-md-6 d-flex align-items-center gap-2"lB>' +
          '<"col-md-6 d-flex justify-content-end"f>' +
          '>rtip',

        buttons: [
          {
            extend: 'selectAll',
            className: 'btn btn-primary me-1',
            text: '<i class="fas fa-check-square me-1"></i> ' + selectAllButtonTrans,
            exportOptions: {
              columns: ':visible:not(:first-child):not(:last-child)'
            },
            action: function (e, dt) {
              e.preventDefault();
              dt.rows().deselect();
              dt.rows({ search: 'applied' }).select();
            }
          },
          {
            extend: 'selectNone',
            className: 'btn btn-primary me-1',
            text: '<i class="fas fa-square me-1"></i> ' + selectNoneButtonTrans,
            exportOptions: {
              columns: ':visible:not(:first-child):not(:last-child)'
            }
          },
          {
            extend: 'collection',
            text: '<i class="fas fa-ellipsis-h"></i> More',
            className: 'btn btn-secondary',
            buttons: [
              {
                extend: 'copy',
                className: 'dt-button dropdown-item d-flex align-items-center gap-2',
                text: '<i class="fas fa-copy text-primary"></i> ' + copyButtonTrans,
                exportOptions: {
                  columns: ':visible:not(:first-child):not(:last-child)'
                }
              },
              {
                extend: 'csv',
                className: 'dt-button dropdown-item d-flex align-items-center gap-2',
                text: '<i class="fas fa-file-csv text-success"></i> ' + csvButtonTrans,
                exportOptions: {
                  columns: ':visible:not(:first-child):not(:last-child)'
                }
              },
              {
                extend: 'excel',
                className: 'dt-button dropdown-item d-flex align-items-center gap-2',
                text: '<i class="fas fa-file-excel text-success"></i> ' + excelButtonTrans,
                exportOptions: {
                  columns: ':visible:not(:first-child):not(:last-child)'
                }
              },
              {
                extend: 'print',
                className: 'dt-button dropdown-item d-flex align-items-center gap-2',
                text: '<i class="fas fa-print text-secondary"></i> ' + printButtonTrans,
                exportOptions: {
                  columns: ':visible:not(:first-child):not(:last-child)'
                }
              },
              {
                extend: 'colvis',
                className: 'dt-button dropdown-item d-flex align-items-center gap-2',
                text: '<i class="fas fa-columns text-warning"></i> ' + colvisButtonTrans,
                columns: ':not(:first-child)', 
                columnText: function (dt, idx, title) {
                  return title; 
                },
                collectionLayout: 'fixed two-column'
              }

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

    !function (e, t) { "object" == typeof exports && "undefined" != typeof module ? t(exports) : "function" == typeof define && define.amd ? define(["exports"], t) : t(e.adminlte = {}) }(this, function (e) { "use strict"; var i, t, o, n, r, a, s, c, f, l, u, d, h, p, _, g, y, m, v, C, D, E, A, O, w, b, L, S, j, T, I, Q, R, P, x, B, M, k, H, N, Y, U, V, G, W, X, z, F, q, J, K, Z, $, ee, te, ne = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) { return typeof e } : function (e) { return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e }, ie = function (e, t) { if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function") }, oe = (i = jQuery, t = "ControlSidebar", o = "lte.control.sidebar", n = i.fn[t], r = ".control-sidebar", a = '[data-widget="control-sidebar"]', s = ".main-header", c = "control-sidebar-open", f = "control-sidebar-slide-open", l = { slide: !0 }, u = function () { function n(e, t) { ie(this, n), this._element = e, this._config = this._getConfig(t) } return n.prototype.show = function () { this._config.slide ? i("body").removeClass(f) : i("body").removeClass(c) }, n.prototype.collapse = function () { this._config.slide ? i("body").addClass(f) : i("body").addClass(c) }, n.prototype.toggle = function () { this._setMargin(), i("body").hasClass(c) || i("body").hasClass(f) ? this.show() : this.collapse() }, n.prototype._getConfig = function (e) { return i.extend({}, l, e) }, n.prototype._setMargin = function () { i(r).css({ top: i(s).outerHeight() }) }, n._jQueryInterface = function (t) { return this.each(function () { var e = i(this).data(o); if (e || (e = new n(this, i(this).data()), i(this).data(o, e)), "undefined" === e[t]) throw new Error(t + " is not a function"); e[t]() }) }, n }(), i(document).on("click", a, function (e) { e.preventDefault(), u._jQueryInterface.call(i(this), "toggle") }), i.fn[t] = u._jQueryInterface, i.fn[t].Constructor = u, i.fn[t].noConflict = function () { return i.fn[t] = n, u._jQueryInterface }, u), re = (d = jQuery, h = "Layout", p = "lte.layout", _ = d.fn[h], g = ".main-sidebar", y = ".main-header", m = ".content-wrapper", v = ".main-footer", C = "hold-transition", D = function () { function n(e) { ie(this, n), this._element = e, this._init() } return n.prototype.fixLayoutHeight = function () { var e = { window: d(window).height(), header: d(y).outerHeight(), footer: d(v).outerHeight(), sidebar: d(g).height() }, t = this._max(e); d(m).css("min-height", e.window - e.header - e.footer), d(g).css("min-height", e.window - e.header) }, n.prototype._init = function () { var e = this; d("body").removeClass(C), this.fixLayoutHeight(), d(g).on("collapsed.lte.treeview expanded.lte.treeview collapsed.lte.pushmenu expanded.lte.pushmenu", function () { e.fixLayoutHeight() }), d(window).resize(function () { e.fixLayoutHeight() }), d("body, html").css("height", "auto") }, n.prototype._max = function (t) { var n = 0; return Object.keys(t).forEach(function (e) { t[e] > n && (n = t[e]) }), n }, n._jQueryInterface = function (t) { return this.each(function () { var e = d(this).data(p); e || (e = new n(this), d(this).data(p, e)), t && e[t]() }) }, n }(), d(window).on("load", function () { D._jQueryInterface.call(d("body")) }), d.fn[h] = D._jQueryInterface, d.fn[h].Constructor = D, d.fn[h].noConflict = function () { return d.fn[h] = _, D._jQueryInterface }, D), ae = (E = jQuery, A = "PushMenu", w = "." + (O = "lte.pushmenu"), b = E.fn[A], L = { COLLAPSED: "collapsed" + w, SHOWN: "shown" + w }, S = { screenCollapseSize: 768 }, j = { TOGGLE_BUTTON: '[data-widget="pushmenu"]', SIDEBAR_MINI: ".sidebar-mini", SIDEBAR_COLLAPSED: ".sidebar-collapse", BODY: "body", OVERLAY: "#sidebar-overlay", WRAPPER: ".wrapper" }, T = "sidebar-collapse", I = "sidebar-open", Q = function () { function n(e, t) { ie(this, n), this._element = e, this._options = E.extend({}, S, t), E(j.OVERLAY).length || this._addOverlay() } return n.prototype.show = function () { E(j.BODY).addClass(I).removeClass(T); var e = E.Event(L.SHOWN); E(this._element).trigger(e) }, n.prototype.collapse = function () { E(j.BODY).removeClass(I).addClass(T); var e = E.Event(L.COLLAPSED); E(this._element).trigger(e) }, n.prototype.toggle = function () { (E(window).width() >= this._options.screenCollapseSize ? !E(j.BODY).hasClass(T) : E(j.BODY).hasClass(I)) ? this.collapse() : this.show() }, n.prototype._addOverlay = function () { var e = this, t = E("<div />", { id: "sidebar-overlay" }); t.on("click", function () { e.collapse() }), E(j.WRAPPER).append(t) }, n._jQueryInterface = function (t) { return this.each(function () { var e = E(this).data(O); e || (e = new n(this), E(this).data(O, e)), t && e[t]() }) }, n }(), E(document).on("click", j.TOGGLE_BUTTON, function (e) { e.preventDefault(); var t = e.currentTarget; "pushmenu" !== E(t).data("widget") && (t = E(t).closest(j.TOGGLE_BUTTON)), Q._jQueryInterface.call(E(t), "toggle") }), E.fn[A] = Q._jQueryInterface, E.fn[A].Constructor = Q, E.fn[A].noConflict = function () { return E.fn[A] = b, Q._jQueryInterface }, Q), se = (R = jQuery, P = "Treeview", B = "." + (x = "lte.treeview"), M = R.fn[P], k = { SELECTED: "selected" + B, EXPANDED: "expanded" + B, COLLAPSED: "collapsed" + B, LOAD_DATA_API: "load" + B }, H = ".nav-item", N = ".nav-treeview", Y = ".menu-open", V = "menu-open", G = { trigger: (U = '[data-widget="treeview"]') + " " + ".nav-link", animationSpeed: 300, accordion: !0 }, W = function () { function i(e, t) { ie(this, i), this._config = t, this._element = e } return i.prototype.init = function () { this._setupListeners() }, i.prototype.expand = function (e, t) { var n = this, i = R.Event(k.EXPANDED); if (this._config.accordion) { var o = t.siblings(Y).first(), r = o.find(N).first(); this.collapse(r, o) } e.slideDown(this._config.animationSpeed, function () { t.addClass(V), R(n._element).trigger(i) }) }, i.prototype.collapse = function (e, t) { var n = this, i = R.Event(k.COLLAPSED); e.slideUp(this._config.animationSpeed, function () { t.removeClass(V), R(n._element).trigger(i), e.find(Y + " > " + N).slideUp(), e.find(Y).removeClass(V) }) }, i.prototype.toggle = function (e) { var t = R(e.currentTarget), n = t.next(); if (n.is(N)) { e.preventDefault(); var i = t.parents(H).first(); i.hasClass(V) ? this.collapse(R(n), i) : this.expand(R(n), i) } }, i.prototype._setupListeners = function () { var t = this; R(document).on("click", this._config.trigger, function (e) { t.toggle(e) }) }, i._jQueryInterface = function (n) { return this.each(function () { var e = R(this).data(x), t = R.extend({}, G, R(this).data()); e || (e = new i(R(this), t), R(this).data(x, e)), "init" === n && e[n]() }) }, i }(), R(window).on(k.LOAD_DATA_API, function () { R(U).each(function () { W._jQueryInterface.call(R(this), "init") }) }), R.fn[P] = W._jQueryInterface, R.fn[P].Constructor = W, R.fn[P].noConflict = function () { return R.fn[P] = M, W._jQueryInterface }, W), ce = (X = jQuery, z = "Widget", q = "." + (F = "lte.widget"), J = X.fn[z], K = { EXPANDED: "expanded" + q, COLLAPSED: "collapsed" + q, REMOVED: "removed" + q }, $ = "collapsed-card", ee = { animationSpeed: "normal", collapseTrigger: (Z = { DATA_REMOVE: '[data-widget="remove"]', DATA_COLLAPSE: '[data-widget="collapse"]', CARD: ".card", CARD_HEADER: ".card-header", CARD_BODY: ".card-body", CARD_FOOTER: ".card-footer", COLLAPSED: ".collapsed-card" }).DATA_COLLAPSE, removeTrigger: Z.DATA_REMOVE }, te = function () { function n(e, t) { ie(this, n), this._element = e, this._parent = e.parents(Z.CARD).first(), this._settings = X.extend({}, ee, t) } return n.prototype.collapse = function () { var e = this; this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideUp(this._settings.animationSpeed, function () { e._parent.addClass($) }); var t = X.Event(K.COLLAPSED); this._element.trigger(t, this._parent) }, n.prototype.expand = function () { var e = this; this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideDown(this._settings.animationSpeed, function () { e._parent.removeClass($) }); var t = X.Event(K.EXPANDED); this._element.trigger(t, this._parent) }, n.prototype.remove = function () { this._parent.slideUp(); var e = X.Event(K.REMOVED); this._element.trigger(e, this._parent) }, n.prototype.toggle = function () { this._parent.hasClass($) ? this.expand() : this.collapse() }, n.prototype._init = function (e) { var t = this; this._parent = e, X(this).find(this._settings.collapseTrigger).click(function () { t.toggle() }), X(this).find(this._settings.removeTrigger).click(function () { t.remove() }) }, n._jQueryInterface = function (t) { return this.each(function () { var e = X(this).data(F); e || (e = new n(X(this), e), X(this).data(F, "string" == typeof t ? e : t)), "string" == typeof t && t.match(/remove|toggle/) ? e[t]() : "object" === ("undefined" == typeof t ? "undefined" : ne(t)) && e._init(X(this)) }) }, n }(), X(document).on("click", Z.DATA_COLLAPSE, function (e) { e && e.preventDefault(), te._jQueryInterface.call(X(this), "toggle") }), X(document).on("click", Z.DATA_REMOVE, function (e) { e && e.preventDefault(), te._jQueryInterface.call(X(this), "remove") }), X.fn[z] = te._jQueryInterface, X.fn[z].Constructor = te, X.fn[z].noConflict = function () { return X.fn[z] = J, te._jQueryInterface }, te); e.ControlSidebar = oe, e.Layout = re, e.PushMenu = ae, e.Treeview = se, e.Widget = ce, Object.defineProperty(e, "__esModule", { value: !0 }) });


  </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById("loading-overlay");

            document.querySelectorAll(".nav-link").forEach(item => {
                item.addEventListener("click", function(e) {
                    const href = this.getAttribute("href");

                    if (href && href !== "#" && !this.hasAttribute("data-bs-toggle")) {
                        loader.style.display = "flex";
                        loader.style.opacity = "1";
                    }
                });
            });

            // Hide loader after page load
            window.addEventListener("load", function() {
                loader.style.opacity = "0";
                setTimeout(() => loader.style.display = "none", 500);
            });
        });

           document.addEventListener('DOMContentLoaded', function () {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.classList.toggle('dark-mode', savedTheme === 'dark');
    });
    </script>
 <script>
class NotificationManager {
    constructor() {
        this.notificationBadge = document.getElementById('notification-badge');
        this.notificationCount = document.getElementById('notification-count');
        this.notificationItemsContainer = document.querySelector('.notification-items');
        this.departmentFilter = document.getElementById('department-filter');
        this.clearFilterBtn = document.getElementById('clear-filter');
        this.currentFilter = '';
        
        this.init();
    }

    init() {
        this.loadNotifications();
        this.setupEventListeners();
        this.startPolling();
    }

    setupEventListeners() {
        // Handle notification click - redirect to process tracking
        document.addEventListener('click', (e) => {
            if (e.target.closest('.notification-item')) {
                e.preventDefault();
                const notificationItem = e.target.closest('.notification-item');
                const notificationId = notificationItem.dataset.id;
                const patientId = notificationItem.dataset.patientId;
                
                // Mark as read first
                this.markAsRead(notificationId);
                
                // Then redirect to process tracking
                if (patientId) {
                    window.location.href = `/admin/process-tracking/${patientId}`;
                }
            }
        });

        // Department filter change
        if (this.departmentFilter) {
            this.departmentFilter.addEventListener('change', (e) => {
                this.applyFilter(e.target.value);
            });
        }

        // Clear filter button
        if (this.clearFilterBtn) {
            this.clearFilterBtn.addEventListener('click', () => {
                this.clearFilter();
            });
        }

        // Mark all as read
        const markAllReadBtn = document.getElementById('mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', () => this.markAllAsRead());
        }

        // Refresh notifications
        const refreshNotificationsBtn = document.getElementById('refresh-notifications');
        if (refreshNotificationsBtn) {
            refreshNotificationsBtn.addEventListener('click', () => this.loadNotifications());
        }
    }

    applyFilter(filterValue) {
        this.currentFilter = filterValue;
        
        // Update UI
        if (this.departmentFilter) {
            this.departmentFilter.value = filterValue;
        }
        
        // Show/hide clear filter button
        if (this.clearFilterBtn) {
            this.clearFilterBtn.style.display = filterValue ? 'block' : 'none';
        }
        
        // Reload notifications with filter
        this.loadNotifications();
    }

    clearFilter() {
        this.applyFilter('');
    }

    async loadNotifications() {
        try {
            const params = new URLSearchParams();
            if (this.currentFilter) {
                params.append('department', this.currentFilter);
            }

            const response = await fetch(`/admin/notifications/list?${params}`);
            const notifications = await response.json();
            
            this.updateNotificationBadge(notifications);
            this.renderNotifications(notifications);
        } catch (error) {
            console.error('Failed to load notifications:', error);
            this.showErrorMessage();
        }
    }

    updateNotificationBadge(notifications) {
        const unreadCount = notifications.filter(n => !n.is_read).length;
        
        if (this.notificationBadge) {
            this.notificationBadge.textContent = unreadCount;
            this.notificationBadge.style.display = unreadCount > 0 ? 'block' : 'none';
        }
        
        if (this.notificationCount) {
            this.notificationCount.textContent = `${unreadCount} new`;
            this.notificationCount.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
    }

    renderNotifications(notifications) {
        if (!this.notificationItemsContainer) {
            console.error('Notification items container not found');
            return;
        }

        // Clear existing content
        this.notificationItemsContainer.innerHTML = '';

        if (notifications.length === 0) {
            this.showEmptyMessage();
            return;
        }

        notifications.forEach(notification => {
            const notificationEl = this.createNotificationElement(notification);
            this.notificationItemsContainer.insertAdjacentHTML('beforeend', notificationEl);
        });
    }

    createNotificationElement(notification) {
        const readClass = notification.is_read ? '' : 'unread-notification';
        const iconClass = `bg-${notification.icon_color} rounded-circle d-flex align-items-center justify-content-center`;
        
        return `
            <a href="/admin/process-tracking/${notification.patient_id}" class="dropdown-item d-flex align-items-start py-3 notification-item ${readClass}" data-id="${notification.id}" data-patient-id="${notification.patient_id}">
                <div class="flex-shrink-0 me-3">
                    <div class="${iconClass}" style="width: 40px; height: 40px;">
                        <i class="${notification.icon} text-white"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1">${this.escapeHtml(notification.title)}</h6>
                        <small class="text-muted">${this.escapeHtml(notification.time_ago)}</small>
                    </div>
                    <p class="mb-0 text-muted small">${this.escapeHtml(notification.message)}</p>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">By: ${this.escapeHtml(notification.user_name)}</small>
                        <span class="badge bg-light text-dark border">${this.escapeHtml(notification.department)}</span>
                    </div>
                </div>
            </a>
            <div class="dropdown-divider"></div>
        `;
    }

    showEmptyMessage() {
        const filterMessage = this.currentFilter 
            ? `No notifications for ${this.currentFilter}`
            : 'No notifications';

        this.notificationItemsContainer.innerHTML = `
            <div class="dropdown-item text-center py-4">
                <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 2rem;"></i>
                <p class="text-muted mb-0">${filterMessage}</p>
                ${this.currentFilter ? `
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="notificationManager.clearFilter()">
                        <i class="fas fa-eye me-1"></i> Show All
                    </button>
                ` : ''}
            </div>
        `;
    }

    showErrorMessage() {
        if (this.notificationItemsContainer) {
            this.notificationItemsContainer.innerHTML = `
                <div class="dropdown-item text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0">Failed to load notifications</p>
                    <button class="btn btn-sm btn-primary mt-2" onclick="notificationManager.loadNotifications()">
                        <i class="fas fa-redo me-1"></i> Retry
                    </button>
                </div>
            `;
        }
    }

    escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            // Update UI immediately
            const notificationEl = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationEl) {
                notificationEl.classList.remove('unread-notification');
            }
            
            // Reload notifications to update counts
            this.loadNotifications();
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            await fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            this.loadNotifications();
        } catch (error) {
            console.error('Failed to mark all notifications as read:', error);
        }
    }

    startPolling() {
        // Refresh notifications every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    }
}

// Initialize when DOM is loaded
let notificationManager;
document.addEventListener('DOMContentLoaded', function() {
    notificationManager = new NotificationManager();
});
</script>
  <script>
        // Initialize toast if exists
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                // Update timer every second
                const timerElement = document.getElementById('toast-timer');
                if (timerElement) {
                    let seconds = 0;
                    setInterval(() => {
                        seconds++;
                        if (seconds < 60) {
                            timerElement.textContent = `${seconds}s ago`;
                        } else {
                            timerElement.textContent = `${Math.floor(seconds / 60)}m ago`;
                        }
                    }, 1000);
                }
            }
        });
    </script>
    @stack('scripts')

</body>

</html>