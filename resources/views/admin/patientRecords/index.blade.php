@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:        #064e3b;
    --pr-forest-deep:   #052e22;
    --pr-forest-mid:    #065f46;
    --pr-forest-lite:   #047857;
    --pr-lime:          #74ff70;
    --pr-lime-dim:      #52e84e;
    --pr-lime-ghost:    rgba(116,255,112,.10);
    --pr-lime-border:   rgba(116,255,112,.30);
    --pr-surface:       #ffffff;
    --pr-surface2:      #f0fdf4;
    --pr-muted:         #ecfdf5;
    --pr-border:        #d1fae5;
    --pr-border-dark:   #a7f3d0;
    --pr-text:          #052e22;
    --pr-sub:           #3d7a62;
    --pr-warn:          #f59e0b;
    --pr-danger:        #ef4444;
    --pr-radius:        12px;
    --pr-radius-sm:     7px;
    --pr-shadow:        0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg:     0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime:   0 2px 12px rgba(116,255,112,.25);
}

.pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

/* ── hero ── */
.pr-hero {
    background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
    border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 16px;
    position: relative; box-shadow: var(--pr-shadow-lg);
}
.pr-hero::before {
    content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius);
    background:
        radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%),
        radial-gradient(ellipse 180px 100px at 5% 80%,  rgba(116,255,112,.07) 0%, transparent 70%),
        radial-gradient(ellipse 250px 120px at 50% -20%, rgba(255,255,255,.04) 0%, transparent 60%);
    pointer-events: none; z-index: 0;
}
.pr-hero::after {
    content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px;
    background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
    border-radius: 2px; opacity: .55;
}
.pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
.pr-hero-left { display: flex; align-items: center; gap: 16px; }
.pr-hero-icon {
    width: 46px; height: 46px;
    background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.30);
    border-radius: 11px; display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: var(--pr-lime); backdrop-filter: blur(4px); flex-shrink: 0;
}
.pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
.pr-hero-meta  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.pr-badge { display: inline-flex; align-items: center; gap: 4px; border-radius: 20px; font-size: .72rem; font-weight: 600; padding: 2px 10px; letter-spacing: .03em; line-height: 1.6; }
.pr-badge-count   { background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); color: var(--pr-lime); }
.pr-badge-deleted { background: rgba(239,68,68,.22);   border: 1px solid rgba(239,68,68,.4);   color: #fca5a5; }
.pr-hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.pr-toggle-wrap {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14);
    border-radius: 8px; padding: 7px 13px; cursor: pointer; transition: all .2s; user-select: none;
}
.pr-toggle-wrap:hover { background: rgba(116,255,112,.10); border-color: var(--pr-lime-border); }
.pr-toggle-wrap .pr-toggle-label { color: rgba(255,255,255,.80); font-size: .78rem; font-weight: 500; white-space: nowrap; line-height: 1; cursor: pointer; }
.pr-toggle-switch { position: relative; display: inline-flex; align-items: center; width: 32px; height: 18px; flex-shrink: 0; cursor: pointer; }
.pr-toggle-switch input { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }
.pr-toggle-switch .pr-slider { position: absolute; inset: 0; background: rgba(255,255,255,.22); border-radius: 18px; transition: background .2s; }
.pr-toggle-switch .pr-slider::before {
    content: ''; position: absolute; width: 13px; height: 13px;
    left: 2.5px; top: 50%; transform: translateY(-50%);
    background: #fff; border-radius: 50%; transition: left .2s; box-shadow: 0 1px 3px rgba(0,0,0,.25);
}
.pr-toggle-switch.is-checked .pr-slider { background: var(--pr-lime); }
.pr-toggle-switch.is-checked .pr-slider::before { left: calc(100% - 15.5px); background: var(--pr-forest); }

.pr-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-lime); color: var(--pr-forest);
    border: none; border-radius: 8px; padding: 7px 16px;
    font-size: .8rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: background .18s, transform .15s, box-shadow .18s;
    white-space: nowrap; box-shadow: var(--pr-shadow-lime);
}
.pr-btn-primary:hover { background: var(--pr-lime-dim); color: var(--pr-forest); transform: translateY(-1px); box-shadow: 0 4px 18px rgba(116,255,112,.40); }

.pr-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
    padding: 7px 14px; font-size: .8rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

/* ── ribbon ── */
.pr-ribbon {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 11px 18px; margin-bottom: 10px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; box-shadow: 0 1px 4px rgba(6,78,59,.06);
}
.pr-ribbon-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.pr-filter-pills { display: flex; align-items: center; background: var(--pr-muted); border-radius: 8px; padding: 3px; gap: 2px; border: 1px solid var(--pr-border); }
.pr-pill {
    display: inline-flex; align-items: center; gap: 5px; border: none; background: transparent;
    border-radius: 6px; padding: 4px 13px; font-size: .77rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; color: var(--pr-sub); cursor: pointer;
    text-decoration: none; transition: all .18s; white-space: nowrap; line-height: 1.5;
}
.pr-pill:hover { background: rgba(255,255,255,.8); color: var(--pr-text); }
.pr-pill.active { background: var(--pr-surface); color: var(--pr-forest); box-shadow: 0 1px 6px rgba(6,78,59,.14); font-weight: 700; }
.pr-pill-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.dot-all     { background: var(--pr-border-dark); }
.dot-submit  { background: var(--pr-lime-dim); }
.dot-process { background: var(--pr-warn); }
.pr-vr { width: 1px; height: 22px; background: var(--pr-border); flex-shrink: 0; }
.pr-search-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 3px 10px; font-size: .74rem; font-weight: 600; color: var(--pr-forest-mid);
}
.pr-search-tag strong { font-weight: 700; color: var(--pr-forest); }
.pr-search-form { display: flex; align-items: center; gap: 6px; }
.pr-search-wrap { position: relative; display: flex; align-items: center; }
.pr-search-wrap .si { position: absolute; left: 11px; color: var(--pr-sub); font-size: .8rem; pointer-events: none; }
.pr-search-input {
    border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 7px 60px 7px 32px;
    font-size: .8rem; font-family: 'DM Sans', sans-serif; width: 250px;
    color: var(--pr-text); background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
}
.pr-search-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
.pr-search-input::placeholder { color: var(--pr-border-dark); }
.pr-search-clear { position: absolute; right: 34px; background: none; border: none; color: var(--pr-border-dark); font-size: .75rem; cursor: pointer; padding: 2px 4px; transition: color .15s; line-height: 1; }
.pr-search-clear:hover { color: var(--pr-danger); }
.pr-search-btn { position: absolute; right: 5px; background: var(--pr-forest); border: none; border-radius: 5px; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; color: var(--pr-lime); font-size: .72rem; cursor: pointer; transition: background .18s; }
.pr-search-btn:hover { background: var(--pr-forest-mid); }

/* ── advanced filter bar ── */
.pr-adv-bar {
    background: var(--pr-surface2); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 12px 18px; margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(6,78,59,.05);
}
.pr-adv-bar-inner { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 10px; }
.pr-adv-group { display: flex; flex-direction: column; gap: 4px; min-width: 160px; flex: 1 1 160px; }
.pr-adv-group.date-group { min-width: 130px; flex: 1 1 130px; }
.pr-adv-label { font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--pr-sub); white-space: nowrap; }
.pr-adv-select, .pr-adv-date {
    border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 6px 10px;
    font-size: .8rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface);
    transition: border-color .2s, box-shadow .2s; width: 100%; appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233d7a62'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px;
}
.pr-adv-date { background-image: none; padding-right: 10px; }
.pr-adv-select:focus, .pr-adv-date:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
.pr-adv-select option { font-family: 'DM Sans', sans-serif; }
.pr-adv-select.has-value, .pr-adv-date.has-value { border-color: var(--pr-forest); background-color: var(--pr-lime-ghost); color: var(--pr-forest); font-weight: 600; }
.pr-adv-select.has-value { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23064e3b'/%3E%3C/svg%3E"); }
.pr-adv-actions { display: flex; align-items: flex-end; gap: 6px; flex-shrink: 0; padding-bottom: 1px; }
.pr-adv-apply {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-forest); color: var(--pr-lime); border: none;
    border-radius: 8px; padding: 7px 16px; font-size: .8rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s, transform .15s;
    white-space: nowrap; box-shadow: 0 2px 8px rgba(6,78,59,.25);
}
.pr-adv-apply:hover { background: var(--pr-forest-mid); transform: translateY(-1px); }
.pr-adv-reset {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-muted); color: var(--pr-sub); border: 1px solid var(--pr-border-dark);
    border-radius: 8px; padding: 7px 13px; font-size: .8rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s; white-space: nowrap; text-decoration: none;
}
.pr-adv-reset:hover { background: var(--pr-border-dark); color: var(--pr-text); }
.pr-active-filters { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--pr-border); }
.pr-filter-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 3px 10px; font-size: .73rem; font-weight: 600; color: var(--pr-forest);
}
.pr-filter-chip .chip-label { color: var(--pr-sub); font-weight: 500; }
.pr-filter-chip a { color: var(--pr-border-dark); text-decoration: none; font-size: .7rem; line-height: 1; margin-left: 2px; }
.pr-filter-chip a:hover { color: var(--pr-danger); }

/* ── table card ── */
.pr-table-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
}
.pr-table-card .dt-buttons .btn { border-radius: 7px !important; font-size: .78rem !important; font-family: 'DM Sans', sans-serif !important; font-weight: 600 !important; padding: 5px 14px !important; transition: opacity .18s, transform .15s !important; background-image: none !important; }
.pr-table-card .dt-buttons .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-table-card .dataTables_wrapper .dataTables_info {
    font-family: 'DM Sans', sans-serif !important; font-size: .74rem !important;
    font-weight: 500 !important; color: var(--pr-sub) !important;
    padding: 10px 16px !important; border-top: 1px solid var(--pr-border) !important;
    background: var(--pr-surface2) !important; margin: 0 !important; line-height: 1.5 !important;
}

/* ── dt-buttons toolbar ── */
.pr-dt-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px 8px; border-bottom: 1px solid var(--pr-border);
    background: var(--pr-surface2); flex-wrap: wrap; gap: 8px;
}
.pr-dt-toolbar .dt-buttons { display: flex; gap: 5px; flex-wrap: wrap; align-items: center; }
.pr-record-indicator {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 4px 12px; font-size: .72rem;
    font-weight: 600; color: var(--pr-forest); white-space: nowrap; flex-shrink: 0;
    font-family: 'DM Sans', sans-serif;
}
.pr-record-indicator .ri-count { font-weight: 800; color: var(--pr-forest); }
.pr-record-indicator .ri-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--pr-lime-dim); flex-shrink: 0; }

/* ── action column buttons — matches permission index ── */
.pr-action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
.pr-action-wrap a,
.pr-action-wrap button {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px;
    border: 1px solid var(--pr-border-dark); background: var(--pr-surface);
    color: var(--pr-sub); font-size: .78rem; text-decoration: none;
    cursor: pointer; transition: all .18s; flex-shrink: 0;
    padding: 0;
}
.pr-action-wrap a:hover        { border-color: var(--pr-forest); background: var(--pr-lime-ghost); color: var(--pr-forest); }
.pr-action-wrap .del-btn:hover { border-color: var(--pr-danger); background: rgba(239,68,68,.08); color: var(--pr-danger); }
.pr-action-wrap .restore-btn:hover { border-color: #059669; background: rgba(5,150,105,.08); color: #059669; }

/* ── pagination ── */
.pr-pagination-wrap {
    padding: 11px 16px; border-top: 1px solid var(--pr-border);
    background: var(--pr-surface2);
    display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px;
}
.pr-pagination-info { font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; white-space: nowrap; }
.pr-pagination-info strong { font-weight: 700; color: var(--pr-forest); }
.pr-pagination-wrap .pagination { gap: 3px; margin: 0; justify-content: center; }
.pr-pagination-wrap .page-link { border-radius: 7px !important; border: 1px solid var(--pr-border-dark) !important; color: var(--pr-text) !important; font-size: .78rem !important; font-family: 'DM Sans', sans-serif !important; padding: 5px 11px !important; transition: background .15s, color .15s; }
.pr-pagination-wrap .page-item.active .page-link { background: var(--pr-forest) !important; border-color: var(--pr-forest) !important; color: var(--pr-lime) !important; font-weight: 700; }
.pr-pagination-wrap .page-link:hover:not(.active) { background: var(--pr-muted) !important; color: var(--pr-forest) !important; }
.pr-pagination-jump { display: flex; align-items: center; gap: 6px; justify-content: flex-end; font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; }
.pr-pagination-jump input {
    width: 50px; border: 1.5px solid var(--pr-border-dark); border-radius: 7px;
    padding: 4px 7px; font-size: .74rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); text-align: center; background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
}
.pr-pagination-jump input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.10); }
.pr-pagination-jump button {
    background: var(--pr-forest); color: var(--pr-lime); border: none;
    border-radius: 7px; padding: 4px 10px; font-size: .72rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s;
}
.pr-pagination-jump button:hover { background: var(--pr-forest-mid); }
@media (max-width: 640px) {
    .pr-pagination-wrap { grid-template-columns: 1fr; justify-items: center; }
    .pr-pagination-info, .pr-pagination-jump { display: none; }
}

/* ── modals ── */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header { padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border); }
.pr-modal .modal-header.bg-danger  { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%) !important; }
.pr-modal .modal-header.bg-success { background: linear-gradient(135deg, #052e22 0%, #064e3b 100%) !important; }
.pr-modal .modal-header.bg-success .modal-title { color: var(--pr-lime) !important; }
.pr-modal .modal-header:not(.bg-danger):not(.bg-success) { background: linear-gradient(135deg, #052e22 0%, #064e3b 100%); color: #fff; }
.pr-modal .modal-header:not(.bg-danger):not(.bg-success) .modal-title { color: var(--pr-lime); }
.pr-modal .modal-header:not(.bg-danger):not(.bg-success) .btn-close { filter: invert(1); }
.pr-modal .modal-title  { font-size: .95rem; font-weight: 700; letter-spacing: -.01em; }
.pr-modal .modal-body   { padding: 20px 22px; font-size: .85rem; color: var(--pr-text); line-height: 1.6; }
.pr-modal .modal-body p { margin-bottom: 6px; }
.pr-modal .modal-body .alert { border-radius: 8px; font-size: .8rem; font-weight: 500; border: none; padding: 10px 14px; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); gap: 8px; background: var(--pr-surface2); }
.pr-modal .modal-footer .btn { border-radius: 8px; font-size: .8rem; font-family: 'DM Sans', sans-serif; font-weight: 600; padding: 7px 18px; border: none; transition: opacity .18s, transform .15s; background-image: none !important; }
.pr-modal .modal-footer .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-modal .modal-footer .btn-secondary { background: var(--pr-muted) !important;   color: var(--pr-sub) !important;    border: 1px solid var(--pr-border-dark) !important; }
.pr-modal .modal-footer .btn-danger    { background: var(--pr-danger) !important;  color: #fff !important; }
.pr-modal .modal-footer .btn-success   { background: var(--pr-lime) !important;    color: var(--pr-forest) !important; box-shadow: var(--pr-shadow-lime) !important; }
.pr-modal .modal-footer .btn-primary   { background: var(--pr-forest) !important;  color: var(--pr-lime) !important;   box-shadow: 0 2px 8px rgba(6,78,59,.25) !important; }
.pr-modal .form-control, .pr-modal .form-select { border-radius: 7px; border: 1.5px solid var(--pr-border-dark); font-size: .82rem; font-family: 'DM Sans', sans-serif; transition: border-color .2s, box-shadow .2s; padding: 8px 12px; }
.pr-modal .form-control:focus, .pr-modal .form-select:focus { border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); outline: none; }
.pr-modal p { font-size: .83rem; font-weight: 500; color: var(--pr-sub); margin-bottom: 6px; }

.pr-export-menu {
    border-radius: 10px !important; border: 1px solid var(--pr-border-dark) !important;
    padding: 6px !important; min-width: 180px;
    font-family: 'DM Sans', sans-serif; box-shadow: var(--pr-shadow-lg) !important;
}
.pr-export-menu .dropdown-item { border-radius: 7px; font-size: .8rem; font-weight: 500; padding: 8px 12px; display: flex; align-items: center; gap: 8px; color: var(--pr-text); transition: background .15s; }
.pr-export-menu .dropdown-item:hover { background: var(--pr-muted); color: var(--pr-forest); }

@media (max-width: 768px) {
    .pr-hero-inner, .pr-ribbon { flex-direction: column; align-items: flex-start; }
    .pr-search-input { width: 100%; }
    .pr-search-wrap, .pr-search-form { width: 100%; }
    .pr-adv-group { min-width: 100%; }
    .pr-hero { padding: 16px 18px; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="pr-hero-title">{{ trans('cruds.patientRecord.title') }}</div>
                <div class="pr-hero-meta">
                    <span class="pr-badge pr-badge-count">
                        {{ $patientRecords->total() }} {{ $patientRecords->total() === 1 ? 'record' : 'records' }}
                    </span>
                    @if ($showDeleted)
                        <span class="pr-badge pr-badge-deleted"><i class="fas fa-trash-alt" style="font-size:.65rem;"></i> Deleted view</span>
                    @endif
                    @php $activeFilterCount = collect([$barangayFilter,$dateMonthFilter,$caseCategoryFilter,$caseTypeFilter])->filter()->count(); @endphp
                    @if($activeFilterCount)
                        <span class="pr-badge" style="background:rgba(245,166,35,.18);border:1px solid rgba(245,166,35,.4);color:#f5a623;">
                            <i class="fas fa-filter" style="font-size:.6rem;"></i> {{ $activeFilterCount }} filter{{ $activeFilterCount > 1 ? 's' : '' }} active
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            @can('patient_record_delete')
            <div class="pr-toggle-wrap" id="deletedToggleWrap">
                <div class="pr-toggle-switch {{ $showDeleted ? 'is-checked' : '' }}" id="deletedToggleSwitch">
                    <input type="checkbox" id="showDeletedToggle" {{ $showDeleted ? 'checked' : '' }}>
                    <span class="pr-slider"></span>
                </div>
                <span class="pr-toggle-label"><i class="fas fa-trash me-1" style="font-size:.7rem;"></i>Show Deleted</span>
            </div>
            @endcan
            @can('patient_record_create')
                <a class="pr-btn-primary" href="{{ route('admin.patient-records.create') }}">
                    <i class="fas fa-plus" style="font-size:.75rem;"></i>
                    {{ trans('global.add') }} {{ trans('cruds.patientRecord.title_singular') }}
                </a>
                <button class="pr-btn-ghost" data-bs-toggle="modal" data-bs-target="#csvImportModal">
                    <i class="fas fa-file-csv" style="font-size:.75rem;"></i>
                    {{ trans('global.app_csvImport') }}
                </button>
            @endcan
            <div class="dropdown" id="exportDropdown">
                <button class="pr-btn-ghost" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="gap:5px;">
                    <i class="fas fa-file-export" style="font-size:.75rem;"></i> Export
                    <i class="fas fa-chevron-down" style="font-size:.6rem;opacity:.7;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end pr-export-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.patient-records.export', array_merge(request()->all(), ['format' => 'csv'])) }}">
                            <i class="fas fa-file-csv" style="color:var(--pr-lime-dim);width:14px;"></i> Export as CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.patient-records.export', array_merge(request()->all(), ['format' => 'excel'])) }}">
                            <i class="fas fa-file-excel" style="color:var(--pr-lime-dim);width:14px;"></i> Export as Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@can('patient_record_create')
    @include('csvImport.modal', ['model' => 'PatientRecord', 'route' => 'admin.patient-records.parseCsvImport'])
@endcan

{{-- ══ STATUS PILLS + SEARCH RIBBON ══ --}}
<div class="pr-ribbon">
    <div class="pr-ribbon-left">
        @if (!$showDeleted)
            <div class="pr-filter-pills">
                <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->except('status_filter','page'), []))) }}"
                   class="pr-pill {{ !request('status_filter') ? 'active' : '' }}">
                    <span class="pr-pill-dot dot-all"></span> All
                </a>
                <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->except('page'), ['status_filter' => 'Submitted']))) }}"
                   class="pr-pill {{ request('status_filter') === 'Submitted' ? 'active' : '' }}">
                    <span class="pr-pill-dot dot-submit"></span> Submitted
                </a>
                <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->except('page'), ['status_filter' => 'Processing']))) }}"
                   class="pr-pill {{ request('status_filter') === 'Processing' ? 'active' : '' }}">
                    <span class="pr-pill-dot dot-process"></span> Processing
                </a>
            </div>
            <div class="pr-vr"></div>
        @endif
        @if($searchTerm)
            <div class="pr-search-tag">
                <i class="fas fa-search" style="font-size:.68rem;"></i>
                Filtering: <strong>"{{ $searchTerm }}"</strong>
            </div>
        @endif
    </div>
    <form method="GET" action="{{ route('admin.patient-records.index') }}" class="pr-search-form">
        @foreach(request()->except('search','page') as $k => $v)
            @if($v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
        @endforeach
        <div class="pr-search-wrap">
            <i class="fas fa-search si"></i>
            <input type="text" name="search" class="pr-search-input" placeholder="Search records…"
                   value="{{ $searchTerm }}" aria-label="Search">
            @if($searchTerm)
                <a href="{{ route('admin.patient-records.index', array_filter(request()->except('search','page'))) }}"
                   class="pr-search-clear" title="Clear"><i class="fas fa-times"></i></a>
            @endif
            <button type="submit" class="pr-search-btn"><i class="fas fa-arrow-right"></i></button>
        </div>
    </form>
</div>

{{-- ══ ADVANCED FILTER BAR ══ --}}
<div class="pr-adv-bar">
    <form method="GET" action="{{ route('admin.patient-records.index') }}" id="advFilterForm">
        @if(request('status_filter'))<input type="hidden" name="status_filter" value="{{ request('status_filter') }}">@endif
        @if(request('show_deleted'))<input type="hidden"  name="show_deleted"  value="1">@endif
        @if($searchTerm)<input type="hidden" name="search" value="{{ $searchTerm }}">@endif
        <div class="pr-adv-bar-inner">
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-map-marker-alt me-1"></i>Barangay</span>
                <select name="barangay" class="pr-adv-select {{ $barangayFilter ? 'has-value' : '' }}" onchange="this.classList.toggle('has-value', this.value !== '')">
                    <option value="">All Barangays</option>
                    @foreach($barangayOptions as $brgy)
                        <option value="{{ $brgy }}" {{ $barangayFilter === $brgy ? 'selected' : '' }}>{{ $brgy }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-calendar me-1"></i>Date Processed</span>
                <input type="month" name="date_month"
                       class="pr-adv-date {{ $dateMonthFilter ? 'has-value' : '' }}"
                       value="{{ $dateMonthFilter }}"
                       onchange="this.classList.toggle('has-value', this.value !== '')">
            </div>
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-tag me-1"></i>Case Category</span>
                <select name="case_category" class="pr-adv-select {{ $caseCategoryFilter ? 'has-value' : '' }}" onchange="this.classList.toggle('has-value', this.value !== '')">
                    <option value="">All Categories</option>
                    @foreach($caseCategoryOptions as $cat)
                        <option value="{{ $cat }}" {{ $caseCategoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-folder me-1"></i>Case Type</span>
                <select name="case_type" class="pr-adv-select {{ $caseTypeFilter ? 'has-value' : '' }}" onchange="this.classList.toggle('has-value', this.value !== '')">
                    <option value="">All Types</option>
                    @foreach($caseTypeOptions as $type)
                        <option value="{{ $type }}" {{ $caseTypeFilter === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pr-adv-actions">
                <button type="submit" class="pr-adv-apply">
                    <i class="fas fa-filter" style="font-size:.72rem;"></i> Apply
                </button>
                @if($barangayFilter || $dateMonthFilter || $caseCategoryFilter || $caseTypeFilter)
                    <a href="{{ route('admin.patient-records.index', array_filter(['status_filter' => request('status_filter'), 'show_deleted' => request('show_deleted'), 'search' => $searchTerm])) }}"
                       class="pr-adv-reset">
                        <i class="fas fa-times" style="font-size:.72rem;"></i> Clear
                    </a>
                @endif
            </div>
        </div>
        @if($barangayFilter || $dateMonthFilter || $caseCategoryFilter || $caseTypeFilter)
            <div class="pr-active-filters">
                @if($barangayFilter)
                    <div class="pr-filter-chip">
                        <span class="chip-label">Barangay:</span> {{ $barangayFilter }}
                        <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->all(), ['barangay' => null, 'page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if($dateMonthFilter)
                    <div class="pr-filter-chip">
                        <span class="chip-label">Month:</span>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $dateMonthFilter)->format('F Y') }}
                        <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->all(), ['date_month' => null, 'page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if($caseCategoryFilter)
                    <div class="pr-filter-chip">
                        <span class="chip-label">Category:</span> {{ $caseCategoryFilter }}
                        <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->all(), ['case_category' => null, 'page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if($caseTypeFilter)
                    <div class="pr-filter-chip">
                        <span class="chip-label">Type:</span> {{ $caseTypeFilter }}
                        <a href="{{ route('admin.patient-records.index', array_filter(array_merge(request()->all(), ['case_type' => null, 'page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
            </div>
        @endif
    </form>
</div>

{{-- ══ TABLE ══ --}}
<div class="pr-table-card">
    <div class="table-responsive">
        <table class="table datatable datatable-PatientRecord" style="width:100%">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.patientRecord.fields.date_processed') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.case_type') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.control_number') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.claimant_name') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.case_category') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.patient_name') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.diagnosis') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.age') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.address') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.contact_number') }}</th>
                    <th>{{ trans('cruds.patientRecord.fields.case_worker') }}</th>
                    @if ($showDeleted)<th>Deleted At</th>@endif
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patientRecords as $patientRecord)
                    <tr data-entry-id="{{ $patientRecord->id }}"
                        data-status="{{ $patientRecord->clean_status }}"
                        data-filter-category="{{ $patientRecord->filter_category }}"
                        @if ($showDeleted) class="table-danger" @endif>
                        <td></td>
                        <td data-sort="{{ \Carbon\Carbon::parse($patientRecord->date_processed)->timestamp }}">
                            <span>{{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}</span>
                        </td>
                        <td>{{ $patientRecord->case_type ?? '' }}</td>
                        <td>{{ $patientRecord->control_number ?? '' }}</td>
                        <td>{{ $patientRecord->claimant_name ?? '' }}</td>
                        <td>{{ $patientRecord->case_category ?? '' }}</td>
                        <td>{{ $patientRecord->patient_name ?? '' }}</td>
                        <td class="text-truncate" style="max-width:200px">{{ $patientRecord->diagnosis ?? '' }}</td>
                        <td>{{ $patientRecord->age ?? '' }}</td>
                        <td>{{ $patientRecord->address ?? '' }}</td>
                        <td>{{ $patientRecord->contact_number ?? '' }}</td>
                        <td>{{ $patientRecord->case_worker ?? '' }}</td>
                        @if ($showDeleted)
                            <td data-sort="{{ \Carbon\Carbon::parse($patientRecord->deleted_at)->timestamp ?? '' }}">
                                @if ($patientRecord->deleted_at)
                                    {{ \Carbon\Carbon::parse($patientRecord->deleted_at)->format('F j, Y g:i A') }}
                                @else N/A @endif
                            </td>
                        @endif
                        <td>
                            {{-- ── pr-action-wrap: same pill-button style as permission index ── --}}
                            <div class="pr-action-wrap">
                                @if ($showDeleted)
                                    <a href="#" class="restore-single-btn restore-btn"
                                       data-id="{{ $patientRecord->id }}"
                                       data-control-number="{{ $patientRecord->control_number }}"
                                       title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    <a href="#" class="force-delete-single-btn del-btn"
                                       data-id="{{ $patientRecord->id }}"
                                       data-control-number="{{ $patientRecord->control_number }}"
                                       title="Delete Permanently">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @else
                                    @can('patient_record_show')
                                        <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('patient_record_edit')
                                        <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('patient_record_delete')
                                        <a href="#" class="delete-single-btn del-btn"
                                           data-id="{{ $patientRecord->id }}"
                                           data-control-number="{{ $patientRecord->control_number }}"
                                           title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── pagination footer ── --}}
    @if($patientRecords->total() > 0)
    @php
        $cur    = $patientRecords->currentPage();
        $last   = $patientRecords->lastPage();
        $from   = ($cur - 1) * $patientRecords->perPage() + 1;
        $to     = min($cur * $patientRecords->perPage(), $patientRecords->total());
        $total  = $patientRecords->total();
        $pages  = collect();
        for ($i = 1; $i <= $last; $i++) {
            if ($i === 1 || $i === $last || abs($i - $cur) <= 2) $pages->push($i);
        }
        $pages   = $pages->unique()->sort()->values();
        $pageUrl = fn($p) => request()->fullUrlWithQuery(['page' => $p]);
    @endphp
    <div class="pr-pagination-wrap">
        <div class="pr-pagination-info">
            Showing <strong>{{ $from }}–{{ $to }}</strong> of <strong>{{ $total }}</strong> records
        </div>
        @if($last > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item {{ $cur <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cur > 1 ? $pageUrl($cur - 1) : '#' }}" aria-label="Previous">‹</a>
                </li>
                @php $prev = null; @endphp
                @foreach($pages as $p)
                    @if($prev !== null && $p - $prev > 1)
                        <li class="page-item disabled"><span class="page-link" style="border:none;background:transparent;padding:5px 4px;color:var(--pr-sub);">…</span></li>
                    @endif
                    <li class="page-item {{ $p === $cur ? 'active' : '' }}">
                        <a class="page-link" href="{{ $pageUrl($p) }}">{{ $p }}</a>
                    </li>
                    @php $prev = $p; @endphp
                @endforeach
                <li class="page-item {{ $cur >= $last ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cur < $last ? $pageUrl($cur + 1) : '#' }}" aria-label="Next">›</a>
                </li>
            </ul>
        </nav>
        @else
            <div></div>
        @endif
        @if($last > 1)
            <div class="pr-pagination-jump">
                <span>Go to</span>
                <input type="number" id="pr-page-jump" min="1" max="{{ $last }}"
                       placeholder="{{ $cur }}" aria-label="Go to page">
                <button type="button" id="pr-page-jump-btn">Go</button>
            </div>
        @else
            <div></div>
        @endif
    </div>
    @endif
</div>

</div>{{-- /pr-page --}}


{{-- ════ MODALS ════ --}}
<div class="modal fade pr-modal" id="singleDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this patient record?</p>
            <p class="text-muted small">This action can be undone from the deleted records view.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="singleDeleteForm" method="POST" style="display:inline;">
                @method('DELETE') @csrf
                <button type="submit" class="btn btn-danger" id="confirmSingleDeleteBtn"><i class="fas fa-trash-alt me-1"></i> Delete</button>
            </form>
        </div>
    </div></div>
</div>

<div class="modal fade pr-modal" id="massDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Confirm Mass Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete the selected patient records?</p>
            <p class="text-muted small">Records will be moved to the deleted view and can be restored.</p>
            <div id="selectedRecordsCount" class="alert alert-warning mt-2"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmMassDeleteBtn"><i class="fas fa-trash-alt me-1"></i> Delete Selected</button>
        </div>
    </div></div>
</div>

<div class="modal fade pr-modal" id="massSubmitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="massSubmitForm">
            @csrf
            <input type="hidden" name="ids" id="massSubmitIds">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit Selected Patients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Submitted Date</p>
                    <input type="datetime-local" class="form-control mb-3" name="submitted_date" id="massSubmitDate" value="{{ now()->toDateTimeLocalString() }}">
                    <p>Remarks <span style="font-weight:400;color:var(--pr-border-dark);">(optional)</span></p>
                    <textarea class="form-control" name="remarks" id="massSubmitRemarks" rows="3" placeholder="Enter remarks…"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Submit Now</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($showDeleted)
<div class="modal fade pr-modal" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-undo me-2"></i>Confirm Restore</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p id="restoreMessage">Are you sure you want to restore this patient record?</p>
            <p class="text-muted small">This will make the record active again.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="restoreForm" method="POST" style="display:inline;">
                @method('PUT') @csrf
                <button type="submit" class="btn btn-success" id="confirmRestoreBtn"><i class="fas fa-undo me-1"></i> Restore</button>
            </form>
        </div>
    </div></div>
</div>

<div class="modal fade pr-modal" id="forceDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-skull-crossbones me-2"></i>Confirm Permanent Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p id="forceDeleteMessage">Are you sure you want to permanently delete this record?</p>
            <p class="text-muted small"><i class="fas fa-exclamation-triangle text-warning me-1"></i>This action <strong>cannot be undone</strong>. All related data will be permanently removed.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="forceDeleteForm" method="POST" style="display:inline;">
                @method('DELETE') @csrf
                <button type="submit" class="btn btn-danger" id="confirmForceDeleteBtn"><i class="fas fa-trash-alt me-1"></i> Delete Permanently</button>
            </form>
        </div>
    </div></div>
</div>

<div class="modal fade pr-modal" id="massRestoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Confirm Mass Restore</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to restore the selected patient records?</p>
            <p class="text-muted small">All selected records will become active again.</p>
            <div id="selectedRecordsForRestore" class="alert alert-info mt-2"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmMassRestoreBtn"><i class="fas fa-undo me-1"></i> Restore Selected</button>
        </div>
    </div></div>
</div>

<div class="modal fade pr-modal" id="massForceDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-skull-crossbones me-2"></i>Confirm Mass Permanent Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to permanently delete the selected records?</p>
            <p class="text-muted small"><i class="fas fa-exclamation-triangle text-warning me-1"></i>This action <strong>cannot be undone</strong>.</p>
            <div id="selectedRecordsForForceDelete" class="alert alert-warning mt-2"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmMassForceDeleteBtn"><i class="fas fa-trash-alt me-1"></i> Delete Permanently</button>
        </div>
    </div></div>
</div>
@endif

@endsection

@section('scripts')
    @parent
    <script>
    function initializeRealTimeUpdates() {
        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
            window.Echo.connector.pusher.connection.bind('connected',    () => console.log('✅ Pusher connected'));
            window.Echo.connector.pusher.connection.bind('disconnected', () => console.log('❌ Pusher disconnected'));
            window.Echo.connector.pusher.connection.bind('error',        err => console.error('Pusher error:', err));
        }
        if (window.Echo) {
            window.Echo.channel('patients').listen('.patientRecord.changed', e => updatePatientTable(e));
        }
    }

    function updatePatientTable(e) {
        const table = jQuery('.datatable-PatientRecord').DataTable();
        const rowData = [
            '',
            `<span data-order="${new Date(e.date_processed).getTime()}">${safeValue(formatDate(e.date_processed))}</span>`,
            safeValue(e.case_type), safeValue(e.control_number), safeValue(e.claimant_name),
            safeValue(caseCategoryLabel(e.case_category)), safeValue(e.patient_name),
            `<span class="text-truncate" style="max-width:200px;">${safeValue(e.diagnosis)}</span>`,
            safeValue(e.age), safeValue(e.address), safeValue(e.contact_number),
            safeValue(e.case_worker), generateActions(e.id)
        ];
        const expectedCols = table.columns().count();
        while (rowData.length < expectedCols) rowData.unshift('');
        while (rowData.length > expectedCols) rowData.pop();
        const newRow = table.row.add(rowData).draw(false).node();
        table.order([1, 'desc']).draw();
        jQuery(newRow).attr('data-entry-id', e.id).attr('data-status', e.latest_status || 'Processing').addClass('table-success');
        setTimeout(() => jQuery(newRow).removeClass('table-success'), 3000);
    }

    function safeValue(v) {
        if (v === null || v === undefined) return '';
        if (typeof v === 'object') return JSON.stringify(v);
        return String(v);
    }

    function formatDate(input) {
        if (!input) return '';
        if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(input)) {
            const [dp, tp] = input.split(' ');
            const [y,m,d] = dp.split('-').map(Number);
            const [hh,mm,ss] = tp.split(':').map(Number);
            return new Date(y,m-1,d,hh,mm,ss).toLocaleString('en-PH',{year:'numeric',month:'long',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});
        }
        const p = new Date(input);
        return isNaN(p) ? input : p.toLocaleString('en-PH',{year:'numeric',month:'long',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});
    }

    function caseCategoryLabel(code) {
        const m = @json(App\Models\PatientRecord::CASE_CATEGORY_SELECT);
        return m && typeof m === 'object' ? m[code] ?? code : code;
    }

    // ── generateActions: now uses pr-action-wrap pill-button markup ──
    function generateActions(id) {
        const showDeleted = @json($showDeleted ?? false);
        if (showDeleted) {
            return `<div class="pr-action-wrap">
                <a href="#" class="restore-single-btn restore-btn" data-id="${id}" title="Restore">
                    <i class="fas fa-undo"></i>
                </a>
                <a href="#" class="force-delete-single-btn del-btn" data-id="${id}" title="Delete Permanently">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>`;
        }
        return `<div class="pr-action-wrap">
            <a href="/admin/patient-records/${id}" title="View">
                <i class="fas fa-eye"></i>
            </a>
            <a href="/admin/patient-records/${id}/edit" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="delete-single-btn del-btn" data-id="${id}" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeRealTimeUpdates();

        var toastEl = document.getElementById('liveToast');
        var timerEl = document.getElementById('toast-timer');
        if (toastEl) {
            new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
            let rem = 5;
            const iv = setInterval(() => { rem--; if (timerEl) timerEl.textContent = `Closing in ${rem}s`; if (rem <= 0) clearInterval(iv); }, 1000);
        }

        const toggleWrap   = document.getElementById('deletedToggleWrap');
        const toggleSwitch = document.getElementById('deletedToggleSwitch');
        const toggleInput  = document.getElementById('showDeletedToggle');
        if (toggleWrap && toggleInput) {
            toggleWrap.addEventListener('click', function() {
                toggleInput.checked = !toggleInput.checked;
                if (toggleSwitch) toggleSwitch.classList.toggle('is-checked', toggleInput.checked);
                const url = new URL(window.location.href);
                toggleInput.checked ? url.searchParams.set('show_deleted', '1') : url.searchParams.delete('show_deleted');
                window.location.href = url.toString();
            });
        }
    });

    jQuery(function() {
        let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons);
        let _token = jQuery('meta[name="csrf-token"]').attr('content');

        @if ($showDeleted)
            dtButtons.push({
                text: '<i class="fas fa-undo me-1"></i> Restore Selected', className: 'btn-success',
                action: function(e, dt) {
                    var ids = jQuery.map(dt.rows({ selected: true }).nodes(), entry => jQuery(entry).data('entry-id'));
                    if (!ids.length) { alert('Please select at least one record to restore'); return; }
                    jQuery('#selectedRecordsForRestore').text(`You have selected ${ids.length} record(s) for restoration.`);
                    const el = document.getElementById('massRestoreModal');
                    el.dataset.selectedIds = JSON.stringify(ids);
                    new bootstrap.Modal(el).show();
                }
            });
            dtButtons.push({
                text: '<i class="fas fa-trash me-1"></i> Delete Permanently', className: 'btn-danger',
                action: function(e, dt) {
                    var ids = jQuery.map(dt.rows({ selected: true }).nodes(), entry => jQuery(entry).data('entry-id'));
                    if (!ids.length) { alert('Please select at least one record to delete permanently'); return; }
                    jQuery('#selectedRecordsForForceDelete').text(`You have selected ${ids.length} record(s) for permanent deletion.`);
                    const el = document.getElementById('massForceDeleteModal');
                    el.dataset.selectedIds = JSON.stringify(ids);
                    new bootstrap.Modal(el).show();
                }
            });
        @else
            @can('submit_patient_application')
                let selectedIds = [];
                dtButtons.push({
                    text: 'Submit Selected', className: 'btn-primary',
                    action: function(e, dt) {
                        selectedIds = jQuery.map(dt.rows({ selected: true }).nodes(), entry => jQuery(entry).data('entry-id'));
                        if (!selectedIds.length) { alert('No records selected'); return; }
                        jQuery('#massSubmitRemarks').val('');
                        jQuery('#massSubmitModal').modal('show');
                    }
                });
                jQuery('#massSubmitForm').on('submit', function(e) {
                    e.preventDefault();
                    const btn = jQuery(this).find('button[type="submit"]');
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');
                    jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', true);
                    let form = jQuery('<form>', { method:'POST', action:"{{ route('admin.patient-records.massSubmit') }}" })
                        .append(jQuery('<input>', { type:'hidden', name:'_token', value:_token }))
                        .append(jQuery('<input>', { type:'hidden', name:'remarks', value:jQuery('#massSubmitRemarks').val() }))
                        .append(jQuery('<input>', { type:'hidden', name:'submitted_date', value:jQuery('#massSubmitDate').val() }));
                    selectedIds.forEach(id => form.append(jQuery('<input>', { type:'hidden', name:'ids[]', value:id })));
                    form.appendTo('body').submit();
                });
                jQuery('#massSubmitModal').on('hidden.bs.modal', function() {
                    jQuery('#massSubmitForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit Now');
                    jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', false);
                });
            @endcan
            @can('patient_record_delete')
                dtButtons.push({
                    text: '{{ trans('global.datatables.delete') }}', className: 'btn-danger',
                    action: function(e, dt) {
                        var ids = jQuery.map(dt.rows({ selected: true }).nodes(), entry => jQuery(entry).data('entry-id'));
                        if (!ids.length) { alert('{{ trans('global.datatables.zero_selected') }}'); return; }
                        jQuery('#selectedRecordsCount').text(`You have selected ${ids.length} record(s) for deletion.`);
                        const el = document.getElementById('massDeleteModal');
                        el.dataset.selectedIds = JSON.stringify(ids);
                        new bootstrap.Modal(el).show();
                    }
                });
            @endcan
        @endif

        let table = jQuery('.datatable-PatientRecord:not(.ajaxTable)').DataTable({
            buttons: dtButtons,
            order: [[1, 'desc']],
            pageLength: 100,
            orderCellsTop: true,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false, className: 'select-checkbox' },
                { targets: 1, type: 'num' }
            ],
            select: { style: 'multi', selector: 'td:first-child' },
            paging: false, info: false, searching: false, processing: true, serverSide: false,
            initComplete: function() {
                const dtWrapper  = jQuery('.pr-table-card .dataTables_wrapper');
                const dtBtnGroup = dtWrapper.find('.dt-buttons');
                const currentPage = {{ $patientRecords->currentPage() }};
                const lastPage    = {{ $patientRecords->lastPage() }};

                const indicator = jQuery(`
                    <div class="pr-record-indicator" id="pr-record-indicator">
                        <span class="ri-dot"></span>
                        <span style="color:var(--pr-sub);font-weight:500;">Page</span>
                        <span class="ri-count" id="pr-ri-count">${currentPage}</span>
                        <span style="color:var(--pr-sub);font-weight:500;">of ${lastPage}</span>
                    </div>
                `);

                const toolbar = jQuery('<div class="pr-dt-toolbar"></div>');
                const btnWrap = jQuery('<div style="display:flex;gap:5px;flex-wrap:wrap;"></div>');
                btnWrap.append(dtBtnGroup.children().detach());
                toolbar.append(btnWrap).append(indicator);
                jQuery('.pr-table-card .table-responsive').before(toolbar);

                table.on('select deselect', function() {
                    const sel = table.rows({ selected: true }).count();
                    if (sel > 0) {
                        jQuery('#pr-record-indicator').css({ 'background': 'rgba(6,78,59,.10)', 'border-color': 'rgba(6,78,59,.35)' });
                        jQuery('#pr-record-indicator .ri-dot').css('background', 'var(--pr-forest)');
                        jQuery('#pr-record-indicator span:last').text(`of ${lastPage} · ${sel} selected`);
                    } else {
                        jQuery('#pr-record-indicator').css({ 'background': '', 'border-color': '' });
                        jQuery('#pr-record-indicator .ri-dot').css('background', '');
                        jQuery('#pr-ri-count').text(currentPage);
                        jQuery('#pr-record-indicator span:last').text(`of ${lastPage}`);
                    }
                });
            }
        });

        jQuery(document).on('click', '#pr-page-jump-btn', function() {
            const val = parseInt(jQuery('#pr-page-jump').val());
            const max = parseInt(jQuery('#pr-page-jump').attr('max') || 1);
            if (!val || val < 1 || val > max) { jQuery('#pr-page-jump').focus(); return; }
            const url = new URL(window.location.href);
            url.searchParams.set('page', val);
            window.location.href = url.toString();
        });
        jQuery('#pr-page-jump').on('keydown', function(e) {
            if (e.key === 'Enter') jQuery('#pr-page-jump-btn').trigger('click');
        });

        @if (!$showDeleted)
            jQuery(document).on('click', '.delete-single-btn', function(e) {
                e.preventDefault();
                const id = jQuery(this).data('id'), cn = jQuery(this).data('control-number');
                jQuery('#singleDeleteForm').attr('action', `/admin/patient-records/${id}`);
                jQuery('#singleDeleteModal .modal-body p:first').text(`Delete patient record with Control Number: ${cn}?`);
                new bootstrap.Modal(document.getElementById('singleDeleteModal')).show();
            });
            jQuery('#confirmSingleDeleteBtn').on('click', function() {
                jQuery(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');
                jQuery('#singleDeleteForm').submit();
            });
            jQuery('#singleDeleteModal').on('hidden.bs.modal', () =>
                jQuery('#confirmSingleDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt me-1"></i> Delete'));
            jQuery('#confirmMassDeleteBtn').on('click', function() {
                const btn = jQuery(this), ids = JSON.parse(document.getElementById('massDeleteModal').dataset.selectedIds || '[]');
                if (!ids.length) { alert('No records selected'); return; }
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');
                let form = jQuery('<form>', { method:'POST', action:"{{ route('admin.patient-records.massDestroy') }}" })
                    .append(jQuery('<input>', { type:'hidden', name:'_token', value:_token }))
                    .append(jQuery('<input>', { type:'hidden', name:'_method', value:'DELETE' }));
                ids.forEach(id => form.append(jQuery('<input>', { type:'hidden', name:'ids[]', value:id })));
                form.appendTo('body').submit();
            });
            jQuery('#massDeleteModal').on('hidden.bs.modal', () =>
                jQuery('#confirmMassDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt me-1"></i> Delete Selected'));
        @endif

        @if ($showDeleted)
            jQuery(document).on('click', '.restore-single-btn', function(e) {
                e.preventDefault();
                const id = jQuery(this).data('id'), cn = jQuery(this).data('control-number');
                jQuery('#restoreForm').attr('action', `/admin/patient-records/${id}/restore`);
                jQuery('#restoreMessage').text(`Restore patient record with Control Number: ${cn}?`);
                new bootstrap.Modal(document.getElementById('restoreModal')).show();
            });
            jQuery(document).on('click', '.force-delete-single-btn', function(e) {
                e.preventDefault();
                const id = jQuery(this).data('id'), cn = jQuery(this).data('control-number');
                jQuery('#forceDeleteForm').attr('action', `/admin/patient-records/${id}/force-delete`);
                jQuery('#forceDeleteMessage').text(`Permanently delete patient record with Control Number: ${cn}?`);
                new bootstrap.Modal(document.getElementById('forceDeleteModal')).show();
            });
            jQuery('#confirmMassRestoreBtn').on('click', function() {
                const btn = jQuery(this), ids = JSON.parse(document.getElementById('massRestoreModal').dataset.selectedIds || '[]');
                if (!ids.length) { alert('No records selected'); return; }
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Restoring...');
                let form = jQuery('<form>', { method:'POST', action:"{{ route('admin.patient-records.massRestore') }}" })
                    .append(jQuery('<input>', { type:'hidden', name:'_token', value:_token }));
                ids.forEach(id => form.append(jQuery('<input>', { type:'hidden', name:'ids[]', value:id })));
                form.appendTo('body').submit();
            });
            jQuery('#confirmMassForceDeleteBtn').on('click', function() {
                const btn = jQuery(this), ids = JSON.parse(document.getElementById('massForceDeleteModal').dataset.selectedIds || '[]');
                if (!ids.length) { alert('No records selected'); return; }
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');
                let form = jQuery('<form>', { method:'POST', action:"{{ route('admin.patient-records.massForceDelete') }}" })
                    .append(jQuery('<input>', { type:'hidden', name:'_token', value:_token }))
                    .append(jQuery('<input>', { type:'hidden', name:'_method', value:'DELETE' }));
                ids.forEach(id => form.append(jQuery('<input>', { type:'hidden', name:'ids[]', value:id })));
                form.appendTo('body').submit();
            });
            jQuery('#massForceDeleteModal').on('hidden.bs.modal', () =>
                jQuery('#confirmMassForceDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt me-1"></i> Delete Permanently'));
        @endif

        jQuery('a[data-toggle="tab"]').on('shown.bs.tab click', () =>
            jQuery(jQuery.fn.dataTable.tables(true)).DataTable().columns.adjust());
    });
    </script>
@endsection