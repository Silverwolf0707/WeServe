@extends('layouts.admin')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b; --pr-forest-deep: #052e22; --pr-forest-mid: #065f46;
            --pr-lime: #74ff70; --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
            --pr-surface: #ffffff; --pr-surface2: #f0fdf4; --pr-muted: #ecfdf5;
            --pr-border: #d1fae5; --pr-border-dark: #a7f3d0;
            --pr-text: #052e22; --pr-sub: #3d7a62; --pr-danger: #ef4444;
            --pr-radius: 12px;
            --pr-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
            --pr-shadow-lime: 0 2px 12px rgba(116,255,112,.25);
        }

        .pr-page { font-family:'DM Sans',sans-serif; color:var(--pr-text); padding:0 0 2rem; }

        /* ── Hero ── */
        .pr-hero { background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%); border-radius:var(--pr-radius); padding:22px 28px; margin-bottom:16px; position:relative; overflow:visible; box-shadow:var(--pr-shadow-lg); }
        .pr-hero::before { content:''; position:absolute; inset:0; border-radius:var(--pr-radius); background:radial-gradient(ellipse 380px 200px at 95% 50%,rgba(116,255,112,.13) 0%,transparent 65%),radial-gradient(ellipse 180px 100px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%),radial-gradient(ellipse 250px 120px at 50% -20%,rgba(255,255,255,.04) 0%,transparent 60%); pointer-events:none; z-index:0; overflow:hidden; }
        .pr-hero::after { content:''; position:absolute; top:0; left:28px; right:28px; height:2px; background:linear-gradient(to right,transparent,var(--pr-lime),transparent); border-radius:2px; opacity:.55; }
        .pr-hero-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; position:relative; z-index:1; margin-bottom:14px; }
        .pr-hero-left { display:flex; align-items:center; gap:16px; }
        .pr-hero-icon { width:46px; height:46px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.30); border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:var(--pr-lime); backdrop-filter:blur(4px); flex-shrink:0; }
        .pr-hero-title { font-size:1.18rem; font-weight:700; color:#fff; letter-spacing:-.01em; margin:0 0 3px; line-height:1.2; }
        .pr-hero-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .pr-badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; font-size:.72rem; font-weight:600; padding:2px 10px; letter-spacing:.03em; line-height:1.6; }
        .pr-badge-count { background:rgba(116,255,112,.14); border:1px solid rgba(116,255,112,.32); color:var(--pr-lime); }
        .pr-back-btn { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.08); color:rgba(255,255,255,.82); border:1px solid rgba(255,255,255,.18); border-radius:8px; padding:7px 14px; font-size:.8rem; font-weight:500; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .18s; white-space:nowrap; text-decoration:none; }
        .pr-back-btn:hover { background:rgba(116,255,112,.12); border-color:rgba(116,255,112,.35); color:var(--pr-lime); }

        /* ── Status badges ── */
        .pt-status { display:inline-flex; align-items:center; gap:6px; border-radius:20px; padding:4px 12px; font-size:.74rem; font-weight:700; white-space:nowrap; line-height:1.4; }
        .pt-status i { font-size:.72rem; }
        .pt-s-processing   { background:rgba(107,114,128,.15); color:#1f2937; border:1px solid rgba(107,114,128,.40); }
        .pt-s-submitted    { background:rgba(59,130,246,.15);  color:#1e3a8a; border:1px solid rgba(59,130,246,.40); }
        .pt-s-emergency    { background:rgba(239,68,68,.15);   color:#7f1d1d; border:1px solid rgba(239,68,68,.40); }
        .pt-s-approved     { background:rgba(5,150,105,.15);   color:#052e22; border:1px solid rgba(5,150,105,.40); }
        .pt-s-rejected     { background:rgba(239,68,68,.15);   color:#7f1d1d; border:1px solid rgba(239,68,68,.40); }
        .pt-s-budget       { background:rgba(245,158,11,.15);  color:#78350f; border:1px solid rgba(245,158,11,.40); }
        .pt-s-dv           { background:rgba(6,182,212,.15);   color:#0c4a6e; border:1px solid rgba(6,182,212,.40); }
        .pt-s-ready        { background:rgba(139,92,246,.15);  color:#3b0764; border:1px solid rgba(139,92,246,.40); }
        .pt-s-disbursed    { background:rgba(124,58,237,.15);  color:#3b0764; border:1px solid rgba(124,58,237,.40); }
        .pt-s-rollback-tag { font-size:.62rem; padding:1px 6px; border-radius:10px; margin-left:3px; vertical-align:middle; background:rgba(245,158,11,.18); color:#78350f; border:1px solid rgba(245,158,11,.45); font-weight:700; }

        /* ── Cards ── */
        .pr-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); margin-bottom:14px; }
        .pr-card-header { display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--pr-border); background:var(--pr-surface2); border-radius:var(--pr-radius) var(--pr-radius) 0 0; }
        .pr-card-header-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.82rem; flex-shrink:0; }
        .pr-card-header-title { font-size:.88rem; font-weight:700; color:var(--pr-text); letter-spacing:-.01em; }
        .pr-card-body { padding:18px 20px; }

        /* ── Info grid ── */
        .pr-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .pr-info-row { display:flex; flex-direction:column; gap:2px; }
        .pr-info-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--pr-sub); }
        .pr-info-value { font-size:.88rem; font-weight:600; color:var(--pr-text); }
        .pr-info-value.mono { font-family:monospace; font-size:.85rem; color:var(--pr-forest); font-weight:700; letter-spacing:.03em; }

        /* ── Stepper ── */
        .pr-stepper { display:flex; align-items:flex-start; gap:0; position:relative; padding:10px 0 4px; overflow-x:auto; }
        .pr-stepper-step { display:flex; flex-direction:column; align-items:center; flex:1; min-width:80px; position:relative; }
        .pr-stepper-step:not(:last-child)::after { content:''; position:absolute; top:16px; left:calc(50% + 16px); right:calc(-50% + 16px); height:2px; background:var(--pr-border-dark); z-index:0; transition:background .3s; }
        .pr-stepper-step.completed:not(:last-child)::after { background:var(--pr-lime-dim); }
        .pr-stepper-circle { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; z-index:1; border:2px solid var(--pr-border-dark); background:var(--pr-surface); color:var(--pr-sub); transition:all .3s; flex-shrink:0; }
        .pr-stepper-step.completed .pr-stepper-circle { background:var(--pr-forest); border-color:var(--pr-forest); color:var(--pr-lime); }
        .pr-stepper-step.current .pr-stepper-circle { background:var(--pr-lime); border-color:var(--pr-lime-dim); color:var(--pr-forest); box-shadow:0 0 0 4px var(--pr-lime-ghost); }
        .pr-stepper-step.next .pr-stepper-circle { border-color:var(--pr-lime-border); color:var(--pr-sub); background:var(--pr-lime-ghost); }
        .pr-stepper-label { text-align:center; margin-top:7px; }
        .pr-stepper-office { font-size:.67rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.05em; line-height:1.3; }
        .pr-stepper-status { font-size:.68rem; color:#6b7280; font-weight:600; margin-top:2px; line-height:1.2; }
        .pr-stepper-step.completed .pr-stepper-office { color:var(--pr-forest); }
        .pr-stepper-step.current .pr-stepper-office { color:var(--pr-forest); font-weight:800; }
        .pr-stepper-step.current .pr-stepper-status { color:#059669; font-weight:700; }

        /* ── Process log ── */
        .pr-log-list { display:flex; flex-direction:column; gap:8px; }
        .pr-log-item { border-radius:10px; padding:12px 16px; border-left:3px solid transparent; font-size:.82rem; position:relative; transition:transform .15s,box-shadow .15s; }
        .pr-log-item:hover { transform:translateX(2px); box-shadow:0 2px 8px rgba(6,78,59,.08); }
        .pr-log-item-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px; margin-bottom:4px; }
        .pr-log-actor { font-size:.78rem; font-weight:600; color:var(--pr-text); }
        .pr-log-date { font-size:.72rem; color:var(--pr-sub); }
        .pr-log-flow { font-size:.72rem; color:var(--pr-sub); margin-top:2px; }
        .pr-log-remarks { font-size:.76rem; color:#374151; margin-top:4px; }
        .pr-log-remarks em { font-style:normal; color:#6b7280; font-weight:600; }

        .pr-log-processing   { background:#f1f5f9; border-left-color:#94a3b8; }
        .pr-log-submitted    { background:#eff6ff; border-left-color:#3b82f6; }
        .pr-log-emergency    { background:#fff7ed; border-left-color:#f59e0b; }
        .pr-log-approved     { background:#f0fdf4; border-left-color:#10b981; }
        .pr-log-rejected     { background:#fef2f2; border-left-color:#ef4444; }
        .pr-log-budget       { background:#fffbeb; border-left-color:#f59e0b; }
        .pr-log-dv           { background:#f0f9ff; border-left-color:#0ea5e9; }
        .pr-log-ready        { background:#faf5ff; border-left-color:#8b5cf6; }
        .pr-log-disbursed    { background:#f0fdf4; border-left-color:#10b981; }
        .pr-log-rollback     { background:#fffbeb; border-left-color:#f59e0b; }

        /* ── Action sections ── */
        .pr-action-card { border-radius:var(--pr-radius); border:1px solid var(--pr-border); margin-bottom:14px; overflow:visible; transition:all .3s ease; }
        .pr-action-card-header { display:flex; align-items:center; gap:10px; padding:14px 20px; border-radius:var(--pr-radius) var(--pr-radius) 0 0; }
        .pr-action-card-header .ach-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.82rem; flex-shrink:0; }
        .pr-action-card-header .ach-title { font-size:.9rem; font-weight:700; color:#fff; }
        .pr-action-card-header .ach-sub { font-size:.74rem; color:rgba(255,255,255,.7); margin-top:1px; }
        .pr-action-card-body { padding:18px 20px; background:var(--pr-surface); border-radius:0 0 var(--pr-radius) var(--pr-radius); }
        .pr-action-btn-row { display:flex; flex-wrap:wrap; gap:10px; }
        .pr-btn { display:inline-flex; align-items:center; gap:7px; border-radius:9px; padding:9px 20px; font-size:.82rem; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .18s; border:none; white-space:nowrap; text-decoration:none; }
        .pr-btn:hover { transform:translateY(-1px); opacity:.92; }
        .pr-btn-primary   { background:var(--pr-forest); color:var(--pr-lime); box-shadow:var(--pr-shadow-lime); }
        .pr-btn-success   { background:#10b981; color:#fff; box-shadow:0 2px 8px rgba(16,185,129,.3); }
        .pr-btn-danger    { background:#ef4444; color:#fff; box-shadow:0 2px 8px rgba(239,68,68,.25); }
        .pr-btn-warning   { background:#f59e0b; color:#fff; box-shadow:0 2px 8px rgba(245,158,11,.25); }
        .pr-btn-ghost     { background:var(--pr-muted); color:var(--pr-sub); border:1px solid var(--pr-border-dark); box-shadow:none; }
        .pr-btn-ghost:hover { background:var(--pr-border-dark); color:var(--pr-forest); opacity:1; }
        .pr-btn-info      { background:#0ea5e9; color:#fff; box-shadow:0 2px 8px rgba(14,165,233,.25); }

        /* ── Form controls inside action cards ── */
        .pr-field { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
        .pr-field label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-sub); }
        .pr-field input, .pr-field textarea, .pr-field select {
            border:1.5px solid var(--pr-border-dark); border-radius:8px; padding:8px 12px;
            font-size:.82rem; font-family:'DM Sans',sans-serif; color:var(--pr-text);
            background:var(--pr-surface); transition:border-color .2s,box-shadow .2s; width:100%;
        }
        .pr-field input:focus, .pr-field textarea:focus, .pr-field select:focus {
            outline:none; border-color:var(--pr-forest-mid); box-shadow:0 0 0 3px rgba(6,78,59,.10);
        }

        /* ── Amount chips ── */
        .pr-amount-chips { display:flex; flex-wrap:wrap; gap:5px; margin-top:7px; }
        .pr-amount-chip { border:1.5px solid var(--pr-border-dark); background:var(--pr-surface); border-radius:20px; padding:3px 12px; font-size:.74rem; font-weight:600; color:var(--pr-sub); cursor:pointer; font-family:'DM Sans',sans-serif; transition:all .18s; }
        .pr-amount-chip:hover, .pr-amount-chip.selected { border-color:var(--pr-forest); background:var(--pr-lime-ghost); color:var(--pr-forest); }

        /* ── Modal overrides ── */
        .pr-modal .modal-content { border:none; border-radius:var(--pr-radius); font-family:'DM Sans',sans-serif; overflow:hidden; box-shadow:var(--pr-shadow-lg); }
        .pr-modal .modal-header { border-bottom:none; padding:18px 22px 14px; }
        .pr-modal .modal-title { font-size:.95rem; font-weight:700; }
        .pr-modal .modal-body { padding:18px 22px; }
        .pr-modal .modal-footer { border-top:1px solid var(--pr-border); background:var(--pr-surface2); padding:14px 22px; gap:8px; }

        /* ── Alert inside action cards ── */
        .pr-alert { border-radius:8px; padding:10px 14px; font-size:.8rem; font-weight:500; border:none; margin-bottom:12px; display:flex; align-items:flex-start; gap:8px; }
        .pr-alert-info    { background:#eff6ff; color:#1e40af; }
        .pr-alert-warning { background:#fffbeb; color:#92400e; }
        .pr-alert-success { background:#f0fdf4; color:#065f46; }

        /* ── Nav bar (replaces pr-nav-footer) ── */
        .pr-nav-bar {
            background: var(--pr-surface2); border: 1px solid var(--pr-border);
            border-radius: var(--pr-radius); padding: 12px 18px; margin-top: 6px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; box-shadow: var(--pr-shadow);
        }
        .pr-nav-bar-left  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-nav-bar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-nav-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 8px; padding: 7px 14px; font-size: .8rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .18s;
            white-space: nowrap; text-decoration: none; border: none;
        }
        .pr-nav-btn-back {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-back:hover { background: var(--pr-muted); color: var(--pr-forest); border-color: var(--pr-forest); }
        .pr-nav-btn-info {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-info:hover { background: var(--pr-lime-ghost); border-color: var(--pr-forest); color: var(--pr-forest); }

        /* ── Rollback notice in process log ── */
        .pr-log-rollback-icon { font-size:.7rem; }

        @media (max-width:768px) {
            .pr-info-grid { grid-template-columns:1fr; }
            .pr-hero-inner { flex-direction:column; align-items:flex-start; }
            .pr-hero { padding:16px 18px; }
            .pr-nav-bar { flex-direction:column; align-items:flex-start; }
        }
    </style>

    <div class="pr-page">

    {{-- ─── HERO ─── --}}
    <div class="pr-hero">
        <div class="pr-hero-inner">
            <div class="pr-hero-left">
                <div class="pr-hero-icon"><i class="fas fa-file-medical-alt"></i></div>
                <div>
                    <div class="pr-hero-title">Process Tracking</div>
                    <div class="pr-hero-meta">
                        <span class="pr-badge pr-badge-count">
                            <i class="fas fa-hashtag" style="font-size:.6rem;"></i>
                            {{ $patient->control_number }}
                        </span>
                        @php
                            $heroStatus  = $latestStatus->status ?? 'Processing';
                            $heroIsRb    = str_contains($heroStatus, '[ROLLED BACK]');
                            $heroBase    = trim(str_replace('[ROLLED BACK]','', $heroStatus));
                            $heroIsEmerg = str_contains($heroStatus, '[Emergency]') || $heroStatus === 'Submitted[Emergency]';
                            $heroClass   = match(true) {
                                $heroBase === 'Processing'             => 'pt-s-processing',
                                $heroBase === 'Submitted' && $heroIsEmerg => 'pt-s-emergency',
                                $heroBase === 'Submitted'              => 'pt-s-submitted',
                                $heroBase === 'Submitted[Emergency]'   => 'pt-s-emergency',
                                $heroBase === 'Approved'               => 'pt-s-approved',
                                $heroBase === 'Rejected'               => 'pt-s-rejected',
                                $heroBase === 'Budget Allocated'       => 'pt-s-budget',
                                $heroBase === 'DV Submitted'           => 'pt-s-dv',
                                $heroBase === 'Ready for Disbursement' => 'pt-s-ready',
                                $heroBase === 'Disbursed'              => 'pt-s-disbursed',
                                default                                => 'pt-s-processing',
                            };
                            $heroIcon = match($heroBase) {
                                'Processing'             => 'fa-spinner',
                                'Submitted'              => 'fa-paper-plane',
                                'Submitted[Emergency]'   => 'fa-exclamation-triangle',
                                'Approved'               => 'fa-thumbs-up',
                                'Rejected'               => 'fa-ban',
                                'Budget Allocated'       => 'fa-money-bill-wave',
                                'DV Submitted'           => 'fa-file',
                                'Ready for Disbursement' => 'fa-check-circle',
                                'Disbursed'              => 'fa-coins',
                                default                  => 'fa-question-circle',
                            };
                        @endphp
                       
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.process-tracking.index') }}" class="pr-back-btn">
                <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back to List
            </a>
        </div>

        {{-- Bottom row: quick nav links --}}
        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.document-management.show', $patient->id) }}"
               style="display:inline-flex;align-items:center;gap:5px;background:rgba(116,255,112,.10);border:1px solid rgba(116,255,112,.28);border-radius:20px;padding:3px 12px;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.82);text-decoration:none;transition:all .18s;"
               onmouseover="this.style.background='rgba(116,255,112,.20)';this.style.color='#fff'"
               onmouseout="this.style.background='rgba(116,255,112,.10)';this.style.color='rgba(255,255,255,.82)'">
                <i class="fas fa-file-alt" style="font-size:.65rem;"></i> Document
            </a>
            <a href="{{ route('admin.patient-records.show', $patient->id) }}"
               style="display:inline-flex;align-items:center;gap:5px;background:rgba(116,255,112,.10);border:1px solid rgba(116,255,112,.28);border-radius:20px;padding:3px 12px;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.82);text-decoration:none;transition:all .18s;"
               onmouseover="this.style.background='rgba(116,255,112,.20)';this.style.color='#fff'"
               onmouseout="this.style.background='rgba(116,255,112,.10)';this.style.color='rgba(255,255,255,.82)'">
                <i class="fas fa-file-medical" style="font-size:.65rem;"></i> Patient Record
            </a>
        </div>
    </div>

    {{-- ─── INFO + STATUS GRID ─── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        {{-- Application Info --}}
        <div class="pr-card">
            <div class="pr-card-header">
                <div class="pr-card-header-icon" style="background:var(--pr-lime-ghost);color:var(--pr-forest);border:1px solid var(--pr-lime-border);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <span class="pr-card-header-title">Application Info</span>
            </div>
            <div class="pr-card-body">
                <div class="pr-info-grid">
                    <div class="pr-info-row">
                        <span class="pr-info-label">Control Number</span>
                        <span class="pr-info-value mono">{{ $patient->control_number }}</span>
                    </div>
                    <div class="pr-info-row">
                        <span class="pr-info-label">Date Processed</span>
                        <span class="pr-info-value">{{ \Carbon\Carbon::parse($patient->date_processed)->format('M j, Y') }}</span>
                        <span style="font-size:.72rem;color:var(--pr-sub);">{{ \Carbon\Carbon::parse($patient->date_processed)->format('g:i A') }}</span>
                    </div>
                    <div class="pr-info-row">
                        <span class="pr-info-label">Claimant Name</span>
                        <span class="pr-info-value">{{ $patient->claimant_name }}</span>
                    </div>
                    <div class="pr-info-row">
                        <span class="pr-info-label">Case Category</span>
                        <span class="pr-info-value">{{ $patient->case_category }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Process Status --}}
        <div class="pr-card">
            <div class="pr-card-header">
                <div class="pr-card-header-icon" style="background:var(--pr-lime-ghost);color:var(--pr-forest);border:1px solid var(--pr-lime-border);">
                    <i class="fas fa-tasks"></i>
                </div>
                <span class="pr-card-header-title">Process Status</span>
            </div>
            <div class="pr-card-body">
                <div class="pr-info-grid">
                    <div class="pr-info-row">
                        <span class="pr-info-label">Case Worker</span>
                        <span class="pr-info-value">{{ $patient->case_worker }}</span>
                    </div>
                    <div class="pr-info-row">
                        <span class="pr-info-label">Current Status</span>
                        <span id="current-status-badge" class="pt-status {{ $heroClass }}" style="width:fit-content;margin-top:2px;">
                            <i class="fas {{ $heroIcon }}"></i>
                            {{ $heroBase === 'Submitted[Emergency]' ? 'Emergency' : $heroBase }}
                            @if($heroIsRb)<span class="pt-s-rollback-tag">ROLLED BACK</span>@endif
                        </span>
                    </div>

                    @if($patient->budgetAllocation)
                    <div class="pr-info-row" id="budget-allocation-row">
                        <span class="pr-info-label">Budget Allocated</span>
                        <span class="pr-info-value" id="budget-amount-display" style="color:var(--pr-forest);font-weight:700;">
                            ₱{{ number_format($patient->budgetAllocation->amount, 2) }}
                        </span>
                    </div>
                    @else
                    <div class="pr-info-row" id="budget-allocation-row" style="display:none;">
                        <span class="pr-info-label">Budget Allocated</span>
                        <span class="pr-info-value" id="budget-amount-display"></span>
                    </div>
                    @endif

                    @if($patient->disbursementVoucher)
                    <div class="pr-info-row" id="dv-info-row">
                        <span class="pr-info-label">DV Code</span>
                        <span class="pr-info-value mono" id="dv-code-display">{{ $patient->disbursementVoucher->dv_code }}</span>
                    </div>
                    @else
                    <div class="pr-info-row" id="dv-info-row" style="display:none;">
                        <span class="pr-info-label">DV Code</span>
                        <span class="pr-info-value" id="dv-code-display"></span>
                    </div>
                    @endif

                    @if($patient->disbursementVoucher)
                    <div class="pr-info-row" id="dv-date-row">
                        <span class="pr-info-label">DV Date</span>
                        <span class="pr-info-value" id="dv-date-display">
                            {{ \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('M j, Y g:i A') }}
                        </span>
                    </div>
                    @else
                    <div class="pr-info-row" id="dv-date-row" style="display:none;">
                        <span class="pr-info-label">DV Date</span>
                        <span class="pr-info-value" id="dv-date-display"></span>
                    </div>
                    @endif
                </div>

                @if(!empty($latestStatus->remarks))
                <div class="pr-info-row" id="remarks-row" style="margin-top:10px;padding-top:10px;border-top:1px solid var(--pr-border);">
                    <span class="pr-info-label">Remarks</span>
                    <span class="pr-info-value" id="current-remarks" style="font-weight:500;color:var(--pr-sub);">{{ $latestStatus->remarks }}</span>
                </div>
                @else
                <div class="pr-info-row" id="remarks-row" style="display:none;margin-top:10px;padding-top:10px;border-top:1px solid var(--pr-border);">
                    <span class="pr-info-label">Remarks</span>
                    <span class="pr-info-value" id="current-remarks" style="font-weight:500;color:var(--pr-sub);"></span>
                </div>
                @endif

                <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--pr-border);">
                    <span class="pr-info-label">Last Updated</span>
                    <span style="font-size:.78rem;color:var(--pr-sub);" id="status-updated-at">
                        {{ $latestStatus->updated_at->format('M j, Y g:i A') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- ─── STEPPER ─── --}}
    @php
        $steps = ['Submitted', 'Approved', 'Budget Allocated', 'DV Submitted', 'Ready for Disbursement', 'Disbursed'];
        $stepLabels = [
            'Submitted'              => 'CSWD Office',
            'Approved'               => "Mayor's Office",
            'Budget Allocated'       => 'Budget Office',
            'DV Submitted'           => 'Accounting Office',
            'Ready for Disbursement' => 'Treasury Office',
            'Disbursed'              => 'Treasury Office',
        ];
        $rawStatus   = $latestStatus->status ?? '';
        $baseStatus  = trim(preg_replace('/\[.*?\]/', '', $rawStatus));
        $currentIndex = array_search($baseStatus, $steps);
        if ($currentIndex === false) $currentIndex = -1;

        $latestStatusValue = optional($latestStatus)->status;
        $isLocked = !in_array($latestStatusValue, [null, 'Rejected', 'Processing', 'Draft', 'Processing[ROLLED BACK]']);

        $latestLog = $patient->statusLogs->last();
        $userPermissions = auth()->user()->roles->flatMap->permissions->pluck('title')->unique();
    @endphp
    <div class="pr-card" style="margin-bottom:14px;">
        <div class="pr-card-header">
            <div class="pr-card-header-icon" style="background:var(--pr-lime-ghost);color:var(--pr-forest);border:1px solid var(--pr-lime-border);">
                <i class="fas fa-route"></i>
            </div>
            <span class="pr-card-header-title">Process Flow</span>
        </div>
        <div class="pr-card-body">
            <div class="pr-stepper" id="pr-stepper">
                @foreach($steps as $idx => $step)
                    @php
                        $isCompleted = $idx <= $currentIndex;
                        $isCurrent   = $idx === $currentIndex;
                        $isNext      = $idx === $currentIndex + 1;
                    @endphp
                    <div class="pr-stepper-step {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }} {{ $isNext ? 'next' : '' }}">
                        <div class="pr-stepper-circle">
                            @if($isCompleted)
                                <i class="fas fa-check" style="font-size:.7rem;"></i>
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>
                        <div class="pr-stepper-label">
                            <div class="pr-stepper-office">{{ $stepLabels[$step] ?? $step }}</div>
                            <div class="pr-stepper-status">{{ $step }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── PROCESS LOG ─── --}}
    @if($patient->statusLogs->count())
    <div class="pr-card" style="margin-bottom:14px;">
        <div class="pr-card-header">
            <div class="pr-card-header-icon" style="background:var(--pr-lime-ghost);color:var(--pr-forest);border:1px solid var(--pr-lime-border);">
                <i class="fas fa-history"></i>
            </div>
            <span class="pr-card-header-title">Process Log</span>
            <span class="pr-badge" style="background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);color:var(--pr-forest);margin-left:auto;">
                {{ $patient->statusLogs->where('status','!=','Draft')->count() }} entries
            </span>
        </div>
        <div class="pr-card-body">
            @php
                $processSteps = [
                    'Submitted'        => 'CSWD Office',
                    'Approved'         => "Mayor's Office",
                    'Budget Allocated' => 'Budget Office',
                    'DV Submitted'     => 'Accounting Office',
                    'Disbursed'        => 'Treasury Office',
                ];
                $stepKeys = array_keys($processSteps);
            @endphp
            <div class="pr-log-list" id="processSummaryList">
                @foreach($patient->statusLogs->where('status','!=','Draft') as $log)
                    @php
                        $origStatus = $log->status;
                        $cleanStatus = trim(preg_replace('/\[.*?\]/', '', $origStatus));
                        $isRolledBack = str_contains($origStatus, '[ROLLED BACK]');
                        $isEmergency  = str_contains($origStatus, '[Emergency]') || $origStatus === 'Submitted[Emergency]';
                        $logClass = match(true) {
                            $isRolledBack                 => 'pr-log-rollback',
                            $cleanStatus === 'Processing' => 'pr-log-processing',
                            $cleanStatus === 'Submitted' && $isEmergency => 'pr-log-emergency',
                            $cleanStatus === 'Submitted[Emergency]' => 'pr-log-emergency',
                            $cleanStatus === 'Submitted'  => 'pr-log-submitted',
                            $cleanStatus === 'Approved'   => 'pr-log-approved',
                            $cleanStatus === 'Rejected'   => 'pr-log-rejected',
                            $cleanStatus === 'Budget Allocated' => 'pr-log-budget',
                            $cleanStatus === 'DV Submitted'     => 'pr-log-dv',
                            $cleanStatus === 'Ready for Disbursement' => 'pr-log-ready',
                            $cleanStatus === 'Disbursed'  => 'pr-log-disbursed',
                            default                       => 'pr-log-processing',
                        };
                        $fromOffice = $log->user ? $log->user->roles->pluck('title')->implode(', ') : 'System';
                        $toOffice   = null;
                        $currIdx    = array_search($cleanStatus, $stepKeys);
                        if ($currIdx !== false && isset($stepKeys[$currIdx + 1])) {
                            $toOffice = $processSteps[$stepKeys[$currIdx + 1]];
                        }
                        if (stripos($origStatus, 'Processing') !== false) $toOffice = null;
                        elseif (stripos($origStatus, 'Rejected') !== false) $toOffice = 'CSWD Office';
                        elseif ($origStatus === 'Submitted[Emergency]') $toOffice = "Mayor's Office";
                        if ($isRolledBack) {
                            $rbdStatus = str_replace('[ROLLED BACK]','', $origStatus);
                            $rbIdx = array_search(trim($rbdStatus), $stepKeys);
                            if ($rbIdx !== false) $toOffice = $processSteps[$stepKeys[$rbIdx]] ?? null;
                        }
                        $logIcon = match(true) {
                            $isRolledBack => 'fa-undo',
                            $cleanStatus === 'Rejected' => 'fa-times-circle',
                            $cleanStatus === 'Approved' => 'fa-check-circle',
                            $cleanStatus === 'Submitted[Emergency]' => 'fa-exclamation-triangle',
                            $cleanStatus === 'Disbursed' => 'fa-coins',
                            default => 'fa-circle',
                        };
                    @endphp
                    <div class="pr-log-item {{ $logClass }}">
                        <div class="pr-log-item-header">
                            <div style="display:flex;align-items:center;gap:7px;">
                                <i class="fas {{ $logIcon }}" style="font-size:.72rem;opacity:.7;"></i>
                                <strong style="font-size:.82rem;">{{ $origStatus }}</strong>
                                @if($isRolledBack)
                                    <span class="pt-s-rollback-tag">ROLLED BACK</span>
                                @endif
                            </div>
                            <span class="pr-log-date">
                                {{ \Carbon\Carbon::parse($log->status_date)->format('M j, Y g:i A') }}
                            </span>
                        </div>

                        <div class="pr-log-flow">
                            {{ $log->user->name ?? 'System' }}
                            @if(!stripos($origStatus, 'Processing') || stripos($origStatus, 'Processing') === false)
                                &nbsp;·&nbsp; From: <strong>{{ $fromOffice }}</strong>
                                @if($toOffice)
                                    &nbsp;→&nbsp; <strong>{{ $toOffice }}</strong>
                                @endif
                            @endif
                        </div>

                        @if(stripos($origStatus, 'Rejected') !== false)
                            @php
                                $rejectionReasons = $log->rejectionReasons ?? collect();
                                if ($rejectionReasons->isEmpty() && isset($patient->rejectionReasons)) {
                                    $rejectionReasons = $patient->rejectionReasons->where('patient_status_log_id', $log->id);
                                }
                            @endphp
                            @if($rejectionReasons->count())
                            <div style="margin-top:5px;font-size:.76rem;color:var(--pr-sub);">
                                <em style="font-style:normal;font-weight:600;">Rejection Reasons:</em>
                                <ul style="margin:3px 0 0 16px;padding:0;">
                                    @foreach($rejectionReasons as $reason)
                                        <li>{{ $reason->reason }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        @endif

                        @if($cleanStatus === 'Budget Allocated' && $patient->budgetAllocation)
                        <div class="pr-log-remarks">
                            <em>Budget:</em> ₱{{ number_format($patient->budgetAllocation->amount, 2) }}
                        </div>
                        @endif

                        @if($log->remarks)
                        <div class="pr-log-remarks">
                            <em>Remarks:</em> {{ $log->remarks }}
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ─── DYNAMIC ACTION SECTIONS ─── --}}
    <div id="dynamic-action-sections">

    {{-- CSWD – Submit Application --}}
    <div class="pr-action-card action-section" id="submit-patient-application"
         data-permission="submit_patient_application"
         style="display:{{ in_array($baseStatus, ['Processing','Draft','Rejected','Processing[ROLLED BACK]']) && auth()->user()->can('submit_patient_application') ? 'block' : 'none' }};">
        <div class="pr-action-card-header" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);">
            <div class="ach-icon" style="background:rgba(255,255,255,.15);color:#fff;">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <div class="ach-title">CSWD Office – Submit Application</div>
            </div>
        </div>
        <div class="pr-action-card-body">
            <form method="POST">
                @csrf
                <input type="hidden" name="status" value="Submitted">
                <input type="hidden" name="redirect_to_process_tracking" value="1">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="pr-field">
                        <label>Submitted Date</label>
                        <input type="datetime-local" name="submitted_date" id="submitted_date"
                               value="{{ now()->toDateTimeLocalString() }}"
                               @if($isLocked) disabled @endif>
                    </div>
                    <div class="pr-field" style="grid-column:1/-1;">
                        <label>Remarks</label>
                        <textarea name="remarks" rows="3" @if($isLocked) disabled @endif></textarea>
                    </div>
                </div>

                @php
                    $submissionLogs = $patient->statusLogs->whereIn('status',['Submitted','Submitted[Emergency]'])->sortByDesc('status_date');
                    $previousSubmissionStatus = null; $hasEmergencySubmission = false; $hasNormalSubmission = false;
                    if($submissionLogs->count() > 0) {
                        $previousSubmissionStatus = $submissionLogs->first()->status;
                        $hasEmergencySubmission = str_contains($previousSubmissionStatus,'Emergency');
                        $hasNormalSubmission = !$hasEmergencySubmission && str_contains($previousSubmissionStatus,'Submitted');
                    }
                    $showSubmissionWarnings = in_array($baseStatus,['Rejected','Processing[ROLLED BACK]','Processing']);
                @endphp

                <div class="pr-action-btn-row" style="margin-top:4px;">
                    <button type="button" class="pr-btn pr-btn-primary submit-btn"
                        @if($isLocked || $hasEmergencySubmission || ($showSubmissionWarnings && $hasEmergencySubmission)) disabled @endif
                        onclick="submitApplication('{{ route('admin.patient-records.submit', $patient->id) }}', this, 'normal')"
                        id="normal-submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                    <button type="button" class="pr-btn pr-btn-danger submit-btn"
                        @if($isLocked || ($showSubmissionWarnings && $hasNormalSubmission)) disabled @endif
                        onclick="submitApplication('{{ route('admin.patient-records.submit-emergency', $patient->id) }}', this, 'emergency')"
                        id="emergency-submit-btn">
                        <i class="fas fa-exclamation-triangle"></i> Submit [Emergency]
                    </button>
                </div>

                @if($hasEmergencySubmission && $showSubmissionWarnings)
                    <div class="pr-alert pr-alert-warning" style="margin-top:10px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Previously submitted as Emergency — only emergency submissions allowed.
                    </div>
                @elseif($hasNormalSubmission && $showSubmissionWarnings)
                    <div class="pr-alert pr-alert-info" style="margin-top:10px;">
                        <i class="fas fa-info-circle"></i>
                        Previously submitted normally — only normal submissions allowed.
                    </div>
                @endif
                @if($isLocked && !$showSubmissionWarnings)
                    <div class="pr-alert pr-alert-info" style="margin-top:10px;">
                        <i class="fas fa-lock"></i>
                        This application has been submitted and is currently in process.
                    </div>
                @endif
            </form>

            {{-- Return to Rollbacker - Always render but hidden by default --}}
            <div class="return-to-rollbacker-container" style="display:none; margin-top:12px; padding-top:12px; border-top:1px solid var(--pr-border);">
                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                    @csrf
                    <button type="submit" class="pr-btn pr-btn-warning" style="width:100%;justify-content:center;">
                        <i class="fas fa-share"></i> Return to Rollbacker
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MAYOR'S OFFICE – Approve/Reject --}}
    <div class="pr-action-card action-section" id="approve-patient"
         data-permission="approve_patient"
         style="display:{{ in_array($baseStatus,['Submitted','Submitted[Emergency]','Submitted[ROLLED BACK]']) && auth()->user()->can('approve_patient') ? 'block' : 'none' }};">
        <div class="pr-action-card-header" style="background:linear-gradient(135deg,#052e22 0%,#064e3b 100%);">
            <div class="ach-icon" style="background:rgba(116,255,112,.15);color:var(--pr-lime);">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <div class="ach-title">Mayor's Office – Approval</div>
            </div>
        </div>
        <div class="pr-action-card-body">
            <div class="pr-action-btn-row">
                <button type="button" class="pr-btn pr-btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check-circle"></i> Approve
                </button>
                <button type="button" class="pr-btn pr-btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
                
                {{-- Return to Rollbacker - Always render but hidden by default --}}
                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                    @csrf
                    <button type="submit" class="pr-btn pr-btn-warning"><i class="fas fa-share"></i> Return to Rollbacker</button>
                </form>
            </div>
        </div>
    </div>

    {{-- BUDGET OFFICE – Allocate --}}
    <div class="pr-action-card action-section" id="budget-allocate"
         data-permission="budget_allocate"
         style="display:{{ in_array($baseStatus,['Approved','Approved[ROLLED BACK]']) && auth()->user()->can('budget_allocate') ? 'block' : 'none' }};">
        <div class="pr-action-card-header" style="background:linear-gradient(135deg,#78350f 0%,#d97706 100%);">
            <div class="ach-icon" style="background:rgba(255,255,255,.15);color:#fff;">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <div class="ach-title">Budget Office – Allocation</div>
            </div>
        </div>
        <div class="pr-action-card-body">
            <div class="pr-action-btn-row">
                <button type="button" class="pr-btn pr-btn-info" data-bs-toggle="modal" data-bs-target="#budgetModal">
                    <i class="fas {{ $patient->budgetAllocation ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ $patient->budgetAllocation ? 'Edit Budget' : 'Allocate Budget' }}
                </button>
                <button type="button" class="pr-btn pr-btn-danger" data-bs-toggle="modal" data-bs-target="#rollbackModal">
                    <i class="fas fa-undo-alt"></i> Rollback
                </button>
                
                {{-- Return to Rollbacker - Always render but hidden by default --}}
                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                    @csrf
                    <button type="submit" class="pr-btn pr-btn-warning"><i class="fas fa-share"></i> Return to Rollbacker</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ACCOUNTING – DV Input --}}
    <div class="pr-action-card action-section" id="accounting-dv-input"
         data-permission="accounting_dv_input"
         style="display:{{ in_array($baseStatus,['Budget Allocated','Budget Allocated[ROLLED BACK]']) && auth()->user()->can('accounting_dv_input') ? 'block' : 'none' }};">
        <div class="pr-action-card-header" style="background:linear-gradient(135deg,#0c4a6e 0%,#0ea5e9 100%);">
            <div class="ach-icon" style="background:rgba(255,255,255,.15);color:#fff;">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <div class="ach-title">Accounting Office – Disbursement Voucher</div>
            </div>
        </div>
        <div class="pr-action-card-body">
            <div class="pr-action-btn-row">
                <button type="button" class="pr-btn pr-btn-info" data-bs-toggle="modal" data-bs-target="#dvModal">
                    <i class="fas fa-file-alt"></i> {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} DV Details
                </button>
                <button type="button" class="pr-btn pr-btn-danger" data-bs-toggle="modal" data-bs-target="#rollbackModal">
                    <i class="fas fa-undo-alt"></i> Rollback
                </button>
                
                {{-- Return to Rollbacker - Always render but hidden by default --}}
                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                    @csrf
                    <button type="submit" class="pr-btn pr-btn-warning"><i class="fas fa-share"></i> Return to Rollbacker</button>
                </form>
            </div>
        </div>
    </div>

    {{-- TREASURY – Disburse --}}
    <div class="pr-action-card action-section" id="treasury-disburse"
         data-permission="treasury_disburse"
         style="display:{{ in_array($baseStatus,['DV Submitted','DV Submitted[ROLLED BACK]','Ready for Disbursement']) && auth()->user()->can('treasury_disburse') ? 'block' : 'none' }};">

        <div class="dv-submitted-content" style="{{ in_array($baseStatus,['DV Submitted','DV Submitted[ROLLED BACK]']) ? '' : 'display:none;' }}">
            <div class="pr-action-card-header" style="background:linear-gradient(135deg,#312e81 0%,#7c3aed 100%);">
                <div class="ach-icon" style="background:rgba(255,255,255,.15);color:#fff;"><i class="fas fa-money-bill-wave"></i></div>
                <div><div class="ach-title">Treasury Office – Disbursement</div></div>
            </div>
            <div class="pr-action-card-body">
                <div class="pr-action-btn-row">
                    <button type="button" class="pr-btn pr-btn-warning" data-bs-toggle="modal" data-bs-target="#readyForDisbursementModal">
                        <i class="fas fa-exclamation-circle"></i> Mark as Ready for Disbursement
                    </button>
                    <button type="button" class="pr-btn pr-btn-danger" data-bs-toggle="modal" data-bs-target="#rollbackModal">
                        <i class="fas fa-undo-alt"></i> Rollback
                    </button>
                    
                    {{-- Return to Rollbacker - Always render but hidden by default --}}
                    <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                        @csrf
                        <button type="submit" class="pr-btn pr-btn-warning"><i class="fas fa-share"></i> Return to Rollbacker</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="ready-for-disbursement-content" style="{{ $baseStatus === 'Ready for Disbursement' ? '' : 'display:none;' }}">
            <div class="pr-action-card-header" style="background:linear-gradient(135deg,#312e81 0%,#7c3aed 100%);">
                <div class="ach-icon" style="background:rgba(255,255,255,.15);color:#fff;"><i class="fas fa-money-bill-wave"></i></div>
                <div><div class="ach-title">Treasury Office – Disbursement</div></div>
            </div>
            <div class="pr-action-card-body">
                <div class="pr-action-btn-row">
                    <button type="button" class="pr-btn pr-btn-success" data-bs-toggle="modal" data-bs-target="#quickDisburseModal">
                        <i class="fas fa-check-circle"></i> Mark as Disbursed
                    </button>
                    
                    
                    {{-- Return to Rollbacker - Always render but hidden by default --}}
                    <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST" class="return-to-rollbacker-form" style="display:none;">
                        @csrf
                        <button type="submit" class="pr-btn pr-btn-warning"><i class="fas fa-share"></i> Return to Rollbacker</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>{{-- /dynamic-action-sections --}}

    @if($patient->budgetAllocation && $patient->budgetAllocation->budget_status === 'Disbursed')
        <div class="pr-alert pr-alert-success" style="margin-bottom:14px;">
            <i class="fas fa-check-circle"></i> <strong>Disbursed</strong> — This application has been fully disbursed.
        </div>
    @endif

    {{-- ─── BOTTOM NAV BAR ─── --}}
    <div class="pr-nav-bar">
        <div class="pr-nav-bar-left">
            <a href="{{ route('admin.process-tracking.index') }}" class="pr-nav-btn pr-nav-btn-back">
                <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back to List
            </a>
        </div>
        <div class="pr-nav-bar-right">
            <a href="{{ route('admin.document-management.show', $patient->id) }}" class="pr-nav-btn pr-nav-btn-info">
                <i class="fas fa-file-alt" style="font-size:.72rem;"></i> Documents
            </a>
            <a href="{{ route('admin.patient-records.show', $patient->id) }}" class="pr-nav-btn pr-nav-btn-info">
                <i class="fas fa-file-medical" style="font-size:.72rem;"></i> Patient Record
            </a>
        </div>
    </div>

    {{-- ─── MODALS ─── --}}

    <!-- Approve Modal -->
    <div class="modal fade pr-modal" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#052e22 0%,#064e3b 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-check-circle me-2" style="color:var(--pr-lime);"></i>Approve Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-field">
                            <label>Status Date</label>
                            <input type="datetime-local" name="status_date" value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>
                        <div class="pr-field">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="3" placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="approve">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-primary"
                            onclick="const f=this.closest('form');this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Processing...';f.submit();">
                            <i class="fas fa-check-circle"></i> Confirm Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade pr-modal" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}" id="rejectForm" novalidate>
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#991b1b 0%,#ef4444 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-times-circle me-2"></i>Reject Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-field">
                            <label>Reason(s) for Rejection</label>
                            <div style="background:var(--pr-surface2);border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:10px 14px;display:flex;flex-direction:column;gap:6px;">
                                @foreach(['Missing ID','No signature','Expired documents','Wrong name','Missing document'] as $i => $reason)
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin:0;font-size:.82rem;font-weight:500;color:var(--pr-text);">
                                        <input type="checkbox" name="reasons[]" value="{{ $reason }}"
                                               id="reason{{ $i }}" class="reject-reason-cb"
                                               style="width:15px;height:15px;accent-color:var(--pr-forest);cursor:pointer;flex-shrink:0;">
                                        {{ $reason }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="pr-field">
                            <label>Other Reason <span style="font-weight:400;color:var(--pr-sub);">(optional)</span></label>
                            <input type="text" id="other_reason_input" placeholder="Specify other reason…"
                                   style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:8px 12px;font-size:.82rem;font-family:'DM Sans',sans-serif;color:var(--pr-text);width:100%;transition:border-color .2s;">
                            <input type="hidden" name="other_reason" id="other_reason_hidden">
                        </div>
                        <div class="pr-field">
                            <label>Status Date</label>
                            <input type="datetime-local" name="status_date" value="{{ now()->toDateTimeLocalString() }}" required
                                   style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:8px 12px;font-size:.82rem;font-family:'DM Sans',sans-serif;color:var(--pr-text);width:100%;">
                        </div>
                        <div class="pr-field">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="3" placeholder="Enter remarks…"
                                      style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:8px 12px;font-size:.82rem;font-family:'DM Sans',sans-serif;color:var(--pr-text);width:100%;resize:vertical;"></textarea>
                        </div>

                        <div id="reject-reasons-preview" style="display:none;margin-top:2px;padding:8px 12px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;font-size:.76rem;color:#991b1b;">
                            <strong style="font-weight:700;">Will be recorded:</strong>
                            <span id="reject-reasons-list"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-danger" id="confirmRejectBtn">
                            <i class="fas fa-times-circle"></i> Confirm Reject
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Budget Modal -->
    <div class="modal fade pr-modal" id="budgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ $patient->budgetAllocation ? route('admin.process-tracking.updateBudget',$patient->id) : route('admin.process-tracking.storeBudget',$patient->id) }}" method="POST">
                @csrf
                @if($patient->budgetAllocation) @method('PUT') @endif
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#78350f 0%,#d97706 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-wallet me-2"></i>{{ $patient->budgetAllocation ? 'Edit Budget Allocation' : 'Allocate Budget' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-field">
                            <label>Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" id="amount" required
                                   value="{{ old('amount', $patient->budgetAllocation->amount ?? '') }}">
                            <div class="pr-amount-chips">
                                @foreach([1000,2000,3000,4000,5000,6000,7000,8000,9000,10000] as $s)
                                    <button type="button" class="pr-amount-chip suggested-amount" data-value="{{ $s }}">₱{{ number_format($s) }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="pr-field">
                            <label>Status Date</label>
                            <input type="datetime-local" name="status_date" id="budget_status_date"
                                   value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>
                        <div class="pr-field">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="3" placeholder="Enter remarks...">{{ old('remarks', $patient->budgetAllocation->remarks ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-warning"
                            onclick="const f=this.closest('form');this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Processing...';f.submit();">
                            <i class="fas fa-check-circle"></i> {{ $patient->budgetAllocation ? 'Update' : 'Confirm' }} Allocation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DV Modal -->
    <div class="modal fade pr-modal" id="dvModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ $patient->disbursementVoucher ? route('admin.process-tracking.updateDV',$patient->id) : route('admin.process-tracking.storeDV',$patient->id) }}" method="POST">
                @csrf
                @if($patient->disbursementVoucher) @method('PUT') @endif
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#0c4a6e 0%,#0ea5e9 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-file-invoice me-2"></i>{{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} Disbursement Voucher</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="pr-field">
                                <label>DV Code</label>
                                <input type="text" name="dv_code" value="{{ old('dv_code', $patient->disbursementVoucher->dv_code ?? '') }}" placeholder="Enter DV Code">
                            </div>
                            <div class="pr-field">
                                <label>DV Date</label>
                                <input type="datetime-local" name="dv_date"
                                       value="{{ old('dv_date', optional($patient->disbursementVoucher)->dv_date ? \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="pr-field">
                            <label>Status Date</label>
                            <input type="datetime-local" name="status_date" value="{{ old('status_date', now()->toDateTimeLocalString()) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-info"
                            onclick="const f=this.closest('form');this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Processing...';f.submit();">
                            <i class="fas fa-check-circle"></i> {{ $patient->disbursementVoucher ? 'Update' : 'Submit' }} DV
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ready for Disbursement Modal -->
    <div class="modal fade pr-modal" id="readyForDisbursementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.process-tracking.markAsReadyForDisbursement', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#b45309 0%,#f59e0b 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-exclamation-circle me-2"></i>Mark as Ready for Disbursement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-alert pr-alert-info"><i class="fas fa-info-circle"></i> Budget allocation and DV must be completed first.</div>
                        <div class="pr-field">
                            <label>Status Date</label>
                            <input type="datetime-local" name="status_date" value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>
                        <div class="pr-field">
                            <label>Budget Allocated</label>
                            <div style="padding:8px 12px;border-radius:8px;background:var(--pr-surface2);border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-weight:600;color:{{ $patient->budgetAllocation ? 'var(--pr-forest)' : 'var(--pr-danger)' }};">
                                @if($patient->budgetAllocation)
                                    ₱{{ number_format($patient->budgetAllocation->amount, 2) }}
                                @else
                                    <i class="fas fa-exclamation-triangle me-1"></i> No budget allocated
                                @endif
                            </div>
                        </div>
                        <div class="pr-field">
                            <label>DV Code</label>
                            <div style="padding:8px 12px;border-radius:8px;background:var(--pr-surface2);border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-weight:600;color:{{ $patient->disbursementVoucher && $patient->disbursementVoucher->dv_code ? 'var(--pr-forest)' : 'var(--pr-danger)' }};">
                                @if($patient->disbursementVoucher && $patient->disbursementVoucher->dv_code)
                                    {{ $patient->disbursementVoucher->dv_code }}
                                @else
                                    <i class="fas fa-exclamation-triangle me-1"></i> No DV submitted
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-warning"
                            onclick="const b=this,f=b.closest('form');b.disabled=true;b.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Processing...';f.submit();">
                            <i class="fas fa-exclamation-circle"></i> Confirm Ready
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Disburse Modal -->
    <div class="modal fade pr-modal" id="quickDisburseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.process-tracking.quickDisburse', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#065f46 0%,#10b981 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-coins me-2"></i>Disburse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-field">
                            <label>Disbursement Date</label>
                            <input type="datetime-local" name="status_date" value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>
                        <div class="pr-field">
                            <label>Remarks (Optional)</label>
                            <textarea name="remarks" rows="3" placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-success"
                            onclick="const b=this,f=b.closest('form');b.disabled=true;b.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Processing...';f.submit();">
                            <i class="fas fa-check-circle"></i> Confirm Disbursement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rollback Modal -->
    <div class="modal fade pr-modal" id="rollbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.process-tracking.rollback', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#78350f 0%,#f59e0b 100%);">
                        <h5 class="modal-title" style="color:#fff;"><i class="fas fa-undo-alt me-2"></i>Rollback Process</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pr-field">
                            <label>Rollback To</label>
                            <select name="rollback_to" id="rollback_to" required style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:8px 12px;font-size:.82rem;font-family:'DM Sans',sans-serif;width:100%;">
                                <option value="">Select department to rollback to</option>
                                @php
                                    $processFlow = ['Processing'=>'CSWD Office','Submitted'=>"Mayor's Office",'Approved'=>'Budget Office','Budget Allocated'=>'Accounting Office','DV Submitted'=>'Treasury Office'];
                                    $allStatuses = $patient->statusLogs->pluck('status')->map(fn($s)=>trim(str_replace(['[ROLLED BACK]','[EMERGENCY]'],'',$s)))->unique()->filter()->values();
                                    $currentBaseStatus = trim(str_replace(['[ROLLED BACK]','[EMERGENCY]'],'',$latestStatus->status));
                                    $currentPosition = array_search($currentBaseStatus, array_keys($processFlow));
                                    $availableRollbacks = [];
                                    if ($currentPosition !== false && $currentPosition > 0) {
                                        for ($i = $currentPosition - 1; $i >= 0; $i--) {
                                            $targetStatus = array_keys($processFlow)[$i];
                                            if ($allStatuses->contains($targetStatus)) $availableRollbacks[$targetStatus] = array_values($processFlow)[$i];
                                        }
                                    }
                                    if ($currentBaseStatus === 'Submitted' && str_contains($latestStatus->status,'[EMERGENCY]') && $allStatuses->contains('Processing')) {
                                        $availableRollbacks['Processing'] = 'CSWD Office';
                                    }
                                @endphp
                                @foreach($availableRollbacks as $status => $office)
                                    <option value="{{ $status }}">{{ $office }}</option>
                                @endforeach
                            </select>
                            @if(empty($availableRollbacks))
                                <div class="pr-alert pr-alert-info" style="margin-top:8px;">No valid rollback targets available.</div>
                            @endif
                        </div>
                        <div class="pr-field">
                            <label>Remarks</label>
                            <textarea name="rollback_remarks" rows="3" placeholder="Enter rollback reason..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pr-btn pr-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="pr-btn pr-btn-warning"
                            onclick="const f=this.closest('form');this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Rolling back...';f.submit();"
                            {{ empty($availableRollbacks) ? 'disabled' : '' }}>
                            <i class="fas fa-undo-alt"></i> Rollback
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    </div>{{-- /pr-page --}}

@endsection

@section('styles')
@endsection

@section('scripts')
<script>
'use strict';

/* ═══════════════════════════════════════════════════════════════════════════
   CONFIG — injected from blade
═══════════════════════════════════════════════════════════════════════════ */
const PATIENT_ID     = {{ $patient->id }};
const POLL_URL       = "{{ route('admin.process-tracking.pollStatus', $patient->id) }}";
const CSRF_TOKEN     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const userPermissions = @json($userPermissions->values());

/* ═══════════════════════════════════════════════════════════════════════════
   GLOBAL STATE
═══════════════════════════════════════════════════════════════════════════ */
let _lastLogId = null;
let _pollTimer = null;
let _polling   = false;
let _currentStatus = '{{ $latestStatus->status ?? "Processing" }}'; // Track current status
const POLL_MS  = 5000;

/* ═══════════════════════════════════════════════════════════════════════════
   POLLING ENGINE
═══════════════════════════════════════════════════════════════════════════ */
function fetchJSON(url) {
    return fetch(url, {
        headers: {
            'X-Requested-With' : 'XMLHttpRequest',
            'X-CSRF-TOKEN'     : CSRF_TOKEN,
            'Accept'           : 'application/json',
        },
        credentials: 'same-origin',
    }).then(r => r.ok ? r.json() : Promise.reject(r.status));
}

function doPoll() {
    if (_polling || _lastLogId === null) return;
    _polling = true;
    fetchJSON(`${POLL_URL}?since=${_lastLogId}`)
        .then(data => {
            if (data.last_log_id > _lastLogId) _lastLogId = data.last_log_id;
            // Apply each log entry in chronological order (oldest → newest)
            (data.updates || []).forEach(update => {
                // Update current status with the latest log (last one in the array will be newest)
                _currentStatus = update.status;
                applyUpdate(update);
            });
            // After processing all updates, ensure return-to-rollbacker button reflects current status
            updateReturnToRollbackerFromCurrentStatus();
        })
        .catch(err => console.warn('[show-poll] error:', err))
        .finally(() => { _polling = false; });
}

function startPolling() {
    if (_pollTimer) return;
    _pollTimer = setInterval(doPoll, POLL_MS);
}

function stopPolling() {
    clearInterval(_pollTimer);
    _pollTimer = null;
}

function initPolling() {
    fetchJSON(POLL_URL)
        .then(data => {
            _lastLogId = data.last_log_id;
            console.log(`[show-poll] initialized — cursor: ${_lastLogId}`);
            startPolling();
        })
        .catch(err => {
            console.warn('[show-poll] init failed, retrying in 10s:', err);
            setTimeout(initPolling, 10000);
        });
}

// Pause when tab is hidden, immediate poll + resume when visible
document.addEventListener('visibilitychange', () => {
    if (document.hidden) { stopPolling(); }
    else { doPoll(); startPolling(); }
});


function applyUpdate(e) {
    updateStatusBadges(e);     
    updateInfoCard(e);          
    updateStepper(e);          
    appendLogEntry(e);           
    updateActionSections(e);    
    updateFormLockState(e);      
    updateRollbackDropdown(e); 

}

function updateReturnToRollbackerFromCurrentStatus() {
    const isRolledBack = _currentStatus.includes('[ROLLED BACK]');
    
    // Show/hide the forms
    document.querySelectorAll('.return-to-rollbacker-form').forEach(f => {
        f.style.display = isRolledBack ? 'inline-block' : 'none';
    });
    
    document.querySelectorAll('.return-to-rollbacker-container').forEach(c => {
        c.style.display = isRolledBack ? 'block' : 'none';
    });
}


function getBase(status) {
    return status.replace('[ROLLED BACK]', '').trim();
}

function getStatusCssClass(status) {
    const b = getBase(status);
    return {
        'Processing'             : 'pt-s-processing',
        'Submitted'              : 'pt-s-submitted',
        'Submitted[Emergency]'   : 'pt-s-emergency',
        'Approved'               : 'pt-s-approved',
        'Rejected'               : 'pt-s-rejected',
        'Budget Allocated'       : 'pt-s-budget',
        'DV Submitted'           : 'pt-s-dv',
        'Ready for Disbursement' : 'pt-s-ready',
        'Disbursed'              : 'pt-s-disbursed',
    }[b] || 'pt-s-processing';
}

function getStatusIcon(status) {
    const b = getBase(status);
    return {
        'Processing'             : 'fa-spinner',
        'Submitted'              : 'fa-paper-plane',
        'Submitted[Emergency]'   : 'fa-exclamation-triangle',
        'Approved'               : 'fa-thumbs-up',
        'Rejected'               : 'fa-ban',
        'Budget Allocated'       : 'fa-money-bill-wave',
        'DV Submitted'           : 'fa-file',
        'Ready for Disbursement' : 'fa-check-circle',
        'Disbursed'              : 'fa-coins',
    }[b] || 'fa-question-circle';
}

function buildBadgeInner(status) {
    const isRb  = status.includes('[ROLLED BACK]');
    const b     = getBase(status);
    const label = b === 'Submitted[Emergency]' ? 'Emergency' : b;
    const rb    = isRb ? `<span class="pt-s-rollback-tag">ROLLED BACK</span>` : '';
    return `<i class="fas ${getStatusIcon(status)}"></i> ${label}${rb}`;
}

function formatDateTime(raw) {
    if (!raw) return '—';
    return new Date(raw).toLocaleString('en-US', {
        year:'numeric', month:'short', day:'numeric',
        hour:'numeric', minute:'2-digit', hour12:true,
    });
}

function updateStatusBadges(e) {
    ['current-status-badge'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = `pt-status ${getStatusCssClass(e.status)}`;
        el.innerHTML = buildBadgeInner(e.status);
    });
}

function updateInfoCard(e) {
    // Remarks
    const remarksRow = document.getElementById('remarks-row');
    const remarksEl  = document.getElementById('current-remarks');
    if (remarksRow && remarksEl) {
        if (e.remarks && e.remarks.trim()) {
            remarksEl.textContent    = e.remarks;
            remarksRow.style.display = '';
        } else {
            remarksRow.style.display = 'none';
        }
    }

    // Budget amount
    const budgetRow = document.getElementById('budget-allocation-row');
    const budgetAmt = document.getElementById('budget-amount-display');
    if (budgetRow && budgetAmt) {
        if (e.budget_amount != null) {
            budgetAmt.textContent   = '₱' + parseFloat(e.budget_amount).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 });
            budgetRow.style.display = '';
        } else {
            budgetRow.style.display = 'none';
        }
    }

    // DV code
    const dvInfoRow = document.getElementById('dv-info-row');
    const dvCodeEl  = document.getElementById('dv-code-display');
    if (dvInfoRow && dvCodeEl) {
        if (e.dv_code) {
            dvCodeEl.textContent     = e.dv_code;
            dvInfoRow.style.display  = '';
        } else {
            dvInfoRow.style.display  = 'none';
        }
    }

    // DV date
    const dvDateRow = document.getElementById('dv-date-row');
    const dvDateEl  = document.getElementById('dv-date-display');
    if (dvDateRow && dvDateEl) {
        if (e.dv_date) {
            dvDateEl.textContent    = formatDateTime(e.dv_date);
            dvDateRow.style.display = '';
        } else {
            dvDateRow.style.display = 'none';
        }
    }

    // Last updated timestamp
    const updatedAt = document.getElementById('status-updated-at');
    if (updatedAt) updatedAt.textContent = formatDateTime(e.status_date || e.created_at);
}

function updateStepper(e) {
    const steps = ['Submitted','Approved','Budget Allocated','DV Submitted','Ready for Disbursement','Disbursed'];
    const b     = getBase(e.status);
    const idx   = steps.indexOf(b);

    document.querySelectorAll('.pr-stepper-step').forEach((step, i) => {
        step.classList.remove('completed', 'current', 'next');
        if (i <= idx)      step.classList.add('completed');
        if (i === idx)     step.classList.add('current');
        if (i === idx + 1) step.classList.add('next');

        const circle = step.querySelector('.pr-stepper-circle');
        if (!circle) return;
        circle.innerHTML = (i <= idx)
            ? `<i class="fas fa-check" style="font-size:.7rem;"></i>`
            : String(i + 1);
    });
}

const PROCESS_TO_OFFICES = {
    'Processing'             : null,
    'Draft'                  : null,
    'Rejected'               : 'CSWD Office',
    'Submitted'              : "Mayor's Office",
    'Submitted[Emergency]'   : "Mayor's Office",
    'Approved'               : 'Budget Office',
    'Budget Allocated'       : 'Accounting Office',
    'DV Submitted'           : 'Treasury Office',
    'Ready for Disbursement' : null,
    'Disbursed'              : null,
};

function getLogClass(status) {
    if (status.includes('[ROLLED BACK]')) return 'pr-log-rollback';
    const b = getBase(status).replace(/\[.*?\]/g, '').trim();
    return {
        'Processing'             : 'pr-log-processing',
        'Submitted'              : 'pr-log-submitted',
        'Submitted[Emergency]'   : 'pr-log-emergency',
        'Approved'               : 'pr-log-approved',
        'Rejected'               : 'pr-log-rejected',
        'Budget Allocated'       : 'pr-log-budget',
        'DV Submitted'           : 'pr-log-dv',
        'Ready for Disbursement' : 'pr-log-ready',
        'Disbursed'              : 'pr-log-disbursed',
    }[b] || 'pr-log-processing';
}

function buildLogFlowHtml(e) {
    if (!e.user_name || e.user_name === 'System') return '';
    const b        = getBase(e.status);
    const toOffice = PROCESS_TO_OFFICES[b] ?? null;
    let html = `${e.user_name}`;
    if (e.user_role) html += ` &nbsp;·&nbsp; From: <strong>${e.user_role}</strong>`;
    if (toOffice)    html += ` &nbsp;→&nbsp; <strong>${toOffice}</strong>`;
    return html;
}

function buildLogRejectionHtml(e) {
    if (!getBase(e.status).includes('Rejected')) return '';
    const reasons = e.rejection_reasons ?? [];
    if (!reasons.length) return '';
    return `<div style="margin-top:5px;font-size:.76rem;color:var(--pr-sub);">
        <em style="font-style:normal;font-weight:600;">Rejection Reasons:</em>
        <ul style="margin:3px 0 0 16px;padding:0;">
            ${reasons.map(r => `<li>${r}</li>`).join('')}
        </ul>
    </div>`;
}

function appendLogEntry(e) {
    const list = document.getElementById('processSummaryList');
    if (!list) return;

    const isRb   = e.status.includes('[ROLLED BACK]');
    const b      = getBase(e.status);
    const rb     = isRb ? `<span class="pt-s-rollback-tag">ROLLED BACK</span>` : '';
    const icon   = isRb            ? 'fa-undo'
        : b === 'Rejected'         ? 'fa-times-circle'
        : b === 'Approved'         ? 'fa-check-circle'
        : b === 'Disbursed'        ? 'fa-coins'
        : b === 'Submitted[Emergency]' ? 'fa-exclamation-triangle'
        : 'fa-circle';

    const flowHtml      = buildLogFlowHtml(e);
    const rejectionHtml = buildLogRejectionHtml(e);

    const budgetHtml = (b === 'Budget Allocated' && e.budget_amount)
        ? `<div class="pr-log-remarks"><em>Budget:</em> ₱${parseFloat(e.budget_amount).toLocaleString('en-PH', { minimumFractionDigits:2 })}</div>`
        : '';

    const remarksHtml = (e.remarks && e.remarks.trim())
        ? `<div class="pr-log-remarks"><em>Remarks:</em> ${e.remarks}</div>`
        : '';

    const div = document.createElement('div');
    div.className = `pr-log-item ${getLogClass(e.status)}`;
    div.innerHTML = `
        <div class="pr-log-item-header">
            <div style="display:flex;align-items:center;gap:7px;">
                <i class="fas ${icon}" style="font-size:.72rem;opacity:.7;"></i>
                <strong style="font-size:.82rem;">${e.status.replace('[ROLLED BACK]','').trim()}</strong>${rb}
            </div>
            <span class="pr-log-date">${formatDateTime(e.status_date)}</span>
        </div>
        ${flowHtml      ? `<div class="pr-log-flow">${flowHtml}</div>`      : ''}
        ${rejectionHtml}
        ${budgetHtml}
        ${remarksHtml}
    `;

    // Append to bottom for chronological order (oldest at top, newest at bottom)
    list.appendChild(div);

    // Update entry count badge
    const badge = list.closest('.pr-card')?.querySelector('.pr-badge');
    if (badge) {
        const n = parseInt(badge.textContent) || 0;
        badge.textContent = `${n + 1} entries`;
    }
}

const ALL_ACTION_IDS = [
    'submit-patient-application',
    'approve-patient',
    'budget-allocate',
    'accounting-dv-input',
    'treasury-disburse',
];

// Maps base status → { permission needed, section id }
const STATUS_TO_SECTION = {
    'Processing'             : { perm: 'submit_patient_application', id: 'submit-patient-application' },
    'Draft'                  : { perm: 'submit_patient_application', id: 'submit-patient-application' },
    'Rejected'               : { perm: 'submit_patient_application', id: 'submit-patient-application' },
    'Submitted'              : { perm: 'approve_patient',            id: 'approve-patient'            },
    'Submitted[Emergency]'   : { perm: 'approve_patient',            id: 'approve-patient'            },
    'Approved'               : { perm: 'budget_allocate',            id: 'budget-allocate'            },
    'Budget Allocated'       : { perm: 'accounting_dv_input',        id: 'accounting-dv-input'        },
    'DV Submitted'           : { perm: 'treasury_disburse',          id: 'treasury-disburse'          },
    'Ready for Disbursement' : { perm: 'treasury_disburse',          id: 'treasury-disburse'          },
};

function updateActionSections(e) {
    const b   = getBase(e.status);
    const map = STATUS_TO_SECTION[b];

    // Hide everything first
    ALL_ACTION_IDS.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    if (!map || !userPermissions.includes(map.perm)) return;

    const el = document.getElementById(map.id);
    if (!el) return;
    el.style.display = 'block';

    // Treasury card has two sub-views: DV Submitted vs Ready for Disbursement
    if (map.id === 'treasury-disburse') {
        const dvContent    = el.querySelector('.dv-submitted-content');
        const readyContent = el.querySelector('.ready-for-disbursement-content');
        if (dvContent)    dvContent.style.display    = (b === 'DV Submitted')             ? '' : 'none';
        if (readyContent) readyContent.style.display = (b === 'Ready for Disbursement')   ? '' : 'none';
    }
}

const LOCKED_STATUSES = new Set([
    'Submitted','Submitted[Emergency]','Approved',
    'Budget Allocated','DV Submitted','Ready for Disbursement','Disbursed',
]);

function updateFormLockState(e) {
    const lock = LOCKED_STATUSES.has(getBase(e.status));
    const dateEl = document.getElementById('submitted_date');
    if (dateEl) dateEl.disabled = lock;
    document.querySelectorAll('#submit-patient-application .submit-btn')
        .forEach(b => b.disabled = lock);
}

const ROLLBACK_FLOW = {
    'Processing'      : 'CSWD Office',
    'Submitted'       : "Mayor's Office",
    'Approved'        : 'Budget Office',
    'Budget Allocated': 'Accounting Office',
    'DV Submitted'    : 'Treasury Office',
};
const ROLLBACK_STEPS = Object.keys(ROLLBACK_FLOW);

function updateRollbackDropdown(e) {
    const sel = document.getElementById('rollback_to');
    if (!sel) return;

    const b   = getBase(e.status);
    const pos = ROLLBACK_STEPS.indexOf(b);

    // Clear existing options except placeholder
    while (sel.options.length > 1) sel.remove(1);

    if (pos > 0) {
        for (let i = pos - 1; i >= 0; i--) {
            const opt       = document.createElement('option');
            opt.value       = ROLLBACK_STEPS[i];
            opt.textContent = ROLLBACK_FLOW[ROLLBACK_STEPS[i]];
            sel.appendChild(opt);
        }
    }

    // Enable/disable confirm button
    const confirmBtn = document.querySelector('#rollbackModal button.pr-btn-warning');
    if (confirmBtn) confirmBtn.disabled = sel.options.length <= 1;
}

function submitApplication(url, btn, type) {
    const form = btn.closest('form');
    form.action = url;
    document.querySelectorAll('.submit-btn').forEach(b => {
        b.disabled  = true;
        b.innerHTML = (b === btn)
            ? '<i class="fas fa-spinner fa-spin"></i> Processing...'
            : '<i class="fas fa-clock"></i> Please wait...';
    });
    form.submit();
}

document.addEventListener('DOMContentLoaded', function () {

    /* ── Toast ── */
    const toastEl = document.getElementById('liveToast');
    const timerEl = document.getElementById('toast-timer');
    if (toastEl) {
        new bootstrap.Toast(toastEl, { autohide:true, delay:5000 }).show();
        let rem = 5;
        const iv = setInterval(() => {
            rem--;
            if (timerEl) timerEl.textContent = `Closing in ${rem}s`;
            if (rem <= 0) clearInterval(iv);
        }, 1000);
    }

    /* ── Reject modal: live reason preview ── */
    const rejectCbs   = document.querySelectorAll('.reject-reason-cb');
    const otherInput  = document.getElementById('other_reason_input');
    const otherHidden = document.getElementById('other_reason_hidden');
    const preview     = document.getElementById('reject-reasons-preview');
    const previewList = document.getElementById('reject-reasons-list');
    const confirmBtn  = document.getElementById('confirmRejectBtn');

    function refreshRejectPreview() {
        const checked = [...rejectCbs].filter(cb => cb.checked).map(cb => cb.value);
        const other   = otherInput?.value.trim() ?? '';
        if (otherHidden) otherHidden.value = other;
        const all = other ? [...checked, other] : checked;
        if (preview && previewList) {
            previewList.textContent = all.length ? ' ' + all.join(', ') : '';
            preview.style.display   = all.length ? 'block' : 'none';
        }
    }

    rejectCbs.forEach(cb  => cb.addEventListener('change', refreshRejectPreview));
    if (otherInput) otherInput.addEventListener('input', refreshRejectPreview);

    document.getElementById('rejectModal')?.addEventListener('hidden.bs.modal', () => {
        rejectCbs.forEach(cb => cb.checked = false);
        if (otherInput)  otherInput.value  = '';
        if (otherHidden) otherHidden.value = '';
        if (preview)     preview.style.display = 'none';
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-times-circle"></i> Confirm Reject';
        }
    });

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (otherHidden && otherInput) otherHidden.value = otherInput.value.trim();
            const form = document.getElementById('rejectForm');
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            form.submit();
        });
    }

    /* ── Amount chips ── */
    const amountInput = document.getElementById('amount');
    document.querySelectorAll('.pr-amount-chip').forEach(btn => {
        btn.addEventListener('click', function () {
            if (amountInput) amountInput.value = this.dataset.value;
            document.querySelectorAll('.pr-amount-chip').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    /* ── Set initial current status and update return-to-rollbacker button ── */
    _currentStatus = '{{ $latestStatus->status ?? "Processing" }}';
    updateReturnToRollbackerFromCurrentStatus();

    /* ── Start polling ── */
    initPolling();
});
</script>
@endsection