@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
    /* ═══════════════════════════════════════════
       STL Decomposition — Forest-green design system
       ═══════════════════════════════════════════ */
    .stl-wrap { font-family: 'DM Sans', sans-serif; color: #052e22; }

    /* ── Summary stat cards ── */
    .stl-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 20px; }
    .stl-stat { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); transition: transform .2s, box-shadow .2s; }
    .stl-stat:hover { transform: translateY(-3px); box-shadow: 0 4px 24px rgba(6,78,59,.16); }
    .stl-stat-inner { padding: 14px 16px 10px; display: flex; align-items: flex-start; justify-content: space-between; }
    .stl-stat-label { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.72); margin-bottom: 6px; }
    .stl-stat-value { font-size: 1.25rem; font-weight: 800; color: #fff; line-height: 1.1; letter-spacing: -.01em; }
    .stl-stat-icon { width: 38px; height: 38px; border-radius: 9px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .95rem; color: #fff; flex-shrink: 0; }
    .stl-stat-foot { padding: 7px 16px; font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); border-top: 1px solid rgba(255,255,255,.12); }
    .stl-c-blue   { background: linear-gradient(135deg,#1d4ed8,#3b82f6); }
    .stl-c-green  { background: linear-gradient(135deg,#064e3b,#10b981); }
    .stl-c-amber  { background: linear-gradient(135deg,#92400e,#f59e0b); }
    .stl-c-red    { background: linear-gradient(135deg,#991b1b,#ef4444); }

    /* ── Card base ── */
    .stl-card { background: #fff; border-radius: 12px; border: 1px solid #d1fae5; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); margin-bottom: 18px; overflow: hidden; }
    .stl-card-hdr { display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: linear-gradient(135deg,#052e22 0%,#064e3b 100%); flex-wrap: wrap; }
    .stl-card-hdr-icon { width: 28px; height: 28px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .72rem; color: #74ff70; flex-shrink: 0; }
    .stl-card-hdr-title { font-size: .88rem; font-weight: 700; color: #fff; }
    .stl-card-hdr-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
    .stl-card-body { padding: 18px; }

    /* ── Filter selects ── */
    .stl-label { font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); white-space: nowrap; }
    .stl-select { height: 28px; border: 1px solid rgba(116,255,112,.28); border-radius: 7px; background: rgba(116,255,112,.08); color: #fff; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 26px 0 9px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(116,255,112,.7)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: border-color .15s, background .15s; }
    .stl-select:focus { border-color: rgba(116,255,112,.65); background-color: rgba(116,255,112,.14); }
    .stl-select option { background: #052e22; color: #fff; }

    /* ── Insight panel ── */
    .stl-insight { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1px solid #d1fae5; border-radius: 12px; padding: 18px 20px; }
    .stl-insight-hdr { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #d1fae5; }
    .stl-insight-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(116,255,112,.15); border: 1px solid rgba(116,255,112,.3); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #064e3b; flex-shrink: 0; }
    .stl-insight-hdr-title { font-size: .84rem; font-weight: 700; color: #052e22; }
    #summary-content h6 { font-size: .82rem; font-weight: 700; color: #052e22; margin-bottom: 8px; }
    #summary-content p  { font-size: .8rem; color: #374151; line-height: 1.6; margin-bottom: 8px; }
    #summary-content strong { color: #064e3b; }
    #summary-content ul  { font-size: .8rem; color: #374151; padding-left: 16px; margin-bottom: 8px; }
    #summary-content li  { margin-bottom: 4px; line-height: 1.5; }

    /* ── Weekly insight ── */
    .stl-weekly-insight { background: #f0fdf4; border: 1px solid #d1fae5; border-radius: 10px; padding: 14px 16px; height: 100%; }
    .stl-weekly-insight h6 { font-size: .82rem; font-weight: 700; color: #052e22; margin-bottom: 8px; }
    #weekly-stl-insight p  { font-size: .79rem; color: #374151; line-height: 1.6; margin-bottom: 6px; }
    #weekly-stl-insight ul  { font-size: .79rem; color: #374151; padding-left: 14px; }
    #weekly-stl-insight li  { margin-bottom: 3px; }
    #weekly-stl-insight strong { color: #064e3b; }

    /* ── Component pills (chart selector) ── */
    .stl-component-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .stl-cpill { padding: 3px 12px; border-radius: 20px; font-size: .72rem; font-weight: 600; border: 1px solid rgba(116,255,112,.28); background: rgba(116,255,112,.08); color: rgba(255,255,255,.75); cursor: pointer; transition: all .15s; font-family: 'DM Sans', sans-serif; }
    .stl-cpill:hover { background: rgba(116,255,112,.18); }
    .stl-cpill.active { background: #74ff70; color: #052e22; border-color: #74ff70; }

    @media (max-width:1100px) { .stl-stat-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width:640px)  { .stl-stat-grid { grid-template-columns: 1fr 1fr; } .stl-card-hdr-controls { margin-left: 0; width: 100%; } }
</style>

<div class="stl-wrap">

    {{-- ── SUMMARY STAT CARDS ── --}}
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

    {{-- ── MONTHLY STL CHART ── --}}
    <div class="stl-card">
        <div class="stl-card-hdr">
            <div class="stl-card-hdr-icon"><i class="fas fa-chart-line"></i></div>
            <span class="stl-card-hdr-title">Monthly Application Pattern</span>
            <div class="stl-card-hdr-controls">
                {{-- Component pills replace the dropdown for better UX --}}
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

    {{-- ── INTERPRETATION ── --}}
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

    {{-- ── WEEKLY PATTERN ── --}}
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

</div>{{-- /stl-wrap --}}

{{-- ── Hidden chartSelector keeps old JS event logic working ── --}}
<select id="chartSelector" style="display:none;"><option value="observed">Observed</option><option value="seasonal">Seasonal</option><option value="trend">Trend</option><option value="residual">Residual</option></select>

<script>
/* ── Sync pill buttons → hidden select → existing loadStlData ── */
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
</script>

<script>
async function loadWeeklyStl() {
    try {
        const res = await fetch('/admin/timeseries/get-weekly-stl');
        const json = await res.json();
        const data = json.weekly_stl.weekday_seasonality;
        const labels = Object.keys(data);
        const values = Object.values(data);

        const ctx = document.getElementById('weeklyStlChart').getContext('2d');
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
                        backgroundColor: '#052e22',
                        titleColor: '#74ff70',
                        bodyColor: '#fff',
                        callbacks: { label: ctx => `Seasonal effect: ${ctx.raw.toFixed(2)}` }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Deviation from Average', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                        grid: { color: 'rgba(6,78,59,.06)' },
                        ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'DM Sans', size: 11 }, color: '#374151' }
                    }
                }
            }
        });

        generateWeeklyInsight(values, labels);
    } catch (err) {
        console.error('Failed to load weekly STL', err);
        document.getElementById('weekly-stl-insight').innerHTML = '<span style="color:#ef4444;">Failed to load weekly data.</span>';
    }
}
</script>

<script>
function generateWeeklyInsight(values, labels) {
    const absAvg = values.map(v => Math.abs(v)).reduce((a, b) => a + b, 0) / values.length;
    const max = Math.max(...values);
    const min = Math.min(...values);
    const peakDay = labels[values.indexOf(max)];
    const lowDay  = labels[values.indexOf(min)];
    let insight = '';

    if (absAvg < 0.5) {
        insight = `
        <p>Application activity is <strong>uniformly distributed</strong> across the week — no significant spikes or drops on any particular day.</p>
        <p><strong>Implication:</strong> Current staffing levels appear well-matched to demand. No weekday-specific adjustments are needed.</p>
        <p><strong>Tip:</strong> Re-check this analysis after holidays or policy changes that may shift applicant volume.</p>`;
    } else {
        insight = `
        <p>A clear <strong>weekday pattern</strong> exists. Peak day is <strong>${peakDay}</strong>; quietest day is <strong>${lowDay}</strong>.</p>
        <p><strong>Operations:</strong> Schedule additional staff on <strong>${peakDay}</strong> to prevent processing delays. Consider lighter staffing on <strong>${lowDay}</strong>.</p>
        <ul>
            <li>Align shift schedules to peak and off-peak days.</li>
            <li>Monitor for seasonal shifts in the weekly rhythm.</li>
            <li>Pre-stage resources (forms, interview slots) before <strong>${peakDay}</strong>.</li>
        </ul>`;
    }

    document.getElementById('weekly-stl-insight').innerHTML = insight;
}
</script>

<script>
async function loadStlData(category = 'ALL', component = 'observed', year = 'ALL') {
    const chartContainer = document.getElementById('combinedChart').parentElement;

    try {
        const res = await fetch('/admin/timeseries/get-stl-json?type=cswd');
        const json = await res.json();

        if (!json || Object.keys(json).length === 0) {
            chartContainer.innerHTML = `<div class="text-center text-muted py-5" style="font-family:'DM Sans',sans-serif;">
                <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;color:#f59e0b;margin-bottom:8px;display:block;"></i>
                <strong>STL Chart unavailable — insufficient data.</strong><br>
                <small>Please upload at least 1–2 years of records to generate decomposition.</small>
            </div>`;
            return;
        }

        const caseSelector = document.getElementById('caseCategorySelector');
        if (caseSelector.options.length === 0) {
            caseSelector.innerHTML = '';
            const allOpt = document.createElement('option');
            allOpt.value = 'ALL'; allOpt.textContent = 'ALL';
            caseSelector.appendChild(allOpt);
            Object.keys(json).forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat; opt.textContent = cat;
                caseSelector.appendChild(opt);
            });
        }

        if (!category) category = caseSelector.value || 'ALL';
        caseSelector.value = category;

        const yearSelector = document.getElementById('yearSelector');
        if (yearSelector.options.length === 0) {
            yearSelector.innerHTML = '';
            const allYearOpt = document.createElement('option');
            allYearOpt.value = 'ALL'; allYearOpt.textContent = 'ALL';
            yearSelector.appendChild(allYearOpt);
            let allDates = [];
            if (category === 'ALL') { allDates = json[Object.keys(json)[0]].dates; }
            else { allDates = json[category].dates; }
            const years = [...new Set(allDates.map(d => d.split('-')[0]))].sort((a, b) => b - a);
            years.forEach(y => {
                const opt = document.createElement('option');
                opt.value = y; opt.textContent = y;
                yearSelector.appendChild(opt);
            });
        }

        if (!year) year = yearSelector.value || 'ALL';
        yearSelector.value = year;

        let labels = [], dataForYear = [];

        if (category === 'ALL') {
            const cats = Object.keys(json);
            const dates = json[cats[0]].dates;
            const idxs = dates.map((d, i) => (year === 'ALL' || d.startsWith(year + '-')) ? i : -1).filter(i => i >= 0);
            dataForYear = idxs.map(i => cats.reduce((sum, c) => sum + (json[c][component][i] || 0), 0));
            labels = idxs.map(i => dates[i]);
        } else {
            const ds = json[category];
            const idxs = ds.dates.map((d, i) => (year === 'ALL' || d.startsWith(year + '-')) ? i : -1).filter(i => i >= 0);
            dataForYear = idxs.map(i => ds[component][i]);
            labels = idxs.map(i => ds.dates[i]);
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

    } catch (err) {
        console.error("Error loading STL data:", err);
        chartContainer.innerHTML = `<div class="text-center text-danger py-5" style="font-family:'DM Sans',sans-serif;">
            <i class="fas fa-times-circle" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
            Failed to load STL data. Please try again.
        </div>`;
    }
}

function renderChart(labels, data, component) {
    const palettes = {
        observed: { border: '#064e3b', fill: 'rgba(6,78,59,.12)', point: '#74ff70' },
        trend:    { border: '#10b981', fill: 'rgba(16,185,129,.12)', point: '#34d399' },
        seasonal: { border: '#f59e0b', fill: 'rgba(245,158,11,.12)', point: '#fcd34d' },
        residual: { border: '#ef4444', fill: 'rgba(239,68,68,.12)', point: '#fca5a5' }
    };
    const pal = palettes[component] || palettes.observed;
    const ctx = document.getElementById('combinedChart').getContext('2d');
    if (window.chartInstance) window.chartInstance.destroy();

    const displayLabels = labels.map(d => {
        const [y, m] = d.split('-');
        return `${new Date(y, parseInt(m)-1).toLocaleString('default',{month:'short'})} ${y}`;
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
                borderColor: pal.border,
                backgroundColor: pal.fill,
                pointBackgroundColor: pal.point,
                pointBorderColor: pal.border,
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#052e22',
                    titleColor: '#74ff70',
                    bodyColor: '#fff',
                    borderColor: 'rgba(116,255,112,.3)',
                    borderWidth: 1,
                    padding: 10,
                    titleFont: { family: 'DM Sans', size: 12 },
                    bodyFont: { family: 'DM Sans', size: 12 }
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

function updateSummaryText(component, category, year, data, labels) {
    const total     = data.reduce((a,b) => a+b, 0);
    const catText   = category === 'ALL' ? 'all assistance types combined' : `"${category}"`;
    const yearText  = year === 'ALL' ? 'all years' : year;
    const startVal  = data[0] || 0;
    const endVal    = data[data.length-1] || 0;
    const diff      = endVal - startVal;
    const diffPct   = startVal ? (diff/startVal)*100 : 0;
    let html = '';

    const badge = (text, color='#064e3b') =>
        `<span style="display:inline-flex;align-items:center;background:rgba(6,78,59,.08);border:1px solid rgba(6,78,59,.2);border-radius:6px;padding:1px 8px;font-size:.75rem;font-weight:700;color:${color};">${text}</span>`;

    switch (component) {
        case 'observed':
            const avg     = total / data.length;
            const hiVal   = Math.max(...data);
            const loVal   = Math.min(...data);
            const hiMonth = labels[data.indexOf(hiVal)];
            const loMonth = labels[data.indexOf(loVal)];
            const trend_dir = diffPct >= 5 ? '📈 increasing' : diffPct <= -5 ? '📉 declining' : '➡️ stable';
            html = `
                <h6>🔍 Observed Data — ${yearText}</h6>
                <p>For <strong>${catText}</strong>, a total of ${badge(total.toLocaleString() + ' applications')} were recorded across <strong>${yearText}</strong>, averaging <strong>${avg.toFixed(0)}</strong> per month.</p>
                <p>The <strong>peak month</strong> was <strong>${hiMonth}</strong> with <strong>${hiVal.toFixed(0)}</strong> applications — the <strong>slowest month</strong> was <strong>${loMonth}</strong> with <strong>${loVal.toFixed(0)}</strong>. The range of <strong>${(hiVal-loVal).toFixed(0)}</strong> applications between peak and trough reflects the degree of monthly variability.</p>
                <p>Overall demand is <strong>${trend_dir}</strong> (${diffPct >= 0 ? '+' : ''}${diffPct.toFixed(1)}% from start to end of period).</p>
                <p><strong>What to do with this:</strong> Use peak months to plan staff coverage and resource allocation. If the peak-to-trough gap is large, consider staggered intake scheduling to smooth demand.</p>`;
            break;

        case 'trend':
            const tStart = data[0]||0, tEnd = data[data.length-1]||0;
            const tDiff  = tEnd - tStart;
            const tPct   = tStart ? (tDiff/tStart)*100 : 0;
            const tMax   = Math.max(...data), tMin = Math.min(...data);
            const tMaxMo = labels[data.indexOf(tMax)], tMinMo = labels[data.indexOf(tMin)];
            let tConclusion = tPct >= 10
                ? 'The trend shows <strong>strong growth</strong> in demand — budget and headcount plans should account for continued increases.'
                : tPct > 3
                ? 'There is a <strong>steady upward trend</strong>. Plan for gradually increasing workload over coming months.'
                : tPct <= -10
                ? 'The trend shows a <strong>significant decline</strong> — investigate whether this reflects a structural reduction in demand or a data gap.'
                : tPct < -3
                ? 'Demand is <strong>gradually declining</strong>. This may represent seasonal recovery or a longer-term drop — monitor closely.'
                : 'Demand is <strong>broadly stable</strong>. Short-term fluctuations are noise; the underlying pipeline is consistent.';
            html = `
                <h6>📈 Trend Analysis — ${yearText}</h6>
                <p>The trend component removes seasonal fluctuations to reveal the <strong>underlying direction</strong> of ${catText} applications.</p>
                <p>The trend moved from ${badge(tStart.toFixed(1))} to ${badge(tEnd.toFixed(1))} — a change of <strong>${tDiff >= 0 ? '+' : ''}${tDiff.toFixed(1)}</strong> (${tPct >= 0 ? '+' : ''}${tPct.toFixed(1)}%) over the selected period.</p>
                <p>Peak trend level: <strong>${tMaxMo}</strong> (~${tMax.toFixed(1)}) · Lowest trend level: <strong>${tMinMo}</strong> (~${tMin.toFixed(1)}).</p>
                <p><strong>Conclusion:</strong> ${tConclusion}</p>`;
            break;

        case 'seasonal':
            const sMax    = Math.max(...data), sMin = Math.min(...data);
            const sPeaks  = data.map((v,i)=>v===sMax?labels[i]:null).filter(Boolean).join(', ');
            const sTroughs = data.map((v,i)=>v===sMin?labels[i]:null).filter(Boolean).join(', ');
            const sDiff   = sMax - sMin;
            const sStrong = sDiff > (0.25 * (total/data.length));
            html = `
                <h6>📅 Seasonal Pattern — ${yearText}</h6>
                <p>Seasonality isolates the <strong>recurring calendar-driven pattern</strong> in ${catText} applications — the same rhythm that repeats year after year regardless of the underlying trend.</p>
                <p>${sStrong
                    ? `There is a <strong>strong seasonal signal</strong> (peak-to-trough gap of <strong>${sDiff.toFixed(0)}</strong>). Peak months — <strong>${sPeaks}</strong> — consistently see elevated demand, while <strong>${sTroughs}</strong> tend to be quieter.`
                    : `Seasonal fluctuations are <strong>mild</strong> (gap of ${sDiff.toFixed(0)}). Demand is relatively even across the year, with slight highs around <strong>${sPeaks}</strong>.`
                }</p>
                <p><strong>Operational implication:</strong> ${sStrong
                    ? `Ensure extra staffing, faster document processing, and higher intake capacity during <strong>${sPeaks}</strong>. Consider proactive outreach to applicants in <strong>${sTroughs}</strong> to keep the pipeline moving.`
                    : `No major seasonal prep is needed. Maintain consistent staffing and intake capacity year-round.`
                }</p>`;
            break;

        case 'residual':
            const rAbs    = data.map(v=>Math.abs(v));
            const rAvg    = rAbs.reduce((a,b)=>a+b,0)/rAbs.length;
            const rMax    = Math.max(...rAbs), rMin = Math.min(...rAbs);
            const rMaxMo  = labels[rAbs.indexOf(rMax)];
            const rMinMo  = labels[rAbs.indexOf(rMin)];
            let rConclusion = rMax > rAvg*2
                ? `A notable anomaly occurred around <strong>${rMaxMo}</strong> — this likely reflects an external shock (policy change, disaster, surge event). Investigate what happened that month.`
                : rAvg > 2
                ? 'Residuals are moderately elevated, suggesting some unpredictable variability the model does not fully explain. Consider external factors like policy changes or data quality.'
                : 'Residuals are small and evenly distributed — the model explains the data well. No major anomalies detected.';
            html = `
                <h6>🔎 Residual / Anomaly Analysis — ${yearText}</h6>
                <p>The residual is what remains after removing both trend and seasonality from ${catText} applications. Large residuals indicate <strong>unexpected events or anomalies</strong> not explained by regular patterns.</p>
                <p>Average residual size: ${badge(rAvg.toFixed(2))} · Largest anomaly: <strong>${rMaxMo}</strong> (${rMax.toFixed(2)}) · Smallest: <strong>${rMinMo}</strong> (${rMin.toFixed(2)}).</p>
                <p><strong>Interpretation:</strong> ${rConclusion}</p>
                <p>Use this view to flag months that warrant investigation — spikes often correspond to external events worth documenting for future planning.</p>`;
            break;

        default:
            html = '<p>No summary available for this component.</p>';
    }

    document.getElementById('summary-content').innerHTML = html;
}

// Event listeners (keep original IDs to maintain backward compatibility)
['yearSelector','caseCategorySelector'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => {
        loadStlData(
            document.getElementById('caseCategorySelector').value,
            document.getElementById('chartSelector').value,
            document.getElementById('yearSelector').value
        );
    });
});

// Dashboard summary cards
async function loadDashboardSummary() {
    try {
        const res  = await fetch('/admin/statistics/get-statistics?type=cswd');
        const json = await res.json();
        const s    = json.overall?.dashboard_summary;
        if (!s) return;
        document.getElementById('topAssistance').textContent       = s.top_assistance || 'N/A';
        document.getElementById('mostCommonCategory').textContent   = s.most_common_category || 'N/A';
        document.getElementById('totalApplicants').textContent      = s.total_applicants?.toLocaleString() || '0';
        document.getElementById('averageProcessingTime').textContent = s.average_processing_time || '0 days';
    } catch (err) {
        console.error('Error loading dashboard summary:', err);
    }
}

loadDashboardSummary();
loadStlData();
loadWeeklyStl();
</script>