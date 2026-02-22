<style>
    /* ═══════════════════════════════════════════
       Statistics — Forest-green design system
       ═══════════════════════════════════════════ */
    .stat-wrap { font-family: 'DM Sans', sans-serif; color: #052e22; margin-top: 0; }

    /* ── Card base (reuse stl-card vars) ── */
    .stat-card { background: #fff; border-radius: 12px; border: 1px solid #d1fae5; box-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06); margin-bottom: 18px; overflow: hidden; }
    .stat-card-hdr { display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: linear-gradient(135deg,#052e22 0%,#064e3b 100%); flex-wrap: wrap; }
    .stat-card-hdr-icon { width: 28px; height: 28px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .72rem; color: #74ff70; flex-shrink: 0; }
    .stat-card-hdr-title { font-size: .88rem; font-weight: 700; color: #fff; }
    .stat-card-hdr-badge { background: rgba(116,255,112,.15); border: 1px solid rgba(116,255,112,.3); border-radius: 20px; padding: 1px 9px; font-size: .67rem; font-weight: 700; color: #74ff70; margin-left: auto; }
    .stat-card-body { padding: 18px; }

    /* ── Filter bar ── */
    .stat-filter-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
    .stat-label { font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.6); white-space: nowrap; }
    .stat-select { height: 28px; border: 1px solid rgba(116,255,112,.28); border-radius: 7px; background: rgba(116,255,112,.08); color: #fff; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 26px 0 9px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(116,255,112,.7)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: all .15s; min-width: 100px; }
    .stat-select:focus { border-color: rgba(116,255,112,.65); background-color: rgba(116,255,112,.14); }
    .stat-select option { background: #052e22; color: #fff; }

    /* ── View title inside card ── */
    #statViewTitle { font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.5); }

    /* ── Summary panel ── */
    .stat-summary-panel { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1px solid #d1fae5; border-radius: 12px; height: 100%; display: flex; flex-direction: column; overflow: hidden; }
    .stat-summary-hdr { padding: 12px 16px; background: linear-gradient(135deg,#052e22,#064e3b); display: flex; align-items: center; gap: 8px; }
    .stat-summary-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #74ff70; flex-shrink: 0; }
    .stat-summary-hdr-title { font-size: .84rem; font-weight: 700; color: #fff; }
    .stat-summary-body { padding: 14px 16px; flex: 1; overflow: auto; }
    .stat-summary-select { width: 100%; height: 30px; border: 1.5px solid #d1fae5; border-radius: 8px; background: #fff; color: #052e22; font-size: .75rem; font-family: 'DM Sans', sans-serif; padding: 0 10px; outline: none; margin-bottom: 14px; cursor: pointer; }
    .stat-summary-select:focus { border-color: #064e3b; }

    /* Metric rows */
    .stat-metric { display: flex; align-items: flex-start; gap: 10px; padding: 9px 0; border-bottom: 1px solid #d1fae5; }
    .stat-metric:last-of-type { border-bottom: none; }
    .stat-metric-icon { width: 28px; height: 28px; border-radius: 8px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.28); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #064e3b; flex-shrink: 0; margin-top: 1px; }
    .stat-metric-label { font-size: .72rem; font-weight: 700; color: #3d7a62; text-transform: uppercase; letter-spacing: .04em; }
    .stat-metric-value { font-size: .88rem; font-weight: 700; color: #052e22; line-height: 1.3; }
    .stat-metric-sub { font-size: .7rem; color: #6b7280; margin-top: 1px; font-weight: 400; }
    .stat-legend-note { font-size: .7rem; color: #6b7280; line-height: 1.5; margin-top: 10px; padding-top: 10px; border-top: 1px solid #d1fae5; }
    .stat-legend-note strong { color: #064e3b; }

    /* ── Pie / donut panel ── */
    .stat-pie-panel { background: #fff; border: 1px solid #d1fae5; border-radius: 12px; height: 100%; padding: 14px; display: flex; flex-direction: column; align-items: center; }
    .stat-pie-select { width: 100%; max-width: 220px; height: 28px; border: 1.5px solid #d1fae5; border-radius: 8px; background: #fff; color: #052e22; font-size: .74rem; font-family: 'DM Sans', sans-serif; padding: 0 8px; outline: none; margin-bottom: 10px; cursor: pointer; }
    .stat-pie-select:focus { border-color: #064e3b; }
    #pieSummary { font-size: .71rem; color: #6b7280; text-align: center; margin-top: 6px; }
    #customLegend li { font-size: .72rem; display: flex; align-items: center; gap: 5px; }

    /* ── Deficiency panel ── */
    .stat-def-panel { background: #fffbeb; border: 1px solid rgba(245,158,11,.3); border-radius: 12px; height: 100%; overflow: hidden; }
    .stat-def-hdr { padding: 12px 16px; background: linear-gradient(135deg,#78350f,#d97706); display: flex; align-items: center; gap: 8px; }
    .stat-def-hdr-icon { width: 26px; height: 26px; border-radius: 7px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .65rem; color: #fff; flex-shrink: 0; }
    .stat-def-hdr-title { font-size: .84rem; font-weight: 700; color: #fff; }
    .stat-def-body { padding: 14px; display: flex; flex-direction: column; height: calc(100% - 50px); }
    #deficiencySummary { font-size: .74rem; color: #6b7280; text-align: center; margin-top: 8px; }

    @media (max-width:992px) { .stat-filter-bar { margin-left: 0; width: 100%; } }
</style>

<div class="stat-wrap">

    <div class="stat-card">
        <div class="stat-card-hdr">
            <div class="stat-card-hdr-icon"><i class="fas fa-chart-bar"></i></div>
            <span class="stat-card-hdr-title">Statistical Analysis</span>
            <span id="statViewTitle" style="margin-left:8px;"></span>
            <div class="stat-filter-bar">
                <span class="stat-label">Year:</span>
                <select id="yearDropdown"  class="stat-select"></select>

                <span class="stat-label">Month:</span>
                <select id="monthDropdown" class="stat-select">
                    <option value="yearly">Yearly View</option>
                </select>

                <span class="stat-label">View:</span>
                <select id="statDropdown" class="stat-select">
                    <option value="case_type">Case Type</option>
                    <option value="case_category">Case Category</option>
                </select>

                <span class="stat-label">Data:</span>
                <select id="typeDropdown" class="stat-select">
                    <option value="Age">Age</option>
                    <option value="Application">Application</option>
                </select>
            </div>
        </div>

        <div class="stat-card-body">

            {{-- Row 1: Central tendency + dispersion ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div style="background:#f8fffe;border:1px solid #d1fae5;border-radius:10px;padding:12px;">
                        <canvas id="meanMedianModeChart" height="200"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background:#f8fffe;border:1px solid #d1fae5;border-radius:10px;padding:12px;">
                        <canvas id="standardDeviationVarianceChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Row 2: Summary + Pie + Deficiency ── --}}
            <div class="row g-3">

                {{-- Statistical Summary ── --}}
                <div class="col-lg-4">
                    <div class="stat-summary-panel" style="min-height:360px;">
                        <div class="stat-summary-hdr">
                            <div class="stat-summary-hdr-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span class="stat-summary-hdr-title">Statistical Summary</span>
                        </div>
                        <div class="stat-summary-body">
                            <select id="summaryLabelDropdown" class="stat-summary-select"></select>

                            <div class="stat-metric">
                                <div class="stat-metric-icon"><i class="fas fa-calculator"></i></div>
                                <div>
                                    <div class="stat-metric-label">Mean</div>
                                    <div class="stat-metric-value" id="summaryMean">—</div>
                                    <div class="stat-metric-sub">Average value across the group</div>
                                </div>
                            </div>
                            <div class="stat-metric">
                                <div class="stat-metric-icon"><i class="fas fa-arrows-alt-v"></i></div>
                                <div>
                                    <div class="stat-metric-label">Median</div>
                                    <div class="stat-metric-value" id="summaryMedian">—</div>
                                    <div class="stat-metric-sub">Middle value when ordered</div>
                                </div>
                            </div>
                            <div class="stat-metric">
                                <div class="stat-metric-icon"><i class="fas fa-chart-bar"></i></div>
                                <div>
                                    <div class="stat-metric-label">Mode</div>
                                    <div class="stat-metric-value" id="summaryMode">—</div>
                                    <div class="stat-metric-sub">Most frequently occurring value</div>
                                </div>
                            </div>
                            <div class="stat-metric">
                                <div class="stat-metric-icon"><i class="fas fa-wave-square"></i></div>
                                <div>
                                    <div class="stat-metric-label">Std Dev</div>
                                    <div class="stat-metric-value" id="summaryStdDev">—</div>
                                    <div class="stat-metric-sub">Typical deviation from mean</div>
                                </div>
                            </div>
                            <div class="stat-metric">
                                <div class="stat-metric-icon"><i class="fas fa-braille"></i></div>
                                <div>
                                    <div class="stat-metric-label">Variance</div>
                                    <div class="stat-metric-value" id="summaryVariance">—</div>
                                    <div class="stat-metric-sub">Overall spread of values</div>
                                </div>
                            </div>

                            <div class="stat-legend-note">
                                <strong>How to read this:</strong> Mean shows the average, Median the middle point. If Mean &gt; Median significantly, there are high-outlier cases. High Std Dev means wide variation between applicants.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie / Donut chart ── --}}
                <div class="col-lg-4">
                    <div class="stat-pie-panel">
                        <select id="pieChartTypeSelector" class="stat-pie-select">
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
                    <div class="stat-def-panel" style="min-height:360px;">
                        <div class="stat-def-hdr">
                            <div class="stat-def-hdr-icon"><i class="fas fa-file-excel"></i></div>
                            <span class="stat-def-hdr-title">Document Deficiency Breakdown</span>
                        </div>
                        <div class="stat-def-body">
                            <canvas id="deficiencyChart" style="flex:1;max-height:280px;"></canvas>
                            <p id="deficiencySummary">No Deficiency Data</p>
                        </div>
                    </div>
                </div>

            </div>{{-- /row 2 --}}
        </div>
    </div>

</div>{{-- /stat-wrap --}}

<script>
    let meanMedianModeChart = null;
    let dispersionChart = null;
    let pieChart = null;
    let cachedData = null;
    let currentView = 'yearly';

    async function fetchStats() {
        try {
            if (!cachedData) {
                const response = await fetch('/admin/statistics/get-statistics?type=cswd');
                if (!response.ok) throw new Error('Network response was not ok');
                cachedData = await response.json();

                const yearDropdown = document.getElementById('yearDropdown');
                const years = Object.keys(cachedData.yearly || {});
                yearDropdown.innerHTML = '';
                years.sort((a, b) => b - a);
                years.forEach(year => {
                    const opt = document.createElement('option');
                    opt.value = year;
                    opt.textContent = year;
                    if (year == cachedData.default_selection.year) opt.selected = true;
                    yearDropdown.appendChild(opt);
                });

                updateMonthDropdown();
                renderPieChart('total_applications_by_category');
            }

            const statType      = document.getElementById('statDropdown').value;
            const dataType      = document.getElementById('typeDropdown').value;
            const selectedYear  = document.getElementById('yearDropdown').value;
            const selectedMonth = document.getElementById('monthDropdown').value;

            if (!statType || !dataType || !selectedYear) { clearChartsAndSummary(); return; }

            let statsData = null, viewLabel = '';
            if (selectedMonth === 'yearly' || selectedMonth === '') {
                currentView = 'yearly';
                statsData   = cachedData.yearly[selectedYear];
                viewLabel   = `${selectedYear} (Yearly)`;
            } else {
                currentView = 'monthly';
                if (cachedData.monthly?.[selectedYear]?.[selectedMonth]) {
                    statsData  = cachedData.monthly[selectedYear][selectedMonth];
                    viewLabel  = `${statsData.month_name || 'Month ' + selectedMonth} ${selectedYear}`;
                } else {
                    statsData = cachedData.yearly[selectedYear];
                    viewLabel = `${selectedYear} (Yearly)`;
                    document.getElementById('monthDropdown').value = 'yearly';
                }
            }

            if (!statsData) { clearChartsAndSummary(); return; }

            // Update the small view title in the header
            document.getElementById('statViewTitle').textContent = `· ${viewLabel}`;

            let statsKey = '';
            if (dataType === 'Age') {
                statsKey = statType === 'case_type' ? 'age_stats_by_type' : 'age_stats_by_category';
            } else {
                statsKey = statType === 'case_type' ? 'application_stats_by_type' : 'application_stats_by_category';
            }

            const stats = statsData[statsKey];
            if (!stats) { clearChartsAndSummary(); return; }

            const labels   = Object.keys(stats);
            const mean     = labels.map(l => stats[l].mean);
            const median   = labels.map(l => stats[l].median);
            const mode     = labels.map(l => { let m = stats[l].mode; return Array.isArray(m) ? (m.length ? m[0] : 0) : m||0; });
            const variance = labels.map(l => stats[l].variance);
            const stdDev   = labels.map(l => stats[l].std_dev);

            updateCharts(labels, mean, median, mode, variance, stdDev, dataType, viewLabel);
            updateSummary(labels, mean, median, mode, variance, stdDev, dataType, statsData);

        } catch (err) {
            console.error('Failed to fetch statistics:', err);
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
                opt.value = monthNum;
                opt.textContent = monthName;
                if (selectedYear == cachedData.default_selection.year && monthNum == cachedData.default_selection.month) opt.selected = true;
                monthDropdown.appendChild(opt);
            });
        }
    }

    function updateCharts(labels, mean, median, mode, variance, stdDev, dataType, viewLabel) {
        if (meanMedianModeChart) meanMedianModeChart.destroy();
        if (dispersionChart)     dispersionChart.destroy();

        const sharedOptions = {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 11 }, padding: 12, usePointStyle: true } },
                tooltip: { backgroundColor:'#052e22', titleColor:'#74ff70', bodyColor:'#fff', padding:10, titleFont:{family:'DM Sans'}, bodyFont:{family:'DM Sans'} }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(6,78,59,.06)' }, ticks: { font: { family:'DM Sans', size:11 }, color:'#6b7280' }, title: { display:true, text: dataType==='Age'?'Age':'Application Count', font:{family:'DM Sans',size:11}, color:'#6b7280' } },
                x: { ticks: { font:{family:'DM Sans',size:11}, color:'#374151', maxRotation:45, minRotation:45 }, grid: { color:'rgba(6,78,59,.04)' } }
            }
        };

        const ctx1 = document.getElementById('meanMedianModeChart').getContext('2d');
        meanMedianModeChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label:'Mean',   data:mean,   backgroundColor:'rgba(6,78,59,.75)',  borderRadius:5 },
                    { label:'Median', data:median, backgroundColor:'rgba(16,185,129,.7)', borderRadius:5 },
                    { label:'Mode',   data:mode,   backgroundColor:'rgba(116,255,112,.65)', borderRadius:5 },
                ]
            },
            options: { ...sharedOptions, plugins: { ...sharedOptions.plugins, title: { display:true, text:`${dataType} Distribution — ${viewLabel}`, font:{family:'DM Sans',size:13,weight:'600'}, color:'#052e22' } } }
        });

        const ctx2 = document.getElementById('standardDeviationVarianceChart').getContext('2d');
        dispersionChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label:'Std Dev',  data:stdDev,   borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,.10)', fill:true, tension:0.4, pointRadius:4, pointBackgroundColor:'#fca5a5', borderWidth:2 },
                    { label:'Variance', data:variance, borderColor:'#0ea5e9', backgroundColor:'rgba(14,165,233,.10)', fill:true, tension:0.4, pointRadius:4, pointBackgroundColor:'#7dd3fc', borderWidth:2 },
                ]
            },
            options: { ...sharedOptions, plugins: { ...sharedOptions.plugins, title: { display:true, text:`Dispersion Analysis — ${viewLabel}`, font:{family:'DM Sans',size:13,weight:'600'}, color:'#052e22' } } }
        });
    }

    function updateSummary(labels, meanArr, medianArr, modeArr, varianceArr, stdDevArr, dataType, statsData) {
        const typeLabel = dataType === 'Age' ? 'years' : 'applications';
        const dropdown  = document.getElementById('summaryLabelDropdown');
        dropdown.innerHTML = '';
        labels.forEach((label, i) => {
            const opt = document.createElement('option');
            opt.value = i; opt.textContent = label;
            dropdown.appendChild(opt);
        });

        function showSummary(index) {
            const m  = meanArr[index],  med = medianArr[index];
            const mo = modeArr[index],  sd  = stdDevArr[index];
            const v  = varianceArr[index];
            const label = labels[index];

            const statKey = dataType === 'Age'
                ? (document.getElementById('statDropdown').value === 'case_type' ? 'age_stats_by_type' : 'age_stats_by_category')
                : (document.getElementById('statDropdown').value === 'case_type' ? 'application_stats_by_type' : 'application_stats_by_category');
            const statObj = statsData[statKey][label];

            let spreadText = 'N/A';
            if (statObj.sample_spread?.length) {
                const min = Math.min(...statObj.sample_spread);
                const max = Math.max(...statObj.sample_spread);
                spreadText = `${min} – ${max} ${typeLabel}`;
            }

            document.getElementById('summaryMean').innerHTML    = `${m.toFixed(0)} ${typeLabel} <br><small style="color:#6b7280;font-weight:400;">Average for this group</small>`;
            document.getElementById('summaryMedian').innerHTML  = `${med.toFixed(0)} ${typeLabel} <br><small style="color:#6b7280;font-weight:400;">Middle value when ordered for this group</small>`;
            document.getElementById('summaryMode').innerHTML    = `${mo} ${typeLabel} <br><small style="color:#6b7280;font-weight:400;">Most frequent value</small>`;
            document.getElementById('summaryStdDev').innerHTML  = `${sd.toFixed(2)} <br><small style="color:#6b7280;font-weight:400;">Values typically vary ±${sd.toFixed(0)} ${typeLabel}</small>`;
            document.getElementById('summaryVariance').innerHTML = `${v.toFixed(2)} <br><small style="color:#6b7280;font-weight:400;">Spread: ${spreadText}</small>`;
        }

        showSummary(0);
        dropdown.onchange = function () { showSummary(this.value); };
    }

    function renderPieChart(dataKey) {
        if (!cachedData) return;
        const selectedYear  = document.getElementById('yearDropdown').value;
        const selectedMonth = document.getElementById('monthDropdown').value;
        let dataObj = null, viewLabel = '';

        if (selectedMonth === 'yearly' || selectedMonth === '') {
            const yd = cachedData.yearly[selectedYear];
            if (!yd?.[dataKey]) return;
            dataObj = yd[dataKey]; viewLabel = `${selectedYear} (Yearly)`;
        } else {
            if (cachedData.monthly?.[selectedYear]?.[selectedMonth]) {
                const md = cachedData.monthly[selectedYear][selectedMonth];
                if (!md[dataKey]) return;
                dataObj = md[dataKey]; viewLabel = `${md.month_name||'Month '+selectedMonth} ${selectedYear}`;
            } else {
                const yd = cachedData.yearly[selectedYear];
                if (!yd?.[dataKey]) return;
                dataObj = yd[dataKey]; viewLabel = `${selectedYear} (Yearly)`;
            }
        }

        const pieCtx = document.getElementById('categoryChart').getContext('2d');
        if (pieChart) pieChart.destroy();

        const labels    = Object.keys(dataObj);
        const data      = Object.values(dataObj);
        const chartType = dataKey === 'total_applications_by_type' ? 'doughnut' : 'pie';
        const colors    = ['#064e3b','#10b981','#34d399','#3b82f6','#f59e0b','#8b5cf6','#ef4444'];

        pieChart = new Chart(pieCtx, {
            type: chartType,
            data: {
                labels,
                datasets: [{ data, backgroundColor: colors.slice(0,labels.length), borderRadius:10, borderWidth:2, borderColor:'#fff' }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display:true, text:viewLabel, font:{family:'DM Sans',size:11}, color:'#6b7280' },
                    tooltip: {
                        backgroundColor:'#052e22', titleColor:'#74ff70', bodyColor:'#fff',
                        callbacks: { label: ctx => { const t = ctx.chart.data.datasets[0].data.reduce((a,b)=>a+b,0); return `${ctx.label}: ${ctx.raw} (${((ctx.raw/t)*100).toFixed(1)}%)`; } }
                    }
                }
            }
        });

        const legend = document.getElementById('customLegend');
        legend.innerHTML = '';
        const total = data.reduce((a,b)=>a+b,0);
        labels.forEach((label,i) => {
            const pct = ((data[i]/total)*100).toFixed(1);
            const li  = document.createElement('li');
            li.innerHTML = `<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${colors[i]};"></span>${label}: <strong>${data[i]}</strong> <span style="color:#9ca3af;">(${pct}%)</span>`;
            legend.appendChild(li);
        });
        document.getElementById('pieSummary').textContent = `${viewLabel} · ${dataKey.replace(/_/g,' ')}`;
    }

    function clearChartsAndSummary() {
        if (meanMedianModeChart) meanMedianModeChart.destroy();
        if (dispersionChart)     dispersionChart.destroy();
        if (pieChart)            pieChart.destroy();
        ['summaryMean','summaryMedian','summaryMode','summaryStdDev','summaryVariance'].forEach(id => {
            document.getElementById(id).textContent = '—';
        });
        const legend = document.getElementById('customLegend');
        if (legend) legend.innerHTML = '';
        document.getElementById('pieSummary').textContent = '';
    }

    // Event listeners (all original IDs preserved)
    document.getElementById('statDropdown').addEventListener('change', fetchStats);
    document.getElementById('typeDropdown').addEventListener('change', fetchStats);
    document.getElementById('pieChartTypeSelector').addEventListener('change', function () { renderPieChart(this.value); });
    document.getElementById('yearDropdown').addEventListener('change', function () {
        updateMonthDropdown(); fetchStats(); renderPieChart(document.getElementById('pieChartTypeSelector').value);
    });
    document.getElementById('monthDropdown').addEventListener('change', function () {
        fetchStats(); renderPieChart(document.getElementById('pieChartTypeSelector').value);
    });

    function runStatCharts() {
        fetchStats();

        fetch("{{ route('admin.statistics.deficiencies') }}")
            .then(res => res.json())
            .then(data => {
                const defCtx = document.getElementById('deficiencyChart').getContext('2d');
                new Chart(defCtx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{ label:'Deficiency Count', data:data.counts, backgroundColor:'rgba(217,119,6,.7)', borderRadius:6, barThickness:16 }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor:'#052e22', titleColor:'#74ff70', bodyColor:'#fff',
                                callbacks: { label: ctx => `${ctx.label}: ${ctx.raw} cases` }
                            }
                        },
                        scales: {
                            x: { beginAtZero:true, grid:{color:'rgba(6,78,59,.06)'}, ticks:{font:{family:'DM Sans',size:11},color:'#6b7280'}, title:{display:true,text:'Number of Cases',font:{family:'DM Sans',size:11},color:'#6b7280'} },
                            y: { ticks:{font:{family:'DM Sans',size:11},color:'#374151',autoSkip:false,maxRotation:0} }
                        }
                    }
                });
                document.getElementById('deficiencySummary').innerHTML = data.summary;
            });
    } // end runStatCharts

    document.addEventListener('DOMContentLoaded', runStatCharts);
</script>