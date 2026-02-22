@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════
   Budget STL — Forest-green design system
   Matches CSWD timeseries exactly
   ═══════════════════════════════════════════ */
.bstl-wrap { font-family: 'DM Sans', sans-serif; color: #052e22; }

/* ── KPI Stat Cards ── */
.bstl-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 20px; }
.bstl-stat { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); transition: transform .2s, box-shadow .2s; }
.bstl-stat:hover { transform: translateY(-3px); box-shadow: 0 4px 24px rgba(6,78,59,.16); }
.bstl-stat-inner { padding: 14px 16px 10px; display: flex; align-items: flex-start; justify-content: space-between; }
.bstl-stat-label { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.72); margin-bottom: 6px; }
.bstl-stat-value { font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1.2; letter-spacing: -.01em; word-break: break-word; }
.bstl-stat-icon { width: 38px; height: 38px; border-radius: 9px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .95rem; color: #fff; flex-shrink: 0; }
.bstl-stat-foot { padding: 7px 16px; font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); border-top: 1px solid rgba(255,255,255,.12); }
.bstl-c-blue   { background: linear-gradient(135deg,#1d4ed8,#3b82f6); }
.bstl-c-green  { background: linear-gradient(135deg,#064e3b,#10b981); }
.bstl-c-amber  { background: linear-gradient(135deg,#92400e,#f59e0b); }
.bstl-c-red    { background: linear-gradient(135deg,#991b1b,#ef4444); }

/* ── Card base ── */
.bstl-card { background: #fff; border-radius: 12px; border: 1px solid #d1fae5; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); margin-bottom: 18px; overflow: hidden; }
.bstl-card-hdr { display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: linear-gradient(135deg,#052e22 0%,#064e3b 100%); flex-wrap: wrap; }
.bstl-card-hdr-icon { width: 28px; height: 28px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .72rem; color: #74ff70; flex-shrink: 0; }
.bstl-card-hdr-title { font-size: .88rem; font-weight: 700; color: #fff; }
.bstl-card-hdr-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
.bstl-card-body { padding: 18px; }

/* ── Filter selects ── */
.bstl-label { font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); white-space: nowrap; }
.bstl-select { height: 28px; border: 1px solid rgba(116,255,112,.28); border-radius: 7px; background: rgba(116,255,112,.08); color: #fff; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 26px 0 9px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(116,255,112,.7)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: border-color .15s, background .15s; }
.bstl-select:focus { border-color: rgba(116,255,112,.65); background-color: rgba(116,255,112,.14); }
.bstl-select option { background: #052e22; color: #fff; }

/* ── Component pills ── */
.bstl-component-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.bstl-cpill { padding: 3px 12px; border-radius: 20px; font-size: .72rem; font-weight: 600; border: 1px solid rgba(116,255,112,.28); background: rgba(116,255,112,.08); color: rgba(255,255,255,.75); cursor: pointer; transition: all .15s; font-family: 'DM Sans', sans-serif; }
.bstl-cpill:hover { background: rgba(116,255,112,.18); color: #fff; }
.bstl-cpill.active { background: #74ff70; color: #052e22; border-color: #74ff70; }

/* ── Insight panel ── */
.bstl-insight { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1px solid #d1fae5; border-radius: 12px; padding: 18px 20px; }
.bstl-insight-hdr { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #d1fae5; }
.bstl-insight-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(116,255,112,.15); border: 1px solid rgba(116,255,112,.3); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #064e3b; flex-shrink: 0; }
.bstl-insight-hdr-title { font-size: .84rem; font-weight: 700; color: #052e22; }
#bstl-summary-content h6 { font-size: .82rem; font-weight: 700; color: #052e22; margin-bottom: 8px; }
#bstl-summary-content p  { font-size: .8rem; color: #374151; line-height: 1.6; margin-bottom: 8px; }
#bstl-summary-content strong { color: #064e3b; }
#bstl-summary-content ul  { font-size: .8rem; color: #374151; padding-left: 16px; margin-bottom: 8px; }
#bstl-summary-content li  { margin-bottom: 4px; line-height: 1.5; }

/* ── Currency badge ── */
.bstl-badge { display: inline-flex; align-items: center; background: rgba(6,78,59,.08); border: 1px solid rgba(6,78,59,.2); border-radius: 6px; padding: 1px 8px; font-size: .75rem; font-weight: 700; color: #064e3b; }

/* ── Y-axis peso formatting note ── */
.bstl-chart-note { font-size: .68rem; color: #9ca3af; font-style: italic; margin-top: 6px; text-align: right; }

@media (max-width:1100px) { .bstl-stat-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width:640px)  { .bstl-stat-grid { grid-template-columns: 1fr 1fr; } .bstl-card-hdr-controls { margin-left: 0; width: 100%; } }
</style>

<div class="bstl-wrap">

    {{-- ── KPI STAT CARDS ── --}}
    <div class="bstl-stat-grid">
        <div class="bstl-stat bstl-c-blue">
            <div class="bstl-stat-inner">
                <div>
                    <div class="bstl-stat-label">Top Allocated Category</div>
                    <div class="bstl-stat-value" id="topBudgetCategory">—</div>
                </div>
                <div class="bstl-stat-icon"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="bstl-stat-foot">Highest-volume case category</div>
        </div>
        <div class="bstl-stat bstl-c-green">
            <div class="bstl-stat-inner">
                <div>
                    <div class="bstl-stat-label">Top Allocated Type</div>
                    <div class="bstl-stat-value" id="highestAllocation">—</div>
                </div>
                <div class="bstl-stat-icon"><i class="fas fa-chart-pie"></i></div>
            </div>
            <div class="bstl-stat-foot">Leading assistance type</div>
        </div>
        <div class="bstl-stat bstl-c-amber">
            <div class="bstl-stat-inner">
                <div>
                    <div class="bstl-stat-label">Total Disbursed</div>
                    <div class="bstl-stat-value" id="totalBudget">—</div>
                </div>
                <div class="bstl-stat-icon"><i class="fas fa-coins"></i></div>
            </div>
            <div class="bstl-stat-foot">All-time budget released</div>
        </div>
        <div class="bstl-stat bstl-c-red">
            <div class="bstl-stat-inner">
                <div>
                    <div class="bstl-stat-label">Monthly Average</div>
                    <div class="bstl-stat-value" id="unusedFunds">—</div>
                </div>
                <div class="bstl-stat-icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
            <div class="bstl-stat-foot">Avg. monthly allocation</div>
        </div>
    </div>

    {{-- ── BUDGET STL CHART ── --}}
    <div class="bstl-card">
        <div class="bstl-card-hdr">
            <div class="bstl-card-hdr-icon"><i class="fas fa-chart-line"></i></div>
            <span class="bstl-card-hdr-title">Budget Time Series</span>
            <div class="bstl-card-hdr-controls">
                <div class="bstl-component-pills">
                    <button class="bstl-cpill active" data-component="observed">Observed</button>
                    <button class="bstl-cpill" data-component="trend">Trend</button>
                    <button class="bstl-cpill" data-component="seasonal">Seasonal</button>
                    <button class="bstl-cpill" data-component="residual">Residual</button>
                </div>
                <span class="bstl-label">Year:</span>
                <select id="yearSelector" class="bstl-select" style="min-width:80px;"></select>
                <span class="bstl-label">Category:</span>
                <select id="caseCategorySelector" class="bstl-select" style="min-width:130px;"></select>
            </div>
        </div>
        <div class="bstl-card-body">
            <canvas id="combinedChart" height="300" style="width:100%;max-height:320px;"></canvas>
            <div class="bstl-chart-note">Values in Philippine Peso (₱)</div>
        </div>
    </div>

    {{-- ── INTERPRETATION ── --}}
    <div class="bstl-card">
        <div class="bstl-card-hdr">
            <div class="bstl-card-hdr-icon"><i class="fas fa-lightbulb"></i></div>
            <span class="bstl-card-hdr-title">Interpretation</span>
        </div>
        <div class="bstl-card-body" style="padding:16px 18px;">
            <div class="bstl-insight">
                <div class="bstl-insight-hdr">
                    <div class="bstl-insight-hdr-icon"><i class="fas fa-brain"></i></div>
                    <span class="bstl-insight-hdr-title" id="bstl-insight-label">Observed Budget Analysis</span>
                </div>
                <div id="bstl-summary-content">
                    <p style="color:#9ca3af;font-size:.8rem;">Select a component and category above to generate insights.</p>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /bstl-wrap --}}

{{-- Hidden selector keeps original JS event compatibility ── --}}
<select id="chartSelector" style="display:none;">
    <option value="observed">Observed</option>
    <option value="seasonal">Seasonal</option>
    <option value="trend">Trend</option>
    <option value="residual">Residual</option>
</select>

<script>
/* ── Sync pill buttons → hidden select → loadStlData ── */
document.querySelectorAll('.bstl-cpill').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.bstl-cpill').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const comp = this.dataset.component;
        document.getElementById('chartSelector').value = comp;

        const insightLabels = {
            observed: 'Observed Budget Analysis',
            trend:    'Trend Direction Analysis',
            seasonal: 'Seasonal Budget Pattern',
            residual: 'Residual / Anomaly Analysis'
        };
        document.getElementById('bstl-insight-label').textContent = insightLabels[comp] || 'Analysis';

        loadStlData(
            document.getElementById('caseCategorySelector').value,
            comp,
            document.getElementById('yearSelector').value
        );
    });
});
</script>

<script>
async function loadStlData(category = 'ALL', component = 'observed', year = 'ALL') {
    const chartContainer = document.getElementById('combinedChart').parentElement;

    try {
        const res  = await fetch('/admin/timeseries/get-stl-json?type=budget');
        const json = await res.json();

        if (!json || Object.keys(json).length === 0) {
            chartContainer.innerHTML = `<div class="text-center text-muted py-5" style="font-family:'DM Sans',sans-serif;">
                <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;color:#f59e0b;margin-bottom:8px;display:block;"></i>
                <strong>STL Chart unavailable — insufficient data.</strong><br>
                <small>Please upload at least 1–2 years of records to generate decomposition.</small>
            </div>`;
            return;
        }

        // Populate category selector
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

        // Populate year selector
        const yearSelector = document.getElementById('yearSelector');
        if (yearSelector.options.length === 0) {
            yearSelector.innerHTML = '';
            const allYearOpt = document.createElement('option');
            allYearOpt.value = 'ALL'; allYearOpt.textContent = 'ALL';
            yearSelector.appendChild(allYearOpt);
            let allDates = category === 'ALL' ? json[Object.keys(json)[0]].dates : json[category].dates;
            const years = [...new Set(allDates.map(d => d.split('-')[0]))].sort((a,b) => b - a);
            years.forEach(y => {
                const opt = document.createElement('option');
                opt.value = y; opt.textContent = y;
                yearSelector.appendChild(opt);
            });
        }
        if (!year) year = yearSelector.value || 'ALL';
        yearSelector.value = year;

        // Build chart data
        let labels = [], dataForYear = [];
        if (category === 'ALL') {
            const cats  = Object.keys(json);
            const dates = json[cats[0]].dates;
            const idxs  = dates.map((d,i) => (year === 'ALL' || d.startsWith(year+'-')) ? i : -1).filter(i => i >= 0);
            dataForYear = idxs.map(i => cats.reduce((sum,c) => sum + (json[c][component][i] || 0), 0));
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

    } catch (err) {
        console.error('Error loading STL data:', err);
        chartContainer.innerHTML = `<div class="text-center text-danger py-5" style="font-family:'DM Sans',sans-serif;">
            <i class="fas fa-times-circle" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
            Failed to load STL data. Please try again.
        </div>`;
    }
}

function renderChart(labels, data, component) {
    const palettes = {
        observed: { border: '#064e3b', fill: 'rgba(6,78,59,.12)',    point: '#74ff70' },
        trend:    { border: '#10b981', fill: 'rgba(16,185,129,.12)', point: '#34d399' },
        seasonal: { border: '#f59e0b', fill: 'rgba(245,158,11,.12)', point: '#fcd34d' },
        residual: { border: '#ef4444', fill: 'rgba(239,68,68,.12)',  point: '#fca5a5' }
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
    if (component === 'residual') {
        const am = Math.max(...data.map(v => Math.abs(v)));
        yMin = -am * 1.1; yMax = am * 1.1;
    }

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
                    bodyFont: { family: 'DM Sans', size: 12 },
                    callbacks: {
                        label: ctx => `₱${ctx.raw.toLocaleString()}`
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Month', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                    grid: { color: 'rgba(6,78,59,.05)' },
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280', maxRotation: 45 }
                },
                y: {
                    beginAtZero: false,
                    min: yMin, max: yMax,
                    grid: { color: 'rgba(6,78,59,.05)' },
                    ticks: {
                        font: { family: 'DM Sans', size: 11 },
                        color: '#6b7280',
                        callback: value => '₱' + value.toLocaleString()
                    }
                }
            }
        }
    });
}

function updateSummaryText(component, category, year, data, labels) {
    const total    = data.reduce((a,b) => a+b, 0);
    const catText  = category === 'ALL' ? 'all budget categories combined' : `"${category}"`;
    const yearText = year === 'ALL' ? 'all years' : year;
    const startVal = data[0] || 0;
    const endVal   = data[data.length-1] || 0;
    const diff     = endVal - startVal;
    const diffPct  = startVal ? (diff/startVal)*100 : 0;
    let html = '';

    const peso = v => `<span class="bstl-badge">₱${Number(v).toLocaleString()}</span>`;

    switch (component) {
        case 'observed':
            const avg      = total / data.length;
            const hiVal    = Math.max(...data);
            const loVal    = Math.min(...data);
            const hiMonth  = labels[data.indexOf(hiVal)];
            const loMonth  = labels[data.indexOf(loVal)];
            const trendDir = diffPct >= 5 ? '📈 increasing' : diffPct <= -5 ? '📉 declining' : '➡️ stable';
            html = `
                <h6>📊 Observed Budget Summary — ${yearText}</h6>
                <p>For <strong>${catText}</strong>, total observed disbursement reached ${peso(total.toFixed(0))} across <strong>${yearText}</strong>, with a monthly average of <strong>₱${avg.toLocaleString(undefined,{maximumFractionDigits:0})}</strong>.</p>
                <p>The <strong>peak month</strong> was <strong>${hiMonth}</strong> at <strong>₱${hiVal.toLocaleString()}</strong> — the <strong>lowest</strong> was <strong>${loMonth}</strong> at <strong>₱${loVal.toLocaleString()}</strong>. The ₱${(hiVal-loVal).toLocaleString(undefined,{maximumFractionDigits:0})} range between peak and trough reflects monthly variability in disbursement.</p>
                <p>Overall budget flow is <strong>${trendDir}</strong> (${diffPct >= 0 ? '+' : ''}${diffPct.toFixed(1)}% from start to end of period).</p>
                <p><strong>What to do:</strong> Use peak months to pre-authorize higher budgets. If the peak-to-trough gap is large, consider quarterly budget releases to ensure smoother cash flow.</p>`;
            break;

        case 'trend':
            const tMax    = Math.max(...data), tMin = Math.min(...data);
            const tMaxMo  = labels[data.indexOf(tMax)], tMinMo = labels[data.indexOf(tMin)];
            let tConclusion = diffPct >= 10
                ? 'The trend shows <strong>strong growth</strong> in budget demand — future allocations should be sized proportionally.'
                : diffPct > 3
                ? 'A <strong>steady upward trend</strong> suggests gradually increasing funding requirements.'
                : diffPct <= -10
                ? 'There is a <strong>significant declining trend</strong> — investigate whether this reflects lower demand or budget constraints.'
                : diffPct < -3
                ? 'Budget is <strong>gradually declining</strong>. Monitor whether this reflects structural changes or temporary reductions.'
                : 'The underlying funding level is <strong>broadly stable</strong>. Short-term swings are noise.';
            html = `
                <h6>💰 Budget Trend Insights — ${yearText}</h6>
                <p>The trend component smooths seasonal noise to reveal the <strong>underlying direction</strong> of ${catText} disbursements.</p>
                <p>Funding moved from ${peso(startVal.toFixed(2))} to ${peso(endVal.toFixed(2))} — a net change of <strong>₱${diff.toFixed(2)}</strong> (${diffPct >= 0 ? '+' : ''}${diffPct.toFixed(1)}%).</p>
                <p>Peak trend level: <strong>${tMaxMo}</strong> (~₱${tMax.toFixed(2)}) · Lowest trend level: <strong>${tMinMo}</strong> (~₱${tMin.toFixed(2)}).</p>
                <p><strong>Conclusion:</strong> ${tConclusion}</p>
                <p>This view helps decision-makers assess long-term funding sustainability and plan multi-year budget projections.</p>`;
            break;

        case 'seasonal':
            const sMax    = Math.max(...data), sMin = Math.min(...data);
            const sPeaks  = labels.filter((_,i) => data[i] === sMax).join(', ');
            const sTroughs = labels.filter((_,i) => data[i] === sMin).join(', ');
            const sDiff   = sMax - sMin;
            const avgM    = total / data.length;
            const sStrong = sDiff > (0.25 * Math.abs(avgM));
            html = `
                <h6>🌊 Seasonal Budget Pattern — ${yearText}</h6>
                <p>Seasonality isolates the <strong>recurring calendar-driven pattern</strong> in ${catText} disbursements — the same cycle repeating year after year independent of the long-term trend.</p>
                <p>${sStrong
                    ? `A <strong>strong seasonal signal</strong> exists (peak-to-trough gap of ₱${sDiff.toLocaleString(undefined,{maximumFractionDigits:0})}). Highest-disbursement months — <strong>${sPeaks}</strong> — consistently see elevated spending, while <strong>${sTroughs}</strong> tend to be quieter.`
                    : `Seasonal fluctuations are <strong>mild</strong> (gap of ₱${sDiff.toLocaleString(undefined,{maximumFractionDigits:0})}). Budget demand is relatively even across the year with slight highs around <strong>${sPeaks}</strong>.`
                }</p>
                <p><strong>Operational implication:</strong> ${sStrong
                    ? `Ensure adequate cash reserves before <strong>${sPeaks}</strong>. Consider pre-releasing funds to avoid disbursement delays during peak months.`
                    : `No major seasonal preparation is needed. Maintain consistent budget release schedules year-round.`
                }</p>`;
            break;

        case 'residual':
            const rAbs   = data.map(v => Math.abs(v));
            const rAvg   = rAbs.reduce((a,b) => a+b,0) / rAbs.length;
            const rMax   = Math.max(...rAbs);
            const rMin   = Math.min(...rAbs);
            const rMaxMo = labels[rAbs.indexOf(rMax)];
            const rMinMo = labels[rAbs.indexOf(rMin)];
            let rConclusion = rMax > rAvg * 2
                ? `A significant irregularity occurred around <strong>${rMaxMo}</strong> — this likely reflects an unexpected spending event, policy change, or emergency release. Investigate what happened that month.`
                : rAvg > 2
                ? 'Residuals are moderately elevated, suggesting budget anomalies not fully explained by trend or seasonality. Consider auditing irregular months.'
                : 'Residuals are small and evenly distributed — the model explains disbursement patterns well. No major anomalies detected.';
            html = `
                <h6>🔎 Residual Budget Analysis — ${yearText}</h6>
                <p>The residual captures <strong>irregular, unpredictable variations</strong> in ${catText} disbursements after removing both trend and seasonality. Large residuals indicate unexpected budget events.</p>
                <p>Average residual size: ${peso(rAvg.toFixed(2))} · Largest anomaly: <strong>${rMaxMo}</strong> (₱${rMax.toFixed(2)}) · Smallest: <strong>${rMinMo}</strong> (₱${rMin.toFixed(2)}).</p>
                <p><strong>Interpretation:</strong> ${rConclusion}</p>
                <p>Use this view to flag months that warrant audit or further investigation — residual spikes often correspond to emergency disbursements, policy shifts, or data irregularities worth documenting.</p>`;
            break;

        default:
            html = '<p>No summary available for this component.</p>';
    }

    document.getElementById('bstl-summary-content').innerHTML = html;
}

// Event listeners (preserve original IDs for backward compatibility)
document.getElementById('yearSelector').addEventListener('change', () =>
    loadStlData(
        document.getElementById('caseCategorySelector').value,
        document.getElementById('chartSelector').value,
        document.getElementById('yearSelector').value
    )
);
document.getElementById('caseCategorySelector').addEventListener('change', () =>
    loadStlData(
        document.getElementById('caseCategorySelector').value,
        document.getElementById('chartSelector').value,
        document.getElementById('yearSelector').value
    )
);

// Budget KPI cards
async function loadBudgetSummary() {
    try {
        const res  = await fetch('/admin/statistics/get-statistics?type=budget');
        const json = await res.json();
        const s    = (json.overall && json.overall.dashboard_summary) || {};

        document.getElementById('topBudgetCategory').textContent = s.highest_allocation_category || 'N/A';
        document.getElementById('highestAllocation').textContent = s.highest_allocation_type     || 'N/A';
        document.getElementById('totalBudget').textContent       = s.total_budget_disbursed
            ? `₱${Number(s.total_budget_disbursed).toLocaleString()}` : '₱0';
        document.getElementById('unusedFunds').textContent       = s.monthly_average_budget_allocation
            ? `₱${Number(s.monthly_average_budget_allocation).toLocaleString()}` : '₱0';
    } catch (err) {
        console.error('Error fetching budget summary:', err);
        ['topBudgetCategory','highestAllocation'].forEach(id => document.getElementById(id).textContent = 'N/A');
        ['totalBudget','unusedFunds'].forEach(id => document.getElementById(id).textContent = '₱0');
    }
}

loadBudgetSummary();
loadStlData();
</script>