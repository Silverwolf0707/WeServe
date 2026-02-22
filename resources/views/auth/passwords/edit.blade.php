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
        }

        .pr-page { font-family:'DM Sans',sans-serif; color:var(--pr-text); padding:0 0 2rem; }

        /* Hero */
        .pr-hero { background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%); border-radius:var(--pr-radius); padding:22px 28px; margin-bottom:20px; position:relative; overflow:visible; box-shadow:var(--pr-shadow-lg); }
        .pr-hero::before { content:''; position:absolute; inset:0; border-radius:var(--pr-radius); background:radial-gradient(ellipse 380px 200px at 95% 50%,rgba(116,255,112,.13) 0%,transparent 65%),radial-gradient(ellipse 180px 100px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%); pointer-events:none; z-index:0; overflow:hidden; }
        .pr-hero::after  { content:''; position:absolute; top:0; left:28px; right:28px; height:2px; background:linear-gradient(to right,transparent,var(--pr-lime),transparent); border-radius:2px; opacity:.55; }
        .pr-hero-inner { display:flex; align-items:center; gap:16px; position:relative; z-index:1; }
        .pr-hero-icon { width:46px; height:46px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.30); border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:var(--pr-lime); flex-shrink:0; }
        .pr-hero-title { font-size:1.18rem; font-weight:700; color:#fff; letter-spacing:-.01em; margin:0 0 3px; }
        .pr-hero-sub { font-size:.78rem; color:rgba(255,255,255,.55); font-weight:500; }

        /* Cards */
        .pr-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); }
        .pr-card-header { display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--pr-border); background:var(--pr-surface2); border-radius:var(--pr-radius) var(--pr-radius) 0 0; }
        .pr-card-header-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; background:var(--pr-lime-ghost); color:var(--pr-forest); border:1px solid var(--pr-lime-border); }
        .pr-card-header-title { font-size:.88rem; font-weight:700; color:var(--pr-text); }
        .pr-card-body { padding:20px; }

        /* Fields */
        .pr-field { display:flex; flex-direction:column; gap:5px; margin-bottom:16px; }
        .pr-field:last-child { margin-bottom:0; }
        .pr-field label {
            font-size:.7rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.06em; color:var(--pr-sub);
        }
        .pr-field label .req { color:var(--pr-lime-dim); margin-left:2px; }
        .pr-field input {
            border:1.5px solid var(--pr-border-dark); border-radius:8px;
            padding:9px 13px; font-size:.84rem; font-family:'DM Sans',sans-serif;
            color:var(--pr-text); background:var(--pr-surface);
            transition:border-color .2s, box-shadow .2s; width:100%;
        }
        .pr-field input:focus {
            outline:none; border-color:var(--pr-forest-mid);
            box-shadow:0 0 0 3px rgba(6,78,59,.10);
        }
        .pr-field input.is-invalid { border-color:var(--pr-danger); }
        .pr-field input.is-invalid:focus { box-shadow:0 0 0 3px rgba(239,68,68,.12); }
        .pr-field .invalid-msg { font-size:.74rem; color:var(--pr-danger); font-weight:500; }

        /* Buttons */
        .pr-btn-save {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--pr-forest); color:var(--pr-lime);
            border:none; border-radius:9px; padding:9px 22px;
            font-size:.82rem; font-weight:700; font-family:'DM Sans',sans-serif;
            cursor:pointer; transition:all .18s;
            box-shadow:0 2px 12px rgba(116,255,112,.25);
        }
        .pr-btn-save:hover { background:var(--pr-forest-mid); transform:translateY(-1px); }

        /* Password strength */
        .pr-strength-bar { height:4px; border-radius:4px; background:var(--pr-border); margin-top:6px; overflow:hidden; }
        .pr-strength-fill { height:100%; border-radius:4px; transition:width .3s, background .3s; width:0; }

        /* Show/hide password toggle */
        .pr-pw-wrap { position:relative; }
        .pr-pw-wrap input { padding-right:38px; }
        .pr-pw-toggle {
            position:absolute; right:10px; top:50%; transform:translateY(-50%);
            background:none; border:none; color:var(--pr-sub); cursor:pointer;
            font-size:.78rem; padding:4px; transition:color .15s;
        }
        .pr-pw-toggle:hover { color:var(--pr-forest); }

        @media (max-width:768px) {
            .pr-grid { grid-template-columns:1fr !important; }
        }
    </style>

    <div class="pr-page">

        {{-- Hero --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-icon"><i class="fas fa-user-cog"></i></div>
                <div>
                    <div class="pr-hero-title">Account Settings</div>
                    <div class="pr-hero-sub">Manage your profile information and password</div>
                </div>
            </div>
        </div>

        {{-- Two-column grid --}}
        <div class="pr-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            {{-- ── Profile Info ── --}}
            <div class="pr-card">
                <div class="pr-card-header">
                    <div class="pr-card-header-icon"><i class="fas fa-id-card"></i></div>
                    <span class="pr-card-header-title">{{ trans('global.my_profile') }}</span>
                </div>
                <div class="pr-card-body">
                    <form method="POST" action="{{ route('profile.password.updateProfile') }}">
                        @csrf

                        <div class="pr-field">
                            <label for="name">{{ trans('cruds.user.fields.name') }}<span class="req">*</span></label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   placeholder="Your full name" required>
                            @if($errors->has('name'))
                                <span class="invalid-msg"><i class="fas fa-exclamation-circle" style="font-size:.68rem;"></i> {{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="pr-field">
                            <label for="email">{{ trans('cruds.user.fields.email') }}<span class="req">*</span></label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="your@email.com" required>
                            @if($errors->has('email'))
                                <span class="invalid-msg"><i class="fas fa-exclamation-circle" style="font-size:.68rem;"></i> {{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="pr-field">
                            <button type="submit" class="pr-btn-save">
                                <i class="fas fa-save" style="font-size:.76rem;"></i>
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Change Password ── --}}
            <div class="pr-card">
                <div class="pr-card-header">
                    <div class="pr-card-header-icon"><i class="fas fa-lock"></i></div>
                    <span class="pr-card-header-title">{{ trans('global.change_password') }}</span>
                </div>
                <div class="pr-card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf

                        <div class="pr-field">
                            <label for="password">New {{ trans('cruds.user.fields.password') }}<span class="req">*</span></label>
                            <div class="pr-pw-wrap">
                                <input type="password" name="password" id="password"
                                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Enter new password"
                                       oninput="updateStrength(this.value)"
                                       required>
                                <button type="button" class="pr-pw-toggle" onclick="togglePw('password', this)" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            {{-- Strength bar --}}
                            <div class="pr-strength-bar">
                                <div class="pr-strength-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthLabel" style="font-size:.68rem;color:var(--pr-sub);font-weight:600;margin-top:1px;"></span>
                            @if($errors->has('password'))
                                <span class="invalid-msg"><i class="fas fa-exclamation-circle" style="font-size:.68rem;"></i> {{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="pr-field">
                            <label for="password_confirmation">Repeat New {{ trans('cruds.user.fields.password') }}<span class="req">*</span></label>
                            <div class="pr-pw-wrap">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       placeholder="Confirm new password"
                                       oninput="checkMatch()"
                                       required>
                                <button type="button" class="pr-pw-toggle" onclick="togglePw('password_confirmation', this)" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <span id="matchLabel" style="font-size:.68rem;font-weight:600;margin-top:1px;"></span>
                        </div>

                        <div class="pr-field">
                            <button type="submit" class="pr-btn-save">
                                <i class="fas fa-key" style="font-size:.76rem;"></i>
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            const icon  = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function updateStrength(val) {
            const fill  = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            if (!val) { fill.style.width = '0'; label.textContent = ''; return; }

            let score = 0;
            if (val.length >= 8)             score++;
            if (/[A-Z]/.test(val))           score++;
            if (/[0-9]/.test(val))           score++;
            if (/[^A-Za-z0-9]/.test(val))   score++;

            const levels = [
                { pct:'20%', color:'#ef4444', text:'Weak' },
                { pct:'45%', color:'#f59e0b', text:'Fair' },
                { pct:'70%', color:'#3b82f6', text:'Good' },
                { pct:'100%',color:'#52e84e', text:'Strong' },
            ];
            const lv = levels[Math.min(score - 1, 3)] || levels[0];
            fill.style.width    = lv.pct;
            fill.style.background = lv.color;
            label.textContent   = lv.text;
            label.style.color   = lv.color;
            checkMatch();
        }

        function checkMatch() {
            const pw   = document.getElementById('password').value;
            const conf = document.getElementById('password_confirmation').value;
            const lbl  = document.getElementById('matchLabel');
            if (!conf) { lbl.textContent = ''; return; }
            if (pw === conf) {
                lbl.textContent = '✓ Passwords match';
                lbl.style.color = '#52e84e';
            } else {
                lbl.textContent = '✗ Passwords do not match';
                lbl.style.color = '#ef4444';
            }
        }
    </script>
@endsection