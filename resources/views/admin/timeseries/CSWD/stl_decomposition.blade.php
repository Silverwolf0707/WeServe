@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
    .stl-wrap { font-family: 'DM Sans', sans-serif; color: #052e22; }

    /* ── stat cards ─────────────────────────────── */
    .stl-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 20px; }
    .stl-stat { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); transition: transform .2s, box-shadow .2s; }
    .stl-stat:hover { transform: translateY(-3px); box-shadow: 0 4px 24px rgba(6,78,59,.16); }
    .stl-stat-inner { padding: 14px 16px 10px; display: flex; align-items: flex-start; justify-content: space-between; }
    .stl-stat-label { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.72); margin-bottom: 6px; }
    .stl-stat-value { font-size: 1.25rem; font-weight: 800; color: #fff; line-height: 1.1; letter-spacing: -.01em; }
    .stl-stat-icon  { width: 38px; height: 38px; border-radius: 9px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .95rem; color: #fff; flex-shrink: 0; }
    .stl-stat-foot  { padding: 7px 16px; font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); border-top: 1px solid rgba(255,255,255,.12); }
    .stl-c-blue  { background: linear-gradient(135deg,#1d4ed8,#3b82f6); }
    .stl-c-green { background: linear-gradient(135deg,#064e3b,#10b981); }
    .stl-c-amber { background: linear-gradient(135deg,#92400e,#f59e0b); }
    .stl-c-red   { background: linear-gradient(135deg,#991b1b,#ef4444); }

    /* ── shared card ────────────────────────────── */
    .stl-card { background: #fff; border-radius: 12px; border: 1px solid #d1fae5; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); margin-bottom: 18px; overflow: hidden; }
    .stl-card-hdr { display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: linear-gradient(135deg,#052e22 0%,#064e3b 100%); flex-wrap: wrap; }
    .stl-card-hdr-icon  { width: 28px; height: 28px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .72rem; color: #74ff70; flex-shrink: 0; }
    .stl-card-hdr-title { font-size: .88rem; font-weight: 700; color: #fff; }
    .stl-card-hdr-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
    .stl-card-body { padding: 18px; }

    .stl-label  { font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); white-space: nowrap; }
    .stl-select { height: 28px; border: 1px solid rgba(116,255,112,.28); border-radius: 7px; background: rgba(116,255,112,.08); color: #fff; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 26px 0 9px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(116,255,112,.7)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: border-color .15s, background .15s; }
    .stl-select:focus { border-color: rgba(116,255,112,.65); background-color: rgba(116,255,112,.14); }
    .stl-select option { background: #052e22; color: #fff; }

    /* ── interpretation insight ─────────────────── */
    .stl-insight { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1px solid #d1fae5; border-radius: 12px; padding: 18px 20px; }
    .stl-insight-hdr { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #d1fae5; }
    .stl-insight-hdr-icon  { width: 26px; height: 26px; border-radius: 7px; background: rgba(116,255,112,.15); border: 1px solid rgba(116,255,112,.3); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #064e3b; flex-shrink: 0; }
    .stl-insight-hdr-title { font-size: .84rem; font-weight: 700; color: #052e22; }
    #summary-content h6        { font-size: .82rem; font-weight: 700; color: #052e22; margin-bottom: 8px; }
    #summary-content p         { font-size: .8rem; color: #374151; line-height: 1.6; margin-bottom: 8px; }
    #summary-content strong    { color: #064e3b; }
    #summary-content ul        { font-size: .8rem; color: #374151; padding-left: 16px; margin-bottom: 8px; }
    #summary-content li        { margin-bottom: 4px; line-height: 1.5; }

    /* ── weekly insight panel ───────────────────── */
    .stl-weekly-insight    { background: #f0fdf4; border: 1px solid #d1fae5; border-radius: 10px; padding: 14px 16px; height: 100%; }
    .stl-weekly-insight h6 { font-size: .82rem; font-weight: 700; color: #052e22; margin-bottom: 8px; }
    #weekly-stl-insight p  { font-size: .79rem; color: #374151; line-height: 1.6; margin-bottom: 6px; }
    #weekly-stl-insight ul { font-size: .79rem; color: #374151; padding-left: 14px; }
    #weekly-stl-insight li { margin-bottom: 3px; }
    #weekly-stl-insight strong { color: #064e3b; }

    /* ── component pills ────────────────────────── */
    .stl-component-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .stl-cpill { padding: 3px 12px; border-radius: 20px; font-size: .72rem; font-weight: 600; border: 1px solid rgba(116,255,112,.28); background: rgba(116,255,112,.08); color: rgba(255,255,255,.75); cursor: pointer; transition: all .15s; font-family: 'DM Sans', sans-serif; }
    .stl-cpill:hover  { background: rgba(116,255,112,.18); }
    .stl-cpill.active { background: #74ff70; color: #052e22; border-color: #74ff70; }

    /* ── NEW: metrics panel ─────────────────────── */
    .stl-metrics-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 14px; }
    .stl-metric-box { background: #f0fdf4; border: 1px solid #d1fae5; border-radius: 10px; padding: 12px 14px; }
    .stl-metric-box-label { font-size: .64rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: 4px; }
    .stl-metric-box-value { font-size: 1.1rem; font-weight: 800; color: #052e22; line-height: 1.1; }
    .stl-metric-box-sub   { font-size: .68rem; color: #6b7280; margin-top: 3px; }
    .stl-metric-badge     { display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px; border-radius: 20px; font-size: .7rem; font-weight: 700; }
    .stl-metric-badge.good    { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .stl-metric-badge.warn    { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .stl-metric-badge.bad     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .stl-acf-bar-wrap   { display: flex; align-items: flex-end; gap: 3px; height: 36px; margin-top: 4px; }
    .stl-acf-bar        { flex: 1; border-radius: 3px 3px 0 0; min-height: 2px; transition: height .3s; }

    @media (max-width:1100px) { .stl-stat-grid { grid-template-columns: repeat(2,1fr); } .stl-metrics-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width:640px)  { .stl-stat-grid { grid-template-columns: 1fr 1fr; } .stl-card-hdr-controls { margin-left: 0; width: 100%; } .stl-metrics-grid { grid-template-columns: 1fr 1fr; } }
</style>

<div class="stl-wrap">

    {{-- ── KPI STAT CARDS ──────────────────────────── --}}
    <div class="stl-stat-grid">
        <div class="stl-stat stl-c-blue">
            <div class="stl-stat-inner">
                <div><div class="stl-stat-label">Top Assistance</div><div class="stl-stat-value" id="topAssistance">—</div></div>
                <div class="stl-stat-icon"><i class="fas fa-hand-holding-medical"></i></div>
            </div>
            <div class="stl-stat-foot">Highest volume type</div>
        </div>
        <div class="stl-stat stl-c-green">
            <div class="stl-stat-inner">
                <div><div class="stl-stat-label">Most Common</div><div class="stl-stat-value" id="mostCommonCategory">—</div></div>
                <div class="stl-stat-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stl-stat-foot">Leading case category</div>
        </div>
        <div class="stl-stat stl-c-amber">
            <div class="stl-stat-inner">
                <div><div class="stl-stat-label">Total Applicants</div><div class="stl-stat-value" id="totalApplicants">—</div></div>
                <div class="stl-stat-icon"><i class="fas fa-user-check"></i></div>
            </div>
            <div class="stl-stat-foot">All-time records</div>
        </div>
        <div class="stl-stat stl-c-red">
            <div class="stl-stat-inner">
                <div><div class="stl-stat-label">Avg. Processing Time</div><div class="stl-stat-value" id="averageProcessingTime">—</div></div>
                <div class="stl-stat-icon"><i class="fas fa-clock"></i></div>
            </div>
            <div class="stl-stat-foot">From intake to disburse</div>
        </div>
    </div>

    {{-- ── MONTHLY PATTERN CHART ────────────────────── --}}
    <div class="stl-card">
        <div class="stl-card-hdr">
            <div class="stl-card-hdr-icon"><i class="fas fa-chart-line"></i></div>
            <span class="stl-card-hdr-title">Monthly Application Pattern</span>
            <div class="stl-card-hdr-controls">
                <div class="stl-component-pills">
                    <button class="stl-cpill active" data-component="observed">Observed</button>
                    <button class="stl-cpill" data-component="seasonal">Seasonal</button>
                    <button class="stl-cpill" data-component="trend">Trend</button>
                    <button class="stl-cpill" data-component="residual">Residual</button>
                </div>
                <span class="stl-label">Year:</span>
                <select id="yearSelector" class="stl-select" style="min-width:80px;"></select>
                <span class="stl-label">Category:</span>
                <select id="caseCategorySelector" class="stl-select" style="min-width:120px;"></select>
            </div>
        </div>
        <div class="stl-card-body">
            <canvas id="combinedChart" height="300" style="width:100%;max-height:320px;"></canvas>
        </div>
    </div>

    {{-- ── INTERPRETATION ───────────────────────────── --}}
    <div class="stl-card">
        <div class="stl-card-hdr">
            <div class="stl-card-hdr-icon"><i class="fas fa-lightbulb"></i></div>
            <span class="stl-card-hdr-title">Interpretation</span>
        </div>
        <div class="stl-card-body" style="padding:16px 18px;">
            <div class="stl-insight">
                <div class="stl-insight-hdr">
                    <div class="stl-insight-hdr-icon"><i class="fas fa-brain"></i></div>
                    <span class="stl-insight-hdr-title" id="insight-label">Observed Data Analysis</span>
                </div>
                <div id="summary-content">
                    <p style="color:#9ca3af;font-size:.8rem;">Select a component and category above to generate insights.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── NEW: MODEL ACCURACY METRICS ─────────────── --}}
    <div class="stl-card">
        <div class="stl-card-hdr">
            <div class="stl-card-hdr-icon"><i class="fas fa-ruler-combined"></i></div>
            <span class="stl-card-hdr-title">Model Accuracy Metrics</span>
            <span style="margin-left:auto;font-size:.72rem;color:rgba(255,255,255,.5);font-weight:500;">
                For selected category &amp; component
            </span>
        </div>
        <div class="stl-card-body">

            {{-- metric boxes --}}
            <div class="stl-metrics-grid" id="metricsGrid">
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">MAE</div>
                    <div class="stl-metric-box-value" id="m-mae">—</div>
                    <div class="stl-metric-box-sub">Mean Absolute Error</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">RMSE</div>
                    <div class="stl-metric-box-value" id="m-rmse">—</div>
                    <div class="stl-metric-box-sub">Root Mean Sq. Error</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">MAPE</div>
                    <div class="stl-metric-box-value" id="m-mape">—</div>
                    <div class="stl-metric-box-sub">Mean Abs. % Error</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">Seasonality Strength</div>
                    <div class="stl-metric-box-value" id="m-ss">—</div>
                    <div class="stl-metric-box-sub">0 = none · 1 = strong</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">Trend Strength</div>
                    <div class="stl-metric-box-value" id="m-ts">—</div>
                    <div class="stl-metric-box-sub">0 = none · 1 = strong</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">Durbin-Watson</div>
                    <div class="stl-metric-box-value" id="m-dw">—</div>
                    <div class="stl-metric-box-sub">Residual autocorrelation</div>
                </div>
            </div>

            {{-- residual diagnostics row --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label">Residual Mean / Std</div>
                    <div style="display:flex;align-items:baseline;gap:8px;margin-top:4px;">
                        <span class="stl-metric-box-value" id="m-rmean">—</span>
                        <span style="font-size:.72rem;color:#6b7280;">/ <span id="m-rstd">—</span></span>
                    </div>
                    <div class="stl-metric-box-sub">Mean ≈ 0 is ideal</div>
                </div>
                <div class="stl-metric-box">
                    <div class="stl-metric-box-label" style="margin-bottom:2px;">
                        Residual ACF (lags 1-6)
                        <span id="m-acf-badge" class="stl-metric-badge good" style="margin-left:6px;">white noise ✓</span>
                    </div>
                    <div class="stl-acf-bar-wrap" id="m-acf-bars"></div>
                    <div class="stl-metric-box-sub">Bars near 0 = no autocorrelation</div>
                </div>
            </div>

            {{-- interpretation footer --}}
            <div id="metrics-footer" style="margin-top:12px;padding:12px 14px;background:#f0fdf4;border:1px solid #d1fae5;border-radius:10px;font-size:.8rem;color:#374151;line-height:1.6;"></div>
        </div>
    </div>
    <div class="stl-card" id="metrics-guide-card">
    <div class="stl-card-hdr" style="cursor:pointer;" onclick="toggleMetricsGuide()">
        <div class="stl-card-hdr-icon"><i class="fas fa-book-open"></i></div>
        <span class="stl-card-hdr-title">How to Read the Metrics</span>
        <span style="margin-left:auto;font-size:.72rem;color:rgba(255,255,255,.5);" id="guide-toggle-label">Click to expand ▾</span>
    </div>
    <div class="stl-card-body" id="metrics-guide-body" style="display:none;">

        {{-- FIT QUALITY --}}
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:10px;">Fit quality — how accurate is the model?</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:10px;margin-bottom:18px;">
            @foreach([
                ['MAE', 'Mean Absolute Error', 'On average, how many applications/month did the model get wrong? Lower is better. MAE = 5 means estimates are off by ~5 per month.', 'Low <5 · Great', '5–15 · OK', '>15 · Review'],
                ['RMSE', 'Root Mean Square Error', 'Like MAE but penalises big mistakes more. If RMSE is much larger than MAE, a few months had very large errors.', 'Close to MAE · Good', '2× MAE · Some spikes', '>3× MAE · Outliers'],
                ['MAPE', 'Mean Absolute % Error', 'Error as a percentage — easy to compare across categories of different sizes. 10% = off by 10% on average.', '<10% · Excellent', '10–25% · Fair', '>25% · Review'],
            ] as [$abbr, $full, $desc, $good, $warn, $bad])
            <div style="background:#f0fdf4;border:1px solid #d1fae5;border-radius:10px;padding:13px 15px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;">
                    <span style="font-size:.84rem;font-weight:700;color:#052e22;">{{ $abbr }}</span>
                    <span style="font-size:.68rem;padding:2px 8px;border-radius:20px;background:#dbeafe;color:#1e40af;font-weight:600;">fit</span>
                </div>
                <p style="font-size:.77rem;color:#374151;line-height:1.6;margin-bottom:7px;">{{ $desc }}</p>
                <div style="display:flex;gap:0;border-radius:6px;overflow:hidden;font-size:.7rem;font-weight:600;">
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#d1fae5;color:#065f46;">{{ $good }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fef3c7;color:#92400e;">{{ $warn }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fee2e2;color:#991b1b;">{{ $bad }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- STRENGTH --}}
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:10px;">Component strength — what drives the data?</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:10px;margin-bottom:18px;">
            @foreach([
                ['Seasonality Strength', 'How much variation is caused by recurring calendar patterns (e.g. busy every January)? High = certain months are consistently busier.', 'Weak <35%', 'Moderate 35–64%', 'Strong >64%', 'High = plan ahead for predictable busy months.'],
                ['Trend Strength', 'Is the long-term direction of caseload (up, down, flat) consistent? High = clear sustained growth or decline.', 'Flat <35%', 'Moderate 35–64%', 'Clear >64%', 'High = factor trend into budget and staffing projections.'],
            ] as [$name, $desc, $bad, $warn, $good, $note])
            <div style="background:#f0fdf4;border:1px solid #d1fae5;border-radius:10px;padding:13px 15px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;">
                    <span style="font-size:.84rem;font-weight:700;color:#052e22;">{{ $name }}</span>
                    <span style="font-size:.68rem;padding:2px 8px;border-radius:20px;background:#d1fae5;color:#065f46;font-weight:600;">pattern</span>
                </div>
                <p style="font-size:.77rem;color:#374151;line-height:1.6;margin-bottom:7px;">{{ $desc }}</p>
                <div style="display:flex;gap:0;border-radius:6px;overflow:hidden;font-size:.7rem;font-weight:600;">
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fee2e2;color:#991b1b;">{{ $bad }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fef3c7;color:#92400e;">{{ $warn }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#d1fae5;color:#065f46;">{{ $good }}</div>
                </div>
                <p style="font-size:.68rem;color:#6b7280;margin-top:6px;font-style:italic;">{{ $note }}</p>
            </div>
            @endforeach
        </div>

        {{-- RESIDUAL --}}
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:10px;">Residual diagnostics — is the model well-fitted?</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:10px;margin-bottom:14px;">
            @foreach([
                ['Durbin-Watson', 'residual', 'Checks if leftover errors are truly random. Near 2 = model has no predictable mistakes.', '<1.5 · Pattern missed', '1.5–2.5 · Ideal', '>2.5 · Over-corrected'],
                ['Residual Mean & Std', 'residual', 'Mean near 0 = model is unbiased. High Std = large unexplained swings (possible external events).', 'Mean ≠ 0 · Biased', 'Mean ≈ 0, High Std', 'Mean ≈ 0, Low Std · Best'],
                ['Residual ACF', 'residual', 'Each bar = whether one month\'s error predicts the next. Bars near 0 = random (good). Tall red bars = missed pattern.', 'Any bar >0.3 · Problem', '—', 'All bars <0.3 · Ideal'],
            ] as [$name, $tag, $desc, $bad, $warn, $good])
            <div style="background:#f0fdf4;border:1px solid #d1fae5;border-radius:10px;padding:13px 15px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;">
                    <span style="font-size:.84rem;font-weight:700;color:#052e22;">{{ $name }}</span>
                    <span style="font-size:.68rem;padding:2px 8px;border-radius:20px;background:#fef3c7;color:#92400e;font-weight:600;">{{ $tag }}</span>
                </div>
                <p style="font-size:.77rem;color:#374151;line-height:1.6;margin-bottom:7px;">{{ $desc }}</p>
                <div style="display:flex;gap:0;border-radius:6px;overflow:hidden;font-size:.7rem;font-weight:600;">
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fee2e2;color:#991b1b;">{{ $bad }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#fef3c7;color:#92400e;">{{ $warn }}</div>
                    <div style="flex:1;padding:3px 5px;text-align:center;background:#d1fae5;color:#065f46;">{{ $good }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Quick tip --}}
        <div style="background:#f0fdf4;border-left:3px solid #10b981;border-radius:0 10px 10px 0;padding:12px 16px;font-size:.8rem;color:#374151;line-height:1.6;">
            <strong style="color:#052e22;">Quick rule of thumb:</strong>
            Low MAE/MAPE + Durbin-Watson between 1.5–2.5 + "white noise ✓" badge = the decomposition is reliable.
            Use trend and seasonal components confidently for planning. Any metric outside range = treat charts as signals, not exact forecasts.
        </div>

    </div>
</div>

    {{-- ── WEEKLY PATTERN ───────────────────────────── --}}
    <div class="stl-card">
        <div class="stl-card-hdr">
            <div class="stl-card-hdr-icon"><i class="fas fa-calendar-week"></i></div>
            <span class="stl-card-hdr-title">Weekly Application Pattern</span>
        </div>
        <div class="stl-card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <canvas id="weeklyStlChart" height="220"></canvas>
                </div>
                <div class="col-md-4">
                    <div class="stl-weekly-insight">
                        <h6><i class="fas fa-lightbulb me-2" style="color:#f59e0b;font-size:.75rem;"></i>Weekly Interpretation</h6>
                        <div id="weekly-stl-insight" style="color:#9ca3af;font-size:.79rem;">Loading weekly insights…</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- hidden sync select --}}
<select id="chartSelector" style="display:none;">
    <option value="observed">Observed</option>
    <option value="seasonal">Seasonal</option>
    <option value="trend">Trend</option>
    <option value="residual">Residual</option>
</select>

<script>
// ── pill toggle ──────────────────────────────────────────────────────────────
document.querySelectorAll('.stl-cpill').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.stl-cpill').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const comp = this.dataset.component;
        document.getElementById('chartSelector').value = comp;
        const insightLabels = {
            observed: 'Observed Data Analysis',
            seasonal: 'Seasonal Pattern Analysis',
            trend:    'Trend Direction Analysis',
            residual: 'Residual / Anomaly Analysis'
        };
        document.getElementById('insight-label').textContent = insightLabels[comp] || 'Analysis';
        loadStlData(
            document.getElementById('caseCategorySelector').value,
            comp,
            document.getElementById('yearSelector').value
        );
    });
});

// ── selector change ──────────────────────────────────────────────────────────
['yearSelector','caseCategorySelector'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => {
        loadStlData(
            document.getElementById('caseCategorySelector').value,
            document.getElementById('chartSelector').value,
            document.getElementById('yearSelector').value
        );
    });
});
</script>

<script>
// ── weekly STL ───────────────────────────────────────────────────────────────
async function loadWeeklyStl() {
    try {
        const res  = await fetch('/admin/timeseries/get-weekly-stl');
        const json = await res.json();
        const data   = json.weekly_stl.weekday_seasonality;
        const labels = Object.keys(data);
        const values = Object.values(data);
        const ctx    = document.getElementById('weeklyStlChart').getContext('2d');
        if (window.weeklyChart) window.weeklyChart.destroy();
        window.weeklyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Seasonal Effect',
                    data: values,
                    backgroundColor: values.map(v => v > 0 ? 'rgba(16,185,129,0.65)' : 'rgba(239,68,68,0.65)'),
                    borderRadius: 7,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#052e22', titleColor: '#74ff70', bodyColor: '#fff',
                        callbacks: { label: ctx => `Seasonal effect: ${ctx.raw.toFixed(2)}` }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Deviation from Average', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                        grid: { color: 'rgba(6,78,59,.06)' },
                        ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280' }
                    },
                    x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 }, color: '#374151' } }
                }
            }
        });
        generateWeeklyInsight(values, labels);
    } catch (err) {
        console.error('Failed to load weekly STL', err);
        document.getElementById('weekly-stl-insight').innerHTML = '<span style="color:#ef4444;">Failed to load weekly data.</span>';
    }
}

function generateWeeklyInsight(values, labels) {
    const absAvg = values.map(v => Math.abs(v)).reduce((a,b) => a+b, 0) / values.length;
    const max    = Math.max(...values), min = Math.min(...values);
    const peakDay = labels[values.indexOf(max)], lowDay = labels[values.indexOf(min)];
    let insight = absAvg < 0.5
        ? `<p>Application activity is <strong>uniformly distributed</strong> across the week — no significant spikes or drops on any particular day.</p>
           <p><strong>Implication:</strong> Current staffing levels appear well-matched to demand. No weekday-specific adjustments are needed.</p>
           <p><strong>Tip:</strong> Re-check this analysis after holidays or policy changes that may shift applicant volume.</p>`
        : `<p>A clear <strong>weekday pattern</strong> exists. Peak day is <strong>${peakDay}</strong>; quietest day is <strong>${lowDay}</strong>.</p>
           <p><strong>Operations:</strong> Schedule additional staff on <strong>${peakDay}</strong> to prevent processing delays.</p>
           <ul>
               <li>Align shift schedules to peak and off-peak days.</li>
               <li>Monitor for seasonal shifts in the weekly rhythm.</li>
               <li>Pre-stage resources before <strong>${peakDay}</strong>.</li>
           </ul>`;
    document.getElementById('weekly-stl-insight').innerHTML = insight;
}
</script>

<script>
// ── main STL loader ──────────────────────────────────────────────────────────
async function loadStlData(category = 'ALL', component = 'observed', year = 'ALL') {
    const chartContainer = document.getElementById('combinedChart').parentElement;
    try {
        const res  = await fetch('/admin/timeseries/get-stl-json?type=cswd');
        const json = await res.json();

        if (!json || Object.keys(json).length === 0) {
            chartContainer.innerHTML = `<div class="text-center text-muted py-5" style="font-family:'DM Sans',sans-serif;">
                <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;color:#f59e0b;margin-bottom:8px;display:block;"></i>
                <strong>STL Chart unavailable — insufficient data.</strong><br>
                <small>Please upload at least 1–2 years of records to generate decomposition.</small>
            </div>`;
            return;
        }

        // populate category selector once
        const caseSelector = document.getElementById('caseCategorySelector');
        if (caseSelector.options.length === 0) {
            caseSelector.innerHTML = '';
            const allOpt = document.createElement('option'); allOpt.value = 'ALL'; allOpt.textContent = 'ALL';
            caseSelector.appendChild(allOpt);
            Object.keys(json).forEach(cat => {
                const opt = document.createElement('option'); opt.value = cat; opt.textContent = cat;
                caseSelector.appendChild(opt);
            });
        }
        if (!category) category = caseSelector.value || 'ALL';
        caseSelector.value = category;

        // populate year selector once
        const yearSelector = document.getElementById('yearSelector');
        if (yearSelector.options.length === 0) {
            yearSelector.innerHTML = '';
            const allYearOpt = document.createElement('option'); allYearOpt.value = 'ALL'; allYearOpt.textContent = 'ALL';
            yearSelector.appendChild(allYearOpt);
            const allDates = category === 'ALL' ? json[Object.keys(json)[0]].dates : json[category].dates;
            [...new Set(allDates.map(d => d.split('-')[0]))].sort((a,b) => b-a).forEach(y => {
                const opt = document.createElement('option'); opt.value = y; opt.textContent = y;
                yearSelector.appendChild(opt);
            });
        }
        if (!year) year = yearSelector.value || 'ALL';
        yearSelector.value = year;

        // build data slice
        let labels = [], dataForYear = [];
        if (category === 'ALL') {
            const cats = Object.keys(json);
            const dates = json[cats[0]].dates;
            const idxs  = dates.map((d,i) => (year === 'ALL' || d.startsWith(year+'-')) ? i : -1).filter(i => i >= 0);
            dataForYear = idxs.map(i => cats.reduce((sum,c) => sum + (json[c][component][i]||0), 0));
            labels      = idxs.map(i => dates[i]);
        } else {
            const ds   = json[category];
            const idxs = ds.dates.map((d,i) => (year === 'ALL' || d.startsWith(year+'-')) ? i : -1).filter(i => i >= 0);
            dataForYear = idxs.map(i => ds[component][i]);
            labels      = idxs.map(i => ds.dates[i]);
        }

        if (labels.length < 12) {
            chartContainer.innerHTML = `<div class="text-center text-muted py-5" style="font-family:'DM Sans',sans-serif;">
                <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;color:#f59e0b;margin-bottom:8px;display:block;"></i>
                <strong>STL Chart requires 12+ months of data.</strong><br>
                <small>Currently showing ${labels.length} months. Please add more records.</small>
            </div>`;
            return;
        }

        if (!document.getElementById('combinedChart')) {
            chartContainer.innerHTML = `<canvas id="combinedChart" height="300" style="width:100%;max-height:320px;"></canvas>`;
        }

        renderChart(labels, dataForYear, component);
        updateSummaryText(component, category, year, dataForYear, labels);

        // ── update metrics panel ─────────────────────────────────────────
        const metricsSource = category === 'ALL' ? null : json[category];
        updateMetricsPanel(metricsSource, category);

    } catch (err) {
        console.error("Error loading STL data:", err);
        chartContainer.innerHTML = `<div class="text-center text-danger py-5" style="font-family:'DM Sans',sans-serif;">
            <i class="fas fa-times-circle" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
            Failed to load STL data. Please try again.
        </div>`;
    }
}

// ── render chart ─────────────────────────────────────────────────────────────
function renderChart(labels, data, component) {
    const palettes = {
        observed: { border: '#064e3b', fill: 'rgba(6,78,59,.12)',   point: '#74ff70' },
        trend:    { border: '#10b981', fill: 'rgba(16,185,129,.12)', point: '#34d399' },
        seasonal: { border: '#f59e0b', fill: 'rgba(245,158,11,.12)', point: '#fcd34d' },
        residual: { border: '#ef4444', fill: 'rgba(239,68,68,.12)',  point: '#fca5a5' }
    };
    const pal = palettes[component] || palettes.observed;
    const ctx = document.getElementById('combinedChart').getContext('2d');
    if (window.chartInstance) window.chartInstance.destroy();

    const displayLabels = labels.map(d => {
        const [y,m] = d.split('-');
        return `${new Date(y,parseInt(m)-1).toLocaleString('default',{month:'short'})} ${y}`;
    });
    let yMin = Math.min(...data) * 0.95;
    let yMax = Math.max(...data) * 1.05;
    if (component === 'residual') { const am = Math.max(...data.map(v=>Math.abs(v))); yMin = -am*1.1; yMax = am*1.1; }

    window.chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: displayLabels,
            datasets: [{
                label: component.charAt(0).toUpperCase() + component.slice(1),
                data,
                borderColor: pal.border, backgroundColor: pal.fill,
                pointBackgroundColor: pal.point, pointBorderColor: pal.border,
                pointBorderWidth: 2, pointRadius: 4, fill: true, tension: 0.4, borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#052e22', titleColor: '#74ff70', bodyColor: '#fff',
                    borderColor: 'rgba(116,255,112,.3)', borderWidth: 1, padding: 10,
                    titleFont: { family: 'DM Sans', size: 12 }, bodyFont: { family: 'DM Sans', size: 12 }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Month', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                    grid: { color: 'rgba(6,78,59,.05)' },
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280', maxRotation: 45 }
                },
                y: {
                    beginAtZero: false, min: yMin, max: yMax,
                    grid: { color: 'rgba(6,78,59,.05)' },
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280' }
                }
            }
        }
    });
}

// ── NEW: update metrics panel ─────────────────────────────────────────────────
function updateMetricsPanel(ds, category) {
    // If "ALL" is selected, metrics aren't aggregable — show a notice
    if (!ds || !ds.metrics) {
        ['m-mae','m-rmse','m-mape','m-ss','m-ts','m-dw','m-rmean','m-rstd'].forEach(id => {
            document.getElementById(id).textContent = '—';
        });
        document.getElementById('m-acf-bars').innerHTML = '';
        document.getElementById('m-acf-badge').className = 'stl-metric-badge warn';
        document.getElementById('m-acf-badge').textContent = 'select a category';
        document.getElementById('metrics-footer').innerHTML =
            '<i class="fas fa-info-circle" style="color:#f59e0b;margin-right:6px;"></i>' +
            'Select a specific category to view per-category accuracy metrics.';
        return;
    }

    const m = ds.metrics;

    // ── fill values ──
    document.getElementById('m-mae').textContent  = m.mae  != null ? m.mae.toFixed(2)  : '—';
    document.getElementById('m-rmse').textContent = m.rmse != null ? m.rmse.toFixed(2) : '—';
    document.getElementById('m-mape').textContent = m.mape != null ? m.mape.toFixed(2) + '%' : 'N/A';
    document.getElementById('m-ss').textContent   = m.seasonality_strength != null ? (m.seasonality_strength * 100).toFixed(1) + '%' : '—';
    document.getElementById('m-ts').textContent   = m.trend_strength       != null ? (m.trend_strength       * 100).toFixed(1) + '%' : '—';
    document.getElementById('m-dw').textContent   = m.durbin_watson        != null ? m.durbin_watson.toFixed(3) : '—';
    document.getElementById('m-rmean').textContent = m.residual_mean       != null ? m.residual_mean.toFixed(3) : '—';
    document.getElementById('m-rstd').textContent  = m.residual_std        != null ? m.residual_std.toFixed(3)  : '—';

    // ── ACF mini bars ──
    const acfBarsEl = document.getElementById('m-acf-bars');
    acfBarsEl.innerHTML = '';
    if (m.residual_acf) {
        const acfVals = Object.values(m.residual_acf);
        const maxAbs  = Math.max(...acfVals.map(v => Math.abs(v)), 0.01);
        acfVals.forEach((v, i) => {
            const bar = document.createElement('div');
            bar.className = 'stl-acf-bar';
            const pct = Math.abs(v) / maxAbs * 100;
            bar.style.cssText = `height:${Math.max(pct, 5)}%;background:${Math.abs(v) > 0.3 ? '#ef4444' : '#10b981'};`;
            bar.title = `Lag ${i+1}: ${v.toFixed(4)}`;
            acfBarsEl.appendChild(bar);
        });
    }

    // ── ACF badge ──
    const acfBadge = document.getElementById('m-acf-badge');
    if (m.residual_has_autocorrelation) {
        acfBadge.className = 'stl-metric-badge warn';
        acfBadge.textContent = '⚠ autocorrelation detected';
    } else {
        acfBadge.className = 'stl-metric-badge good';
        acfBadge.textContent = 'white noise ✓';
    }

    // ── Durbin-Watson badge color on the DW box ──
    const dw = m.durbin_watson;
    const dwEl = document.getElementById('m-dw');
    dwEl.style.color = (dw >= 1.5 && dw <= 2.5) ? '#065f46' : '#991b1b';

    // ── plain-English footer ──
    const mapeStr = m.mape != null ? `MAPE of <strong>${m.mape.toFixed(2)}%</strong>` : 'MAPE unavailable (zero-heavy data)';
    const ssLevel = m.seasonality_strength > 0.64 ? 'strong' : m.seasonality_strength > 0.35 ? 'moderate' : 'weak';
    const tsLevel = m.trend_strength       > 0.64 ? 'strong' : m.trend_strength       > 0.35 ? 'moderate' : 'weak';
    const dwOk    = dw >= 1.5 && dw <= 2.5;
    const acfOk   = !m.residual_has_autocorrelation;

    document.getElementById('metrics-footer').innerHTML = `
        <strong style="color:#052e22;">How to read these metrics for <em>${category}</em>:</strong><br>
        On average the model's reconstruction deviates by <strong>${m.mae.toFixed(2)} applications/month</strong> (MAE),
        with ${mapeStr}. The seasonal pattern is <strong>${ssLevel}</strong>
        (${(m.seasonality_strength*100).toFixed(1)}%) and the trend is <strong>${tsLevel}</strong>
        (${(m.trend_strength*100).toFixed(1)}%).
        ${dwOk && acfOk
            ? 'Residuals pass white-noise checks — the decomposition explains the data well.'
            : !dwOk
            ? `⚠ Durbin-Watson = ${dw.toFixed(2)} (outside 1.5–2.5) — residuals show autocorrelation; consider adjusting the STL period.`
            : '⚠ Residual ACF shows autocorrelation at some lags — there may be unexplained structure in the data.'
        }
    `;
}

// ── update interpretation text ────────────────────────────────────────────────
function updateSummaryText(component, category, year, data, labels) {
    const total   = data.reduce((a,b) => a+b, 0);
    const catText = category === 'ALL' ? 'all assistance types combined' : `"${category}"`;
    const yearText = year === 'ALL' ? 'all years' : year;
    const startVal = data[0]||0, endVal = data[data.length-1]||0;
    const diff = endVal - startVal, diffPct = startVal ? (diff/startVal)*100 : 0;
    let html = '';
    const badge = (text, color='#064e3b') =>
        `<span style="display:inline-flex;align-items:center;background:rgba(6,78,59,.08);border:1px solid rgba(6,78,59,.2);border-radius:6px;padding:1px 8px;font-size:.75rem;font-weight:700;color:${color};">${text}</span>`;

    switch (component) {
        case 'observed': {
            const avg = total/data.length, hiVal = Math.max(...data), loVal = Math.min(...data);
            const hiMonth = labels[data.indexOf(hiVal)], loMonth = labels[data.indexOf(loVal)];
            const trend_dir = diffPct >= 5 ? '📈 increasing' : diffPct <= -5 ? '📉 declining' : '➡️ stable';
            html = `
                <h6>🔍 Observed Data — ${yearText}</h6>
                <p>For <strong>${catText}</strong>, a total of ${badge(total.toLocaleString()+' applications')} were recorded, averaging <strong>${avg.toFixed(0)}</strong>/month.</p>
                <p>Peak: <strong>${hiMonth}</strong> (${hiVal.toFixed(0)}) · Slowest: <strong>${loMonth}</strong> (${loVal.toFixed(0)}) · Range: <strong>${(hiVal-loVal).toFixed(0)}</strong>.</p>
                <p>Overall demand is <strong>${trend_dir}</strong> (${diffPct >= 0 ? '+' : ''}${diffPct.toFixed(1)}% start to end).</p>
                <p><strong>What to do:</strong> Use peak months to plan staff coverage. If the gap is large, consider staggered intake scheduling.</p>`; break; }
        case 'trend': {
            const tDiff = endVal - startVal, tPct = startVal ? (tDiff/startVal)*100 : 0;
            const tMax = Math.max(...data), tMin = Math.min(...data);
            const tMaxMo = labels[data.indexOf(tMax)], tMinMo = labels[data.indexOf(tMin)];
            const tConclusion = tPct >= 10 ? 'Strong growth — budget and headcount plans should account for continued increases.'
                : tPct > 3  ? 'Steady upward trend. Plan for gradually increasing workload.'
                : tPct <= -10 ? 'Significant decline — investigate whether this is structural or a data gap.'
                : tPct < -3  ? 'Gradual decline — monitor closely.'
                : 'Broadly stable. Short-term fluctuations are noise.';
            html = `
                <h6>📈 Trend — ${yearText}</h6>
                <p>For <strong>${catText}</strong>, trend moved from ${badge(startVal.toFixed(1))} to ${badge(endVal.toFixed(1))} — ${tDiff >= 0 ? '+' : ''}${tDiff.toFixed(1)} (${tPct >= 0 ? '+' : ''}${tPct.toFixed(1)}%).</p>
                <p>Peak trend: <strong>${tMaxMo}</strong> (~${tMax.toFixed(1)}) · Lowest: <strong>${tMinMo}</strong> (~${tMin.toFixed(1)}).</p>
                <p><strong>Conclusion:</strong> ${tConclusion}</p>`; break; }
        case 'seasonal': {
            const sMax = Math.max(...data), sMin = Math.min(...data);
            const sPeaks   = data.map((v,i) => v===sMax ? labels[i] : null).filter(Boolean).join(', ');
            const sTroughs = data.map((v,i) => v===sMin ? labels[i] : null).filter(Boolean).join(', ');
            const sDiff = sMax - sMin, sStrong = sDiff > (0.25*(total/data.length));
            html = `
                <h6>📅 Seasonal Pattern — ${yearText}</h6>
                <p>Seasonality isolates the recurring calendar-driven pattern in <strong>${catText}</strong>.</p>
                <p>${sStrong
                    ? `Strong seasonal signal (gap: <strong>${sDiff.toFixed(0)}</strong>). Peaks: <strong>${sPeaks}</strong>; troughs: <strong>${sTroughs}</strong>.`
                    : `Mild seasonal fluctuations (gap: ${sDiff.toFixed(0)}). Slight highs around <strong>${sPeaks}</strong>.`}</p>
                <p><strong>Operational implication:</strong> ${sStrong
                    ? `Extra staffing during <strong>${sPeaks}</strong>; proactive outreach during <strong>${sTroughs}</strong>.`
                    : `No major seasonal prep needed. Maintain consistent capacity year-round.`}</p>`; break; }
        case 'residual': {
            const rAbs = data.map(v=>Math.abs(v)), rAvg = rAbs.reduce((a,b)=>a+b,0)/rAbs.length;
            const rMax = Math.max(...rAbs), rMaxMo = labels[rAbs.indexOf(rMax)], rMin = Math.min(...rAbs), rMinMo = labels[rAbs.indexOf(rMin)];
            const rConclusion = rMax > rAvg*2
                ? `Notable anomaly around <strong>${rMaxMo}</strong> — likely an external shock. Investigate.`
                : rAvg > 2
                ? 'Moderately elevated residuals — consider external factors or data quality.'
                : 'Small, evenly distributed residuals — the model explains the data well. No major anomalies.';
            html = `
                <h6>🔎 Residual / Anomaly — ${yearText}</h6>
                <p>Large residuals in <strong>${catText}</strong> indicate unexpected events not explained by trend or seasonality.</p>
                <p>Avg residual: ${badge(rAvg.toFixed(2))} · Largest anomaly: <strong>${rMaxMo}</strong> (${rMax.toFixed(2)}) · Smallest: <strong>${rMinMo}</strong> (${rMin.toFixed(2)}).</p>
                <p><strong>Interpretation:</strong> ${rConclusion}</p>
                <p>Use this view to flag months worth investigating — spikes often correspond to external events.</p>`; break; }
        default: html = '<p>No summary available.</p>';
    }
    document.getElementById('summary-content').innerHTML = html;
}
</script>

<script>
// ── dashboard summary KPI cards ───────────────────────────────────────────────
async function loadDashboardSummary() {
    try {
        const res  = await fetch('/admin/statistics/get-statistics?type=cswd');
        const json = await res.json();
        const s    = json.overall?.dashboard_summary;
        if (!s) return;
        document.getElementById('topAssistance').textContent        = s.top_assistance || 'N/A';
        document.getElementById('mostCommonCategory').textContent    = s.most_common_category || 'N/A';
        document.getElementById('totalApplicants').textContent       = s.total_applicants?.toLocaleString() || '0';
        document.getElementById('averageProcessingTime').textContent = s.average_processing_time || '0 days';
    } catch (err) { console.error('Error loading dashboard summary:', err); }
}

loadDashboardSummary();
loadStlData();
loadWeeklyStl();
</script>
<script>
function toggleMetricsGuide() {
    const body  = document.getElementById('metrics-guide-body');
    const label = document.getElementById('guide-toggle-label');
    const open  = body.style.display === 'none';
    body.style.display  = open ? 'block' : 'none';
    label.textContent = open ? 'Click to collapse ▴' : 'Click to expand ▾';
}
</script>