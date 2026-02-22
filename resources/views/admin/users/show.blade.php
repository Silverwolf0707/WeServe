@extends('layouts.admin')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:      #064e3b; --pr-forest-mid: #065f46;
    --pr-lime:        #74ff70; --pr-lime-dim: #52e84e;
    --pr-lime-ghost:  rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
    --pr-surface:     #ffffff; --pr-surface2: #f0fdf4; --pr-muted: #ecfdf5;
    --pr-border:      #d1fae5; --pr-border-dark: #a7f3d0;
    --pr-text:        #052e22; --pr-sub: #3d7a62; --pr-danger: #ef4444;
    --pr-radius: 12px;
    --pr-shadow:   0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime: 0 2px 12px rgba(116,255,112,.25);
}
.pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }
.pr-hero { background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%); border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: var(--pr-shadow-lg); }
.pr-hero::before { content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius); background: radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%), radial-gradient(ellipse 180px 100px at 5% 80%, rgba(116,255,112,.07) 0%, transparent 70%); pointer-events: none; z-index: 0; }
.pr-hero::after { content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px; background: linear-gradient(to right, transparent, var(--pr-lime), transparent); border-radius: 2px; opacity: .55; }
.pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
.pr-hero-left  { display: flex; align-items: center; gap: 16px; }
.pr-hero-avatar { width: 52px; height: 52px; border-radius: 13px; background: rgba(116,255,112,.15); border: 1.5px solid rgba(116,255,112,.35); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 800; color: var(--pr-lime); text-transform: uppercase; flex-shrink: 0; letter-spacing: .02em; }
.pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
.pr-hero-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); border-radius: 20px; padding: 2px 10px; font-size: .72rem; font-weight: 600; color: var(--pr-lime); }
.pr-status-hero { display: inline-flex; align-items: center; gap: 4px; border-radius: 20px; padding: 2px 10px; font-size: .71rem; font-weight: 700; }
.pr-status-active    { background: rgba(116,255,112,.15); border: 1px solid rgba(116,255,112,.35); color: var(--pr-lime); }
.pr-status-inactive  { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.35); color: #fde68a; }
.pr-status-suspended { background: rgba(239,68,68,.15); border: 1px solid rgba(239,68,68,.35); color: #fca5a5; }
.pr-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.pr-btn-primary { display: inline-flex; align-items: center; gap: 7px; background: var(--pr-lime); color: var(--pr-forest); border: none; border-radius: 8px; padding: 8px 18px; font-size: .82rem; font-weight: 700; font-family: 'DM Sans', sans-serif; text-decoration: none; cursor: pointer; transition: background .18s, transform .15s; box-shadow: var(--pr-shadow-lime); white-space: nowrap; }
.pr-btn-primary:hover { background: var(--pr-lime-dim); color: var(--pr-forest); transform: translateY(-1px); }
.pr-btn-ghost { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,.08); color: rgba(255,255,255,.82); border: 1px solid rgba(255,255,255,.18); border-radius: 8px; padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; white-space: nowrap; }
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

/* detail cards */
.pr-detail-card { background: var(--pr-surface); border-radius: var(--pr-radius); border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden; margin-bottom: 16px; }
.pr-section-head { display: flex; align-items: center; gap: 10px; padding: 16px 24px 14px; border-bottom: 1px solid var(--pr-border); background: var(--pr-surface2); }
.pr-section-icon { width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0; background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); display: flex; align-items: center; justify-content: center; color: var(--pr-forest); font-size: .78rem; }
.pr-section-title { font-size: .82rem; font-weight: 700; color: var(--pr-forest); letter-spacing: .04em; text-transform: uppercase; }
.pr-detail-body { padding: 22px 24px; }
.pr-detail-field { margin-bottom: 20px; }
.pr-detail-field:last-child { margin-bottom: 0; }
.pr-detail-label { font-size: .68rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: var(--pr-sub); margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
.pr-detail-value { font-size: .88rem; font-weight: 500; color: var(--pr-text); background: var(--pr-surface2); border: 1px solid var(--pr-border); border-radius: 8px; padding: 9px 13px; line-height: 1.5; word-break: break-word; }
.pr-detail-value.id-val { font-weight: 800; font-size: .95rem; color: var(--pr-forest); }

/* status in detail */
.pr-status-badge { display: inline-flex; align-items: center; gap: 5px; border-radius: 20px; padding: 3px 12px; font-size: .76rem; font-weight: 700; }
.pr-status-badge.active    { background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); color: var(--pr-forest); }
.pr-status-badge.inactive  { background: rgba(245,158,11,.10); border: 1px solid rgba(245,158,11,.30); color: #92400e; }
.pr-status-badge.suspended { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.25); color: #991b1b; }

/* verified */
.pr-verified   { display: inline-flex; align-items: center; gap: 5px; background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); border-radius: 20px; padding: 3px 12px; font-size: .76rem; font-weight: 600; color: var(--pr-forest); }
.pr-unverified { display: inline-flex; align-items: center; gap: 5px; background: rgba(245,158,11,.10); border: 1px solid rgba(245,158,11,.30); border-radius: 20px; padding: 3px 12px; font-size: .76rem; font-weight: 600; color: #92400e; }

/* ip */
.pr-ip-block { font-family: 'Courier New', monospace; font-size: .82rem; background: var(--pr-surface2); border: 1px solid var(--pr-border); border-radius: 7px; padding: 7px 13px; color: var(--pr-sub); display: inline-block; }

/* role pills */
.pr-perms-wrap { display: flex; flex-wrap: wrap; gap: 6px; padding: 12px 14px; background: var(--pr-surface2); border: 1px solid var(--pr-border); border-radius: 8px; min-height: 46px; align-content: flex-start; }
.pr-role-pill { display: inline-flex; align-items: center; gap: 5px; background: rgba(6,78,59,.08); border: 1px solid rgba(6,78,59,.18); border-radius: 20px; padding: 3px 11px; font-size: .74rem; font-weight: 600; color: var(--pr-forest); }
.pr-role-pill i { font-size: .6rem; }
.pr-role-empty { font-size: .82rem; color: var(--pr-sub); font-style: italic; }

/* meta */
.pr-meta-line { display: flex; flex-direction: column; }
.pr-meta-date { font-size: .84rem; font-weight: 500; color: var(--pr-text); }
.pr-meta-ago  { font-size: .72rem; color: var(--pr-sub); margin-top: 2px; }

/* action footer */
.pr-action-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; background: var(--pr-surface); border-radius: var(--pr-radius); border: 1px solid var(--pr-border); padding: 16px 24px; box-shadow: var(--pr-shadow); }
.pr-btn-secondary { display: inline-flex; align-items: center; gap: 7px; background: var(--pr-muted); color: var(--pr-sub); border: 1px solid var(--pr-border-dark); border-radius: 8px; padding: 9px 18px; font-size: .84rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: background .18s; }
.pr-btn-secondary:hover { background: var(--pr-border-dark); color: var(--pr-text); }
.pr-btn-edit { display: inline-flex; align-items: center; gap: 7px; background: var(--pr-forest); color: var(--pr-lime); border: none; border-radius: 8px; padding: 9px 18px; font-size: .84rem; font-weight: 700; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: background .18s, transform .15s; box-shadow: 0 2px 8px rgba(6,78,59,.25); }
.pr-btn-edit:hover { background: var(--pr-forest-mid); color: var(--pr-lime); transform: translateY(-1px); }

@media (max-width: 768px) {
    .pr-hero-inner { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-detail-body { padding: 16px; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-avatar">{{ mb_substr($user->name, 0, 2) }}</div>
            <div>
                <div class="pr-hero-title">{{ $user->name }}</div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px;">
                    <span class="pr-hero-badge"><i class="fas fa-hashtag" style="font-size:.6rem;"></i> ID: {{ $user->id }}</span>
                    <span class="pr-status-hero pr-status-{{ $user->status }}">
                        <i class="fas fa-circle" style="font-size:.45rem;"></i> {{ ucfirst($user->status) }}
                    </span>
                    <span style="color:rgba(255,255,255,.4);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ $user->email }}</span>
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            @can('user_edit')
                <a href="{{ route('admin.users.edit', $user->id) }}" class="pr-btn-primary">
                    <i class="fas fa-edit" style="font-size:.75rem;"></i> Edit
                </a>
            @endcan
            <a href="{{ route('admin.users.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to List
            </a>
        </div>
    </div>
</div>

{{-- ══ ACCOUNT INFO ══ --}}
<div class="pr-detail-card">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-user"></i></div>
        <span class="pr-section-title">Account Information</span>
    </div>
    <div class="pr-detail-body">
        <div class="row g-4">
            <div class="col-md-1">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-hashtag"></i> {{ trans('cruds.user.fields.id') }}</div>
                    <div class="pr-detail-value id-val">{{ $user->id }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-user"></i> {{ trans('cruds.user.fields.name') }}</div>
                    <div class="pr-detail-value">{{ $user->name }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-envelope"></i> {{ trans('cruds.user.fields.email') }}</div>
                    <div class="pr-detail-value">{{ $user->email }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-circle"></i> Status</div>
                    <div class="pr-detail-value" style="padding: 8px 12px;">
                        <span class="pr-status-badge {{ $user->status }}">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i> {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-check-circle"></i> {{ trans('cruds.user.fields.email_verified_at') }}</div>
                    <div class="pr-detail-value" style="padding: 8px 12px;">
                        @if($user->email_verified_at)
                            @php $ev = is_string($user->email_verified_at) ? \Carbon\Carbon::parse($user->email_verified_at) : $user->email_verified_at; @endphp
                            <span class="pr-verified"><i class="fas fa-check-circle"></i> Verified — {{ $ev->format('M j, Y') }}</span>
                        @else
                            <span class="pr-unverified"><i class="fas fa-clock"></i> Not Verified</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ACTIVITY ══ --}}
<div class="pr-detail-card">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-chart-line"></i></div>
        <span class="pr-section-title">Activity & Timestamps</span>
    </div>
    <div class="pr-detail-body">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-sign-in-alt"></i> Last Login</div>
                    <div class="pr-detail-value">
                        @if($user->last_login_at)
                            @php $ll = is_string($user->last_login_at) ? \Carbon\Carbon::parse($user->last_login_at) : $user->last_login_at; @endphp
                            <div class="pr-meta-line">
                                <span class="pr-meta-date">{{ $ll->format('M j, Y g:i A') }}</span>
                                <span class="pr-meta-ago">{{ $ll->diffForHumans() }}</span>
                            </div>
                        @else
                            <span style="font-size:.82rem;color:var(--pr-sub);font-style:italic;">Never logged in</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-network-wired"></i> Last Login IP</div>
                    <div class="pr-detail-value">
                        @if($user->last_login_ip)
                            <span class="pr-ip-block">{{ $user->last_login_ip }}</span>
                        @else
                            <span style="font-size:.82rem;color:var(--pr-sub);">—</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-calendar-plus"></i> Account Created</div>
                    <div class="pr-detail-value">
                        @if($user->created_at)
                            @php $ca = is_string($user->created_at) ? \Carbon\Carbon::parse($user->created_at) : $user->created_at; @endphp
                            <div class="pr-meta-line">
                                <span class="pr-meta-date">{{ $ca->format('M j, Y g:i A') }}</span>
                                <span class="pr-meta-ago">{{ $ca->diffForHumans() }}</span>
                            </div>
                        @else
                            <span style="font-size:.82rem;color:var(--pr-sub);">—</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-pencil-alt"></i> Last Updated</div>
                    <div class="pr-detail-value">
                        @if($user->updated_at)
                            @php $ua = is_string($user->updated_at) ? \Carbon\Carbon::parse($user->updated_at) : $user->updated_at; @endphp
                            <div class="pr-meta-line">
                                <span class="pr-meta-date">{{ $ua->format('M j, Y g:i A') }}</span>
                                <span class="pr-meta-ago">{{ $ua->diffForHumans() }}</span>
                            </div>
                        @else
                            <span style="font-size:.82rem;color:var(--pr-sub);">—</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ROLES ══ --}}
<div class="pr-detail-card">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-user-tag"></i></div>
        <span class="pr-section-title">
            Roles
            <span style="display:inline-flex;align-items:center;gap:4px;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);border-radius:20px;padding:1px 9px;font-size:.68rem;font-weight:700;color:var(--pr-forest);margin-left:8px;vertical-align:middle;">
                {{ $user->roles->count() }}
            </span>
        </span>
    </div>
    <div class="pr-detail-body">
        <div class="pr-detail-label" style="margin-bottom:10px;"><i class="fas fa-user-tag"></i> {{ trans('cruds.user.fields.roles') }}</div>
        <div class="pr-perms-wrap">
            @forelse($user->roles as $role)
                <span class="pr-role-pill"><i class="fas fa-shield-alt"></i> {{ $role->title }}</span>
            @empty
                <span class="pr-role-empty">No roles assigned to this user.</span>
            @endforelse
        </div>
    </div>
</div>

{{-- ══ ACTION FOOTER ══ --}}
<div class="pr-action-footer">
    <a href="{{ route('admin.users.index') }}" class="pr-btn-secondary">
        <i class="fas fa-arrow-left" style="font-size:.78rem;"></i> {{ trans('global.back_to_list') }}
    </a>
    @can('user_edit')
        <a href="{{ route('admin.users.edit', $user->id) }}" class="pr-btn-edit">
            <i class="fas fa-edit" style="font-size:.78rem;"></i> Edit User
        </a>
    @endcan
</div>

</div>
@endsection