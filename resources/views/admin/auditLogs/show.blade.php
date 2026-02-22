@extends('layouts.admin')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:#064e3b;--pr-forest-deep:#052e22;--pr-forest-mid:#065f46;--pr-forest-lite:#047857;
    --pr-lime:#74ff70;--pr-lime-dim:#52e84e;--pr-lime-ghost:rgba(116,255,112,.10);--pr-lime-border:rgba(116,255,112,.30);
    --pr-surface:#ffffff;--pr-surface2:#f0fdf4;--pr-muted:#ecfdf5;--pr-border:#d1fae5;--pr-border-dark:#a7f3d0;
    --pr-text:#052e22;--pr-sub:#3d7a62;--pr-warn:#f59e0b;--pr-danger:#ef4444;
    --pr-radius:12px;--pr-radius-sm:7px;
    --pr-shadow:0 2px 8px rgba(6,78,59,.08),0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg:0 4px 24px rgba(6,78,59,.16),0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime:0 2px 12px rgba(116,255,112,.25);
}
.pr-page{font-family:'DM Sans',sans-serif;color:var(--pr-text);padding:0 0 2rem}

/* hero */
.pr-hero{background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%);border-radius:var(--pr-radius);padding:22px 28px;margin-bottom:20px;position:relative;overflow:hidden;box-shadow:var(--pr-shadow-lg)}
.pr-hero::before{content:'';position:absolute;inset:0;border-radius:var(--pr-radius);background:radial-gradient(ellipse 380px 200px at 95% 50%,rgba(116,255,112,.13) 0%,transparent 65%),radial-gradient(ellipse 180px 100px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%),radial-gradient(ellipse 250px 120px at 50% -20%,rgba(255,255,255,.04) 0%,transparent 60%);pointer-events:none;z-index:0}
.pr-hero::after{content:'';position:absolute;top:0;left:28px;right:28px;height:2px;background:linear-gradient(to right,transparent,var(--pr-lime),transparent);border-radius:2px;opacity:.55}
.pr-hero-inner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;position:relative;z-index:1}
.pr-hero-left{display:flex;align-items:center;gap:16px}
.pr-hero-icon{width:46px;height:46px;background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.30);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;color:var(--pr-lime);backdrop-filter:blur(4px);flex-shrink:0}
.pr-hero-title{font-size:1.18rem;font-weight:700;color:#fff;letter-spacing:-.01em;margin:0 0 3px;line-height:1.2}
.pr-hero-sub{font-size:.78rem;color:rgba(255,255,255,.55);font-weight:400}
.pr-hero-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px}
.pr-badge{display:inline-flex;align-items:center;gap:4px;border-radius:20px;font-size:.72rem;font-weight:600;padding:2px 10px;letter-spacing:.03em;line-height:1.6}
.pr-badge-id{background:rgba(116,255,112,.14);border:1px solid rgba(116,255,112,.32);color:var(--pr-lime)}
.pr-badge-action{border:1px solid}
.pr-ba-created{background:rgba(116,255,112,.18);border-color:rgba(116,255,112,.4)!important;color:var(--pr-lime)}
.pr-ba-updated{background:rgba(59,130,246,.18);border-color:rgba(59,130,246,.4)!important;color:#bfdbfe}
.pr-ba-deleted{background:rgba(239,68,68,.18);border-color:rgba(239,68,68,.4)!important;color:#fca5a5}
.pr-ba-accessed{background:rgba(245,158,11,.18);border-color:rgba(245,158,11,.4)!important;color:#fde68a}
.pr-ba-default{background:rgba(255,255,255,.10);border-color:rgba(255,255,255,.22)!important;color:rgba(255,255,255,.75)}
.pr-hero-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.pr-btn-ghost{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.82);border:1px solid rgba(255,255,255,.18);border-radius:8px;padding:8px 16px;font-size:.82rem;font-weight:500;font-family:'DM Sans',sans-serif;text-decoration:none;cursor:pointer;transition:all .18s;white-space:nowrap}
.pr-btn-ghost:hover{background:rgba(116,255,112,.12);border-color:var(--pr-lime-border);color:var(--pr-lime)}

/* detail card */
.pr-detail-card{background:var(--pr-surface);border-radius:var(--pr-radius);border:1px solid var(--pr-border);box-shadow:var(--pr-shadow);overflow:hidden;margin-bottom:16px}
.pr-section-head{display:flex;align-items:center;gap:10px;padding:16px 24px 14px;border-bottom:1px solid var(--pr-border);background:var(--pr-surface2)}
.pr-section-icon{width:30px;height:30px;border-radius:8px;flex-shrink:0;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);display:flex;align-items:center;justify-content:center;color:var(--pr-forest);font-size:.78rem}
.pr-section-title{font-size:.82rem;font-weight:700;color:var(--pr-forest);letter-spacing:.04em;text-transform:uppercase}
.pr-detail-body{padding:22px 24px}
.pr-detail-field{margin-bottom:18px}
.pr-detail-field:last-child{margin-bottom:0}
.pr-detail-label{font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--pr-sub);margin-bottom:4px;display:flex;align-items:center;gap:5px}
.pr-detail-value{font-size:.88rem;font-weight:500;color:var(--pr-text);background:var(--pr-surface2);border:1px solid var(--pr-border);border-radius:8px;padding:9px 13px;line-height:1.5;word-break:break-word}
.pr-detail-value.empty-val{color:var(--pr-border-dark);font-style:italic;font-weight:400}

/* inline chips */
.pr-action-detail{display:inline-flex;align-items:center;gap:5px;border-radius:20px;padding:4px 12px;font-size:.76rem;font-weight:700;border:1px solid}
.ad-created{background:var(--pr-lime-ghost);border-color:var(--pr-lime-border);color:var(--pr-forest)}
.ad-updated{background:rgba(59,130,246,.08);border-color:rgba(59,130,246,.25);color:#1e40af}
.ad-deleted{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.25);color:#991b1b}
.ad-accessed{background:rgba(245,158,11,.08);border-color:rgba(245,158,11,.25);color:#78350f}
.ad-default{background:rgba(107,114,128,.08);border-color:rgba(107,114,128,.22);color:#374151}
.pr-subject-pill{display:inline-flex;align-items:center;gap:5px;background:rgba(6,78,59,.08);border:1px solid rgba(6,78,59,.18);border-radius:20px;padding:3px 12px;font-size:.78rem;font-weight:700;color:var(--pr-forest)}
.pr-user-chip{display:inline-flex;align-items:center;gap:6px;background:var(--pr-surface2);border:1px solid var(--pr-border-dark);border-radius:20px;padding:4px 12px;font-size:.82rem;font-weight:600;color:var(--pr-text)}
.pr-user-avatar{width:22px;height:22px;border-radius:6px;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:800;color:var(--pr-forest);text-transform:uppercase;flex-shrink:0}
.pr-ip-block{font-family:'Courier New',monospace;font-size:.84rem;background:var(--pr-surface2);border:1px solid var(--pr-border);border-radius:7px;padding:7px 13px;color:var(--pr-sub);display:inline-block}
.pr-date-main{font-size:.88rem;font-weight:500}
.pr-date-ago{font-size:.74rem;color:var(--pr-sub);margin-top:2px}

/* JSON viewer */
.pr-json-card{background:#0d1117;border-radius:10px;border:1px solid rgba(116,255,112,.15);overflow:hidden}
.pr-json-toolbar{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;background:rgba(6,78,59,.5);border-bottom:1px solid rgba(116,255,112,.12)}
.pr-json-dots{display:flex;align-items:center;gap:5px}
.pr-json-dot{width:9px;height:9px;border-radius:50%}
.pr-json-filename{font-size:.72rem;font-weight:600;color:rgba(116,255,112,.8);font-family:'Courier New',monospace;margin-left:8px}
.pr-json-copy{display:inline-flex;align-items:center;gap:5px;background:rgba(116,255,112,.10);border:1px solid rgba(116,255,112,.22);border-radius:6px;padding:3px 10px;font-size:.72rem;font-weight:600;font-family:'DM Sans',sans-serif;color:rgba(116,255,112,.85);cursor:pointer;transition:background .15s}
.pr-json-copy:hover{background:rgba(116,255,112,.20)}
.pr-json-body{padding:16px 18px;overflow-x:auto;max-height:480px;overflow-y:auto}
.pr-json-body pre{margin:0;font-family:'Courier New',monospace;font-size:.78rem;line-height:1.7;color:#e6edf3;white-space:pre}
.pr-json-body .jk{color:#79c0ff}
.pr-json-body .js{color:#a5d6ff}
.pr-json-body .jn{color:#f2cc60}
.pr-json-body .jb{color:#ff7b72}
.pr-json-body .jz{color:#8b949e;font-style:italic}

/* diff */
.pr-diff-section{margin-bottom:16px}
.pr-diff-fieldname{font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--pr-sub);margin-bottom:6px}
.pr-diff-cols{display:flex;gap:10px;flex-wrap:wrap}
.pr-diff-col{flex:1;min-width:150px}
.pr-diff-col-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px}
.pr-diff-old .pr-diff-col-label{color:rgba(239,68,68,.7)}
.pr-diff-new .pr-diff-col-label{color:rgba(116,255,112,.7)}
.pr-diff-old-body{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.20);border-radius:8px;padding:10px 14px}
.pr-diff-new-body{background:rgba(116,255,112,.06);border:1px solid rgba(116,255,112,.18);border-radius:8px;padding:10px 14px}
.pr-diff-old-body pre{color:#fca5a5;margin:0;font-family:'Courier New',monospace;font-size:.78rem;line-height:1.6;white-space:pre-wrap;word-break:break-word}
.pr-diff-new-body pre{color:#86efac;margin:0;font-family:'Courier New',monospace;font-size:.78rem;line-height:1.6;white-space:pre-wrap;word-break:break-word}
.pr-view-toggle{font-size:.72rem;font-weight:600;color:var(--pr-sub);background:none;border:1px solid var(--pr-border-dark);border-radius:6px;padding:3px 10px;cursor:pointer;transition:all .15s;font-family:'DM Sans',sans-serif}
.pr-view-toggle:hover{border-color:var(--pr-forest);color:var(--pr-forest)}
.pr-raw-view{display:none}
.pr-raw-view.visible{display:block;margin-top:12px}

/* action footer */
.pr-action-footer{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;background:var(--pr-surface);border-radius:var(--pr-radius);border:1px solid var(--pr-border);padding:16px 24px;box-shadow:var(--pr-shadow)}
.pr-btn-secondary{display:inline-flex;align-items:center;gap:7px;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);border-radius:8px;padding:9px 18px;font-size:.84rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;cursor:pointer;transition:background .18s;white-space:nowrap}
.pr-btn-secondary:hover{background:var(--pr-border-dark);color:var(--pr-text)}

@media(max-width:768px){.pr-hero-inner,.pr-action-footer{flex-direction:column;align-items:flex-start}.pr-hero{padding:16px 18px}.pr-detail-body{padding:14px 16px}.pr-section-head{padding:12px 16px}}
</style>

<div class="pr-page">

@php
    $desc    = $auditLog->description ?? '';
    $descLow = strtolower($desc);
    if      (str_contains($descLow,'created'))                               $slug = 'created';
    elseif  (str_contains($descLow,'updated'))                               $slug = 'updated';
    elseif  (str_contains($descLow,'deleted'))                               $slug = 'deleted';
    elseif  (str_contains($descLow,'accessed')||str_contains($descLow,'viewed')) $slug = 'accessed';
    else                                                                      $slug = 'default';
    $subject = str_replace('App\Models\\','', $auditLog->subject_type ?? '');
    $props   = $auditLog->properties;
    $hasOld  = is_array($props) && isset($props['old']);
    $hasNew  = is_array($props) && isset($props['attributes']);
    $isDiff  = $hasOld || $hasNew;
@endphp

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="pr-hero-title">Audit Log Entry</div>
                <div class="pr-hero-meta">
                    <span class="pr-badge pr-badge-id"><i class="fas fa-hashtag" style="font-size:.58rem;"></i> {{ $auditLog->id }}</span>
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span class="pr-badge pr-badge-action pr-ba-{{ $slug }}">
                        <i class="fas fa-circle" style="font-size:.42rem;"></i> {{ ucfirst($slug) }}
                    </span>
                    @if($subject)
                        <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                        <span style="color:rgba(255,255,255,.6);font-size:.75rem;">{{ $subject }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            <a href="{{ route('admin.audit-logs.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to List
            </a>
        </div>
    </div>
</div>

{{-- ══ EVENT DETAILS + SUBJECT & ACTOR (2 col) ══ --}}
<div class="row g-3 mb-0">
    <div class="col-lg-6">
        <div class="pr-detail-card h-100">
            <div class="pr-section-head">
                <div class="pr-section-icon"><i class="fas fa-info-circle"></i></div>
                <span class="pr-section-title">Event Details</span>
            </div>
            <div class="pr-detail-body">
                <div class="row g-3">
                    <div class="col-3">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-hashtag"></i> ID</div>
                            <div class="pr-detail-value" style="font-weight:800;font-size:.95rem;color:var(--pr-forest);">{{ $auditLog->id }}</div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-tag"></i> Action</div>
                            <div class="pr-detail-value" style="padding:7px 12px;">
                                <span class="pr-action-detail ad-{{ $slug }}">
                                    <i class="fas fa-circle" style="font-size:.38rem;"></i> {{ ucfirst($slug) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-align-left"></i> Description</div>
                    <div class="pr-detail-value">{{ $auditLog->description ?: '' }}
                        @if(!$auditLog->description)<span class="empty-val">—</span>@endif
                    </div>
                </div>
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-clock"></i> Timestamp</div>
                    <div class="pr-detail-value">
                        <div class="pr-date-main">{{ $auditLog->created_at?->format('F j, Y g:i:s A') }}</div>
                        <div class="pr-date-ago">{{ $auditLog->created_at?->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="pr-detail-card h-100">
            <div class="pr-section-head">
                <div class="pr-section-icon"><i class="fas fa-sitemap"></i></div>
                <span class="pr-section-title">Subject & Actor</span>
            </div>
            <div class="pr-detail-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-cube"></i> Subject Type</div>
                            <div class="pr-detail-value" style="padding:8px 12px;">
                                @if($subject)
                                    <span class="pr-subject-pill"><i class="fas fa-cube" style="font-size:.58rem;"></i> {{ $subject }}</span>
                                @else
                                    <span class="empty-val">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-fingerprint"></i> Subject ID</div>
                            <div class="pr-detail-value">{{ $auditLog->subject_id ?? '' }}
                                @if(!$auditLog->subject_id)<span class="empty-val">—</span>@endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-user"></i> Actor</div>
                    <div class="pr-detail-value" style="padding:8px 12px;">
                        @if($auditLog->user)
                            <span class="pr-user-chip">
                                <span class="pr-user-avatar">{{ mb_substr($auditLog->user->name,0,2) }}</span>
                                {{ $auditLog->user->name }}
                                <span style="color:var(--pr-sub);font-size:.72rem;">#{{ $auditLog->user_id }}</span>
                            </span>
                        @else
                            <span style="font-size:.82rem;color:var(--pr-sub);font-style:italic;">
                                <i class="fas fa-robot me-1"></i>System / Unauthenticated
                            </span>
                        @endif
                    </div>
                </div>
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-network-wired"></i> IP / Host</div>
                    <div class="pr-detail-value">
                        @if($auditLog->host)
                            <span class="pr-ip-block">{{ $auditLog->host }}</span>
                        @else
                            <span class="empty-val">—</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ PROPERTIES ══ --}}
@if($props && !(is_array($props) && empty($props)))
<div class="pr-detail-card" style="margin-top:16px;">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-code"></i></div>
        <span class="pr-section-title">
            Properties
            @if($isDiff)
                <span style="font-size:.72rem;font-weight:500;color:var(--pr-sub);text-transform:none;letter-spacing:0;margin-left:6px;">· changed fields</span>
            @endif
        </span>
        @if($isDiff)
            <button type="button" class="pr-view-toggle ms-auto" id="viewToggle">
                <i class="fas fa-code"></i> Raw JSON
            </button>
        @endif
    </div>
    <div class="pr-detail-body">

        {{-- Diff view --}}
        @if($isDiff)
        <div id="diffView">
            @php
                $oldVals = $props['old'] ?? [];
                $newVals = $props['attributes'] ?? [];
                $allKeys = array_unique(array_merge(array_keys($oldVals), array_keys($newVals)));
                $changed = array_filter($allKeys, fn($k) => ($oldVals[$k]??'__MISS__') !== ($newVals[$k]??'__MISS__'));
            @endphp
            @if(count($changed) === 0)
                <div style="font-size:.82rem;color:var(--pr-sub);font-style:italic;padding:8px 0;">No field differences detected.</div>
            @else
                @foreach($changed as $key)
                    <div class="pr-diff-section">
                        <div class="pr-diff-fieldname">{{ $key }}</div>
                        <div class="pr-diff-cols">
                            @if(isset($oldVals[$key]))
                                <div class="pr-diff-col pr-diff-old">
                                    <div class="pr-diff-col-label">Before</div>
                                    <div class="pr-diff-old-body">
                                        <pre>{{ is_array($oldVals[$key]) ? json_encode($oldVals[$key], JSON_PRETTY_PRINT) : $oldVals[$key] }}</pre>
                                    </div>
                                </div>
                            @endif
                            @if(isset($newVals[$key]))
                                <div class="pr-diff-col pr-diff-new">
                                    <div class="pr-diff-col-label">After</div>
                                    <div class="pr-diff-new-body">
                                        <pre>{{ is_array($newVals[$key]) ? json_encode($newVals[$key], JSON_PRETTY_PRINT) : $newVals[$key] }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        @endif

        {{-- Raw JSON --}}
        <div id="rawView" class="{{ $isDiff ? 'pr-raw-view' : '' }}">
            <div class="pr-json-card">
                <div class="pr-json-toolbar">
                    <div style="display:flex;align-items:center;">
                        <div class="pr-json-dots">
                            <span class="pr-json-dot" style="background:#ff5f57;"></span>
                            <span class="pr-json-dot" style="background:#febc2e;"></span>
                            <span class="pr-json-dot" style="background:#28c840;"></span>
                        </div>
                        <span class="pr-json-filename">properties.json</span>
                    </div>
                    <button type="button" class="pr-json-copy" id="copyJsonBtn">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div class="pr-json-body">
                    <pre id="jsonOutput"></pre>
                </div>
            </div>
        </div>

    </div>
</div>
@endif

{{-- ══ ACTION FOOTER ══ --}}
<div class="pr-action-footer">
    <a href="{{ route('admin.audit-logs.index') }}" class="pr-btn-secondary">
        <i class="fas fa-arrow-left" style="font-size:.78rem;"></i> {{ trans('global.back_to_list') }}
    </a>
    <span style="font-size:.74rem;color:var(--pr-sub);font-family:'DM Sans',sans-serif;">
        <i class="fas fa-lock" style="font-size:.65rem;margin-right:4px;"></i>Audit logs are read-only
    </span>
</div>

</div>{{-- /pr-page --}}
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawProps = @json($auditLog->properties);

    function syntaxHighlight(json) {
        if (typeof json !== 'string') json = JSON.stringify(json, null, 2);
        return json
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(m) {
                let c='jn';
                if(/^"/.test(m)){c=/:$/.test(m)?'jk':'js'}
                else if(/true|false/.test(m)){c='jb'}
                else if(/null/.test(m)){c='jz'}
                return `<span class="${c}">${m}</span>`;
            });
    }

    const outputEl = document.getElementById('jsonOutput');
    if (outputEl && rawProps != null) {
        outputEl.innerHTML = syntaxHighlight(JSON.stringify(rawProps, null, 2));
    }

    const copyBtn = document.getElementById('copyJsonBtn');
    if (copyBtn && rawProps != null) {
        copyBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(JSON.stringify(rawProps, null, 2)).then(() => {
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => { copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy'; }, 2000);
            });
        });
    }

    const toggleBtn = document.getElementById('viewToggle');
    const diffView  = document.getElementById('diffView');
    const rawView   = document.getElementById('rawView');
    let showRaw = false;
    if (toggleBtn && diffView && rawView) {
        toggleBtn.addEventListener('click', function () {
            showRaw = !showRaw;
            if (showRaw) {
                rawView.classList.add('visible');
                diffView.style.display = 'none';
                this.innerHTML = '<i class="fas fa-exchange-alt"></i> Diff View';
            } else {
                rawView.classList.remove('visible');
                diffView.style.display = 'block';
                this.innerHTML = '<i class="fas fa-code"></i> Raw JSON';
            }
        });
    }
});
</script>
@endsection