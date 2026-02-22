<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════
   Budget Statistics — Forest-green design system
   Matches CSWD stats blade exactly
   ═══════════════════════════════════════════ */
.bstat-wrap { font-family: 'DM Sans', sans-serif; color: #052e22; margin-top: 0; }

/* ── Card base ── */
.bstat-card { background: #fff; border-radius: 12px; border: 1px solid #d1fae5; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); margin-bottom: 18px; overflow: hidden; }
.bstat-card-hdr { display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: linear-gradient(135deg,#052e22 0%,#064e3b 100%); flex-wrap: wrap; }
.bstat-card-hdr-icon { width: 28px; height: 28px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .72rem; color: #74ff70; flex-shrink: 0; }
.bstat-card-hdr-title { font-size: .88rem; font-weight: 700; color: #fff; }
.bstat-card-body { padding: 18px; }

/* ── Filter bar ── */
.bstat-filter-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
.bstat-label { font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); white-space: nowrap; }
.bstat-select { height: 28px; border: 1px solid rgba(116,255,112,.28); border-radius: 7px; background: rgba(116,255,112,.08); color: #fff; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 26px 0 9px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(116,255,112,.7)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: all .15s; min-width: 100px; }
.bstat-select:focus { border-color: rgba(116,255,112,.65); background-color: rgba(116,255,112,.14); }
.bstat-select option { background: #052e22; color: #fff; }
#bstatViewTitle { font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.5); }

/* ── Chart containers ── */
.bstat-chart-wrap { background: #f8fffe; border: 1px solid #d1fae5; border-radius: 10px; padding: 12px; }

/* ── Summary panel ── */
.bstat-summary-panel { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1px solid #d1fae5; border-radius: 12px; height: 100%; display: flex; flex-direction: column; overflow: hidden; }
.bstat-summary-hdr { padding: 12px 16px; background: linear-gradient(135deg,#052e22,#064e3b); display: flex; align-items: center; gap: 8px; }
.bstat-summary-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #74ff70; flex-shrink: 0; }
.bstat-summary-hdr-title { font-size: .84rem; font-weight: 700; color: #fff; }
.bstat-summary-body { padding: 14px 16px; flex: 1; overflow: auto; }
.bstat-summary-select { width: 100%; height: 30px; border: 1.5px solid #d1fae5; border-radius: 8px; background: #fff; color: #052e22; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 10px; outline: none; margin-bottom: 14px; cursor: pointer; }
.bstat-summary-select:focus { border-color: #064e3b; }

/* Metric rows */
.bstat-metric { display: flex; align-items: flex-start; gap: 10px; padding: 9px 0; border-bottom: 1px solid #d1fae5; }
.bstat-metric:last-of-type { border-bottom: none; }
.bstat-metric-icon { width: 28px; height: 28px; border-radius: 8px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #064e3b; flex-shrink: 0; margin-top: 1px; }
.bstat-metric-label { font-size: .72rem; font-weight: 700; color: #3d7a62; text-transform: uppercase; letter-spacing: .04em; }
.bstat-metric-value { font-size: .88rem; font-weight: 700; color: #052e22; line-height: 1.3; }
.bstat-metric-sub { font-size: .7rem; color: #6b7280; margin-top: 1px; font-weight: 400; }
.bstat-legend-note { font-size: .7rem; color: #6b7280; line-height: 1.5; margin-top: 10px; padding-top: 10px; border-top: 1px solid #d1fae5; }
.bstat-legend-note strong { color: #064e3b; }

/* ── Pie panel ── */
.bstat-pie-panel { background: #fff; border: 1px solid #d1fae5; border-radius: 12px; height: 100%; padding: 14px; display: flex; flex-direction: column; align-items: center; }
.bstat-pie-select { width: 100%; max-width: 220px; height: 28px; border: 1.5px solid #d1fae5; border-radius: 8px; background: #fff; color: #052e22; font-size: .74rem; font-family: 'DM Sans', sans-serif; padding: 0 8px; outline: none; margin-bottom: 10px; cursor: pointer; }
.bstat-pie-select:focus { border-color: #064e3b; }
#bstatPieSummary { font-size: .71rem; color: #6b7280; text-align: center; margin-top: 6px; }
#bstatCustomLegend li { font-size: .72rem; display: flex; align-items: center; gap: 5px; }

/* ── Deficiency panel ── */
.bstat-def-panel { background: #fffbeb; border: 1px solid rgba(245,158,11,.3); border-radius: 12px; height: 100%; overflow: hidden; }
.bstat-def-hdr { padding: 12px 16px; background: linear-gradient(135deg,#78350f,#d97706); display: flex; align-items: center; gap: 8px; }
.bstat-def-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #fff; flex-shrink: 0; }
.bstat-def-hdr-title { font-size: .84rem; font-weight: 700; color: #fff; }
.bstat-def-body { padding: 14px; display: flex; flex-direction: column; height: calc(100% - 50px); }
#bstatDefSummary { font-size: .74rem; color: #6b7280; text-align: center; margin-top: 8px; }

@media (max-width:992px) { .bstat-filter-bar { margin-left: 0; width: 100%; } }
</style>

<div class="bstat-wrap">
    <div class="bstat-card">
        <div class="bstat-card-hdr">
            <div class="bstat-card-hdr-icon"><i class="fas fa-chart-bar"></i></div>
            <span class="bstat-card-hdr-title">Budget Statistical Analysis</span>
            <span id="bstatViewTitle" style="margin-left:8px;"></span>
            <div class="bstat-filter-bar">
                <span class="bstat-label">Year:</span>
                <select id="yearDropdown" class="bstat-select"></select>

                <span class="bstat-label">Month:</span>
                <select id="monthDropdown" class="bstat-select">
                    <option value="yearly">Yearly View</option>
                </select>

                <span class="bstat-label">View:</span>
                <select id="statDropdown" class="bstat-select">
                    <option value="case_type">Case Type</option>
                    <option value="case_category">Case Category</option>
                </select>
            </div>
        </div>

        <div class="bstat-card-body">

            {{-- Row 1: Charts ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="bstat-chart-wrap">
                        <canvas id="meanMedianModeChart" height="200"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bstat-chart-wrap">
                        <canvas id="standardDeviationVarianceChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Row 2: Summary + Pie + Deficiency ── --}}
            <div class="row g-3">

                {{-- Statistical Summary ── --}}
                <div class="col-lg-4">
                    <div class="bstat-summary-panel" style="min-height:360px;">
                        <div class="bstat-summary-hdr">
                            <div class="bstat-summary-hdr-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span class="bstat-summary-hdr-title">Budget Summary</span>
                        </div>
                        <div class="bstat-summary-body">
                            <select id="summaryLabelDropdown" class="bstat-summary-select"></select>

                            <div class="bstat-metric">
                                <div class="bstat-metric-icon"><i class="fas fa-calculator"></i></div>
                                <div>
                                    <div class="bstat-metric-label">Mean</div>
                                    <div class="bstat-metric-value" id="summaryMean">—</div>
                                    <div class="bstat-metric-sub">Average value across the group</div>
                                </div>
                            </div>
                            <div class="bstat-metric">
                                <div class="bstat-metric-icon"><i class="fas fa-arrows-alt-v"></i></div>
                                <div>
                                    <div class="bstat-metric-label">Median</div>
                                    <div class="bstat-metric-value" id="summaryMedian">—</div>
                                    <div class="bstat-metric-sub">Middle value when ordered</div>
                                </div>
                            </div>
                            <div class="bstat-metric">
                                <div class="bstat-metric-icon"><i class="fas fa-chart-bar"></i></div>
                                <div>
                                    <div class="bstat-metric-label">Mode</div>
                                    <div class="bstat-metric-value" id="summaryMode">—</div>
                                    <div class="bstat-metric-sub">Most frequently occurring value</div>
                                </div>
                            </div>
                            <div class="bstat-metric">
                                <div class="bstat-metric-icon"><i class="fas fa-wave-square"></i></div>
                                <div>
                                    <div class="bstat-metric-label">Std Dev</div>
                                    <div class="bstat-metric-value" id="summaryStdDev">—</div>
                                    <div class="bstat-metric-sub">Typical deviation from mean</div>
                                </div>
                            </div>
                            <div class="bstat-metric">
                                <div class="bstat-metric-icon"><i class="fas fa-braille"></i></div>
                                <div>
                                    <div class="bstat-metric-label">Variance</div>
                                    <div class="bstat-metric-value" id="summaryVariance">—</div>
                                    <div class="bstat-metric-sub">Overall spread of values</div>
                                </div>
                            </div>

                            <div class="bstat-legend-note">
                                <strong>How to read this:</strong> Mean is the average budget, Median the midpoint. If Mean &gt; Median significantly, high-spend outliers are skewing the average. High Std Dev means wide variation in disbursement amounts across this group.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie / Donut ── --}}
                <div class="col-lg-4">
                    <div class="bstat-pie-panel">
                        <select id="pieChartTypeSelector" class="bstat-pie-select">
                            <option value="total_applications_by_category">Applications by Category</option>
                            <option value="total_applications_by_type">Applications by Type</option>
                        </select>
                        <div style="position:relative;width:100%;height:240px;max-width:240px;margin:0 auto;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <ul id="customLegend" class="list-unstyled mt-2 d-flex flex-wrap justify-content-center gap-2"></ul>
                        <p id="pieSummary"></p>
                    </div>
                </div>

                {{-- Document Deficiency ── --}}
                <div class="col-lg-4">
                    <div class="bstat-def-panel" style="min-height:360px;">
                        <div class="bstat-def-hdr">
                            <div class="bstat-def-hdr-icon"><i class="fas fa-file-excel"></i></div>
                            <span class="bstat-def-hdr-title">Document Deficiency Breakdown</span>
                        </div>
                        <div class="bstat-def-body">
                            <canvas id="deficiencyChart" style="flex:1;max-height:280px;"></canvas>
                            <p id="deficiencySummary">No Deficiency Data</p>
                        </div>
                    </div>
                </div>

            </div>{{-- /row 2 --}}
        </div>
    </div>
</div>{{-- /bstat-wrap --}}

<script>
    let meanMedianModeChart = null;
    let dispersionChart     = null;
    let pieChart            = null;
    let cachedData          = null;

    async function fetchStats() {
        try {
            if (!cachedData) {
                const res  = await fetch('/admin/statistics/get-statistics?type=budget');
                cachedData = await res.json();

                // Populate year dropdown
                const yearDropdown = document.getElementById('yearDropdown');
                const years = Object.keys(cachedData.yearly || {});
                yearDropdown.innerHTML = '';
                years.sort((a,b) => b - a);
                years.forEach((y, i) => {
                    const opt = document.createElement('option');
                    opt.value = y; opt.textContent = y;
                    const currentYear = new Date().getFullYear();
                    if (y == currentYear || i === 0) opt.selected = true;
                    yearDropdown.appendChild(opt);
                });

                updateMonthDropdown();
            }

            const year     = document.getElementById('yearDropdown').value;
            const month    = document.getElementById('monthDropdown').value;
            const statType = document.getElementById('statDropdown').value;

            let dataSource = cachedData.yearly[year];
            if (month !== 'yearly' && cachedData.monthly?.[year]?.[month]) {
                dataSource = cachedData.monthly[year][month];
            }
            if (!dataSource) return;

            const statsKey = statType === 'case_type' ? 'budget_stats_by_type' : 'budget_stats_by_category';
            const stats    = dataSource[statsKey];
            if (!stats) return;

            const labels   = Object.keys(stats);
            const mean     = labels.map(l => stats[l].mean);
            const median   = labels.map(l => stats[l].median);
            const mode     = labels.map(l => Array.isArray(stats[l].mode) && stats[l].mode.length ? stats[l].mode[0] : 0);
            const variance = labels.map(l => stats[l].variance);
            const stdDev   = labels.map(l => stats[l].std_dev);

            // View label for chart titles
            let viewLabel = `${year} (Yearly)`;
            if (month !== 'yearly' && cachedData.monthly?.[year]?.[month]) {
                const monthName = cachedData.monthly[year][month].month_name || `Month ${month}`;
                viewLabel = `${monthName} ${year}`;
            }
            document.getElementById('bstatViewTitle').textContent = `· ${viewLabel}`;

            updateCharts(labels, mean, median, mode, variance, stdDev, viewLabel);
            updateBudgetSummary(stats);

            // Pie chart
            renderPieChart(document.getElementById('pieChartTypeSelector').value);

        } catch (err) {
            console.error(err);
        }
    }

    function updateMonthDropdown() {
        const yearDropdown  = document.getElementById('yearDropdown');
        const monthDropdown = document.getElementById('monthDropdown');
        const selectedYear  = yearDropdown.value;
        monthDropdown.innerHTML = '<option value="yearly">Yearly View</option>';

        if (cachedData.monthly?.[selectedYear]) {
            const months = Object.keys(cachedData.monthly[selectedYear]).sort((a,b) => a-b);
            months.forEach(monthNum => {
                const monthData = cachedData.monthly[selectedYear][monthNum];
                const monthName = monthData.month_name || `Month ${monthNum}`;
                const opt = document.createElement('option');
                opt.value = monthNum; opt.textContent = monthName;
                const currentMonth = new Date().getMonth() + 1;
                if (monthNum == currentMonth) opt.selected = true;
                monthDropdown.appendChild(opt);
            });
        }
    }

    function updateCharts(labels, mean, median, mode, variance, stdDev, viewLabel) {
        if (meanMedianModeChart) meanMedianModeChart.destroy();
        if (dispersionChart)     dispersionChart.destroy();

        const sharedOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'DM Sans', size: 11 }, padding: 12, usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: '#052e22', titleColor: '#74ff70', bodyColor: '#fff',
                    padding: 10, titleFont: { family: 'DM Sans' }, bodyFont: { family: 'DM Sans' },
                    callbacks: { label: ctx => `₱${ctx.raw.toLocaleString()}` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(6,78,59,.06)' },
                    ticks: {
                        font: { family: 'DM Sans', size: 11 }, color: '#6b7280',
                        callback: v => '₱' + v.toLocaleString()
                    },
                    title: { display: true, text: 'Budget (₱)', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' }
                },
                x: {
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#374151', maxRotation: 45, minRotation: 45 },
                    grid: { color: 'rgba(6,78,59,.04)' }
                }
            }
        };

        const ctx1 = document.getElementById('meanMedianModeChart').getContext('2d');
        meanMedianModeChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Mean',   data: mean,   backgroundColor: 'rgba(6,78,59,.75)',   borderRadius: 5 },
                    { label: 'Median', data: median, backgroundColor: 'rgba(16,185,129,.7)',  borderRadius: 5 },
                    { label: 'Mode',   data: mode,   backgroundColor: 'rgba(116,255,112,.65)', borderRadius: 5 },
                ]
            },
            options: {
                ...sharedOptions,
                plugins: {
                    ...sharedOptions.plugins,
                    title: { display: true, text: `Budget Distribution — ${viewLabel}`, font: { family: 'DM Sans', size: 13, weight: '600' }, color: '#052e22' }
                }
            }
        });

        const ctx2 = document.getElementById('standardDeviationVarianceChart').getContext('2d');
        dispersionChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Std Dev',  data: stdDev,   borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,.10)',  fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#fca5a5', borderWidth: 2 },
                    { label: 'Variance', data: variance, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,.10)', fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#7dd3fc', borderWidth: 2 },
                ]
            },
            options: {
                ...sharedOptions,
                plugins: {
                    ...sharedOptions.plugins,
                    tooltip: {
                        ...sharedOptions.plugins.tooltip,
                        callbacks: { label: ctx => `${ctx.dataset.label}: ₱${ctx.raw.toLocaleString()}` }
                    },
                    title: { display: true, text: `Dispersion Analysis — ${viewLabel}`, font: { family: 'DM Sans', size: 13, weight: '600' }, color: '#052e22' }
                }
            }
        });
    }

    function updateBudgetSummary(stats) {
        const labels   = Object.keys(stats);
        if (!labels.length) return;

        const dropdown = document.getElementById('summaryLabelDropdown');
        dropdown.innerHTML = '';
        labels.forEach((label, idx) => {
            const opt = document.createElement('option');
            opt.value = idx; opt.textContent = label;
            dropdown.appendChild(opt);
        });

        function showSummary(index) {
            const label = labels[index];
            const stat  = stats[label];

            document.getElementById('summaryMean').innerHTML =
                `₱${stat.mean.toLocaleString()} <br><small style="color:#6b7280;font-weight:400;">Average disbursement for this group</small>`;

            document.getElementById('summaryMedian').innerHTML =
                `₱${stat.median.toLocaleString()} <br><small style="color:#6b7280;font-weight:400;">Middle value when ordered for this group</small>`;

            document.getElementById('summaryMode').innerHTML =
                `₱${Array.isArray(stat.mode) ? stat.mode.join(', ') : stat.mode} <br><small style="color:#6b7280;font-weight:400;">Most frequent disbursement amount</small>`;

            document.getElementById('summaryStdDev').innerHTML =
                `₱${stat.std_dev.toLocaleString()} <br><small style="color:#6b7280;font-weight:400;">Values typically vary ±₱${stat.std_dev.toLocaleString()}</small>`;

            if (stat.sample_spread?.length) {
                const minVal = Math.min(...stat.sample_spread);
                const maxVal = Math.max(...stat.sample_spread);
                document.getElementById('summaryVariance').innerHTML =
                    `₱${minVal.toLocaleString()} – ₱${maxVal.toLocaleString()} <br><small style="color:#6b7280;font-weight:400;">Range of disbursement values</small>`;
            } else {
                document.getElementById('summaryVariance').innerHTML =
                    `₱${stat.variance.toLocaleString()} <br><small style="color:#6b7280;font-weight:400;">Overall spread of values</small>`;
            }
        }

        showSummary(0);
        dropdown.onchange = function () { showSummary(this.value); };
    }

    function renderPieChart(dataKey) {
        if (!cachedData) return;
        const year  = document.getElementById('yearDropdown').value;
        const month = document.getElementById('monthDropdown').value;

        let dataSource = cachedData.yearly[year];
        if (month !== 'yearly' && cachedData.monthly?.[year]?.[month]) {
            dataSource = cachedData.monthly[year][month];
        }
        if (!dataSource?.[dataKey]) return;

        const dataObj  = dataSource[dataKey];
        const labels   = Object.keys(dataObj);
        const data     = Object.values(dataObj);
        const colors   = ['#064e3b','#10b981','#34d399','#3b82f6','#f59e0b','#8b5cf6','#ef4444'];
        const viewLabel = month !== 'yearly' && cachedData.monthly?.[year]?.[month]
            ? `${cachedData.monthly[year][month].month_name || 'Month '+month} ${year}`
            : `${year} (Yearly)`;

        const pieCtx = document.getElementById('categoryChart').getContext('2d');
        if (pieChart) pieChart.destroy();

        pieChart = new Chart(pieCtx, {
            type: dataKey === 'total_applications_by_type' ? 'doughnut' : 'pie',
            data: {
                labels,
                datasets: [{ data, backgroundColor: colors.slice(0, labels.length), borderRadius: 10, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: viewLabel, font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                    tooltip: {
                        backgroundColor: '#052e22', titleColor: '#74ff70', bodyColor: '#fff',
                        callbacks: {
                            label: ctx => {
                                const t = ctx.chart.data.datasets[0].data.reduce((a,b) => a+b, 0);
                                return `${ctx.label}: ${ctx.raw} (${((ctx.raw/t)*100).toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        });

        const legend = document.getElementById('customLegend');
        legend.innerHTML = '';
        const total = data.reduce((a,b) => a+b, 0);
        labels.forEach((label, i) => {
            const pct = ((data[i]/total)*100).toFixed(1);
            const li  = document.createElement('li');
            li.innerHTML = `<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${colors[i]};"></span>${label}: <strong>${data[i]}</strong> <span style="color:#9ca3af;">(${pct}%)</span>`;
            legend.appendChild(li);
        });

        document.getElementById('pieSummary').textContent = `${viewLabel} · ${dataKey.replace(/_/g,' ')}`;
    }

    // Event listeners (all original IDs preserved)
    document.getElementById('yearDropdown').addEventListener('change', function () {
        updateMonthDropdown();
        fetchStats();
        renderPieChart(document.getElementById('pieChartTypeSelector').value);
    });
    document.getElementById('monthDropdown').addEventListener('change', function () {
        fetchStats();
        renderPieChart(document.getElementById('pieChartTypeSelector').value);
    });
    document.getElementById('statDropdown').addEventListener('change', fetchStats);
    document.getElementById('pieChartTypeSelector').addEventListener('change', function () {
        renderPieChart(this.value);
    });

    // Initial load + deficiency chart
    document.addEventListener('DOMContentLoaded', function () {
        fetchStats();

        fetch("{{ route('admin.statistics.deficiencies') }}")
            .then(res => res.json())
            .then(data => {
                const defCtx = document.getElementById('deficiencyChart').getContext('2d');
                new Chart(defCtx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Deficiency Count',
                            data: data.counts,
                            backgroundColor: 'rgba(217,119,6,.7)',
                            borderRadius: 6,
                            barThickness: 16
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#052e22', titleColor: '#74ff70', bodyColor: '#fff',
                                callbacks: { label: ctx => `${ctx.label}: ${ctx.raw} cases` }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: 'rgba(6,78,59,.06)' },
                                ticks: { font: { family: 'DM Sans', size: 11 }, color: '#6b7280' },
                                title: { display: true, text: 'Number of Cases', font: { family: 'DM Sans', size: 11 }, color: '#6b7280' }
                            },
                            y: { ticks: { font: { family: 'DM Sans', size: 11 }, color: '#374151', autoSkip: false, maxRotation: 0 } }
                        }
                    }
                });
                document.getElementById('deficiencySummary').innerHTML = data.summary;
            });
    });
</script>