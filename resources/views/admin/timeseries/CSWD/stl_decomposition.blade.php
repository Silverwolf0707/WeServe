<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Statistical Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fbfc;
        }

        .card {
            border: none;
            border-radius: 1rem;
            transition: box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-card .card-body {
            min-height: 100px;
        }

        .fs-5 {
            font-size: 1.15rem;
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card Color Variants */
        .bg-light-blue {
            background-color: #e8f1ff;
        }

        .bg-light-green {
            background-color: #e9f9ef;
        }

        .bg-light-yellow {
            background-color: #fffbe7;
        }

        .bg-light-red {
            background-color: #ffeaea;
        }

        .bg-blue {
            background-color: #3b82f6;
        }

        .bg-green {
            background-color: #10b981;
        }

        .bg-yellow {
            background-color: #facc15;
        }

        .bg-red {
            background-color: #ef4444;
        }

        .border-blue-500 {
            border-left: 6px solid #3b82f6 !important;
        }

        .border-green-500 {
            border-left: 6px solid #10b981 !important;
        }

        .border-yellow-500 {
            border-left: 6px solid #facc15 !important;
        }

        .border-red-500 {
            border-left: 6px solid #ef4444 !important;
        }

        .summary-equal-height {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .summary-equal-height .card-body {
            flex-grow: 1;
            justify-content: start;
        }

        .summary-equal-height .list-group {
            margin-top: -0.25rem;
        }

        #combinedChart {
            max-height: 320px;
        }

        select.form-select {
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid #eee;
            background-color: #f0f4f8;
        }

        .list-group-item {
            background: transparent;
            border: none;
            padding-left: 0;
            font-size: 0.95rem;
        }

        .list-group-item::before {
            content: "•";
            color: #10b981;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm bg-light-blue border-blue-500">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Top Assistance</div>
                            <div class="fw-semibold fs-5" id="topAssistance">Loading...</div>
                        </div>
                        <div class="icon-circle bg-blue text-white">
                            <i class="fas fa-hand-holding-medical fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm bg-light-green border-green-500">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Most Common</div>
                            <div class="fw-semibold fs-5" id="mostCommonCategory">Loading...</div>
                        </div>
                        <div class="icon-circle bg-green text-white">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm bg-light-yellow border-yellow-500">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Applicants</div>
                            <div class="fw-semibold fs-5" id="totalApplicants">Loading...</div>
                        </div>
                        <div class="icon-circle bg-yellow text-white">
                            <i class="fas fa-user-check fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm bg-light-red border-red-500">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Avg. Processing Time</div>
                            <div class="fw-semibold fs-5" id="averageProcessingTime">Loading...</div>
                        </div>
                        <div class="icon-circle bg-red text-white">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row align-items-stretch">
            <!-- Chart -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 h-100">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <h6 class="mb-0 fw-bold me-3">📈 Time Series Component</h6>

                            <select id="chartSelector" class="form-select form-select-sm me-2" style="width: auto;">
                                <option value="observed">Observed</option>
                                <option value="seasonal">Seasonal</option>
                                <option value="trend">Trend</option>
                                <option value="residual">Residual</option>
                            </select>

                            <select id="yearSelector" class="form-select form-select-sm me-2" style="width: auto;">

                            </select>

                            <select id="caseTypeSelector" class="form-select form-select-sm me-2" style="width: auto;">

                            </select>

                        </div>
                    </div>

                    <div class="card-body">
                        <canvas id="combinedChart" height="300" class="w-100"></canvas>
                    </div>
                </div>
            </div>


            <!-- Summary Report -->
            <div class="col-lg-4">
                <div class="card shadow-sm summary-equal-height h-100 border border-primary"
                    style="background: #e0eafc; border-radius: 1rem;">

                    <!-- Header -->
                    <div class="card-header d-flex align-items-center text-white border-0"
                        style="background-color: #004080; border-radius: 1rem 1rem 0 0; padding: 1rem;">
                        <i class="fas fa-chart-line me-2 fs-5"></i>
                        <h6 class="mb-0 fw-semibold">STL Decomposition Insights</h6>
                    </div>

                    <!-- Body -->
                    <div class="card-body" style="padding: 1.2rem;">
                        <div id="summary-content" class="text-dark fs-6">
                            <!-- Dynamic content will be injected here -->
                        </div>
                    </div>
                </div>
            </div>



<script>
async function loadStlData(category = 'ALL', component = 'observed', year = 'ALL') {
    const chartContainer = document.getElementById('combinedChart').parentElement;

    try {
        const res = await fetch('/admin/timeseries/get-stl-json?type=cswd');
        const json = await res.json();

        if (!json || Object.keys(json).length === 0) {
            chartContainer.innerHTML = `<div class="text-center text-muted py-5">
                <strong>⚠ STL Chart can't load because of lack of data.</strong><br>
                Please upload at least 1–2 years of records to generate decomposition.
            </div>`;
            return;
        }

        const caseSelector = document.getElementById('caseTypeSelector');
        if (caseSelector.options.length === 0) {
            caseSelector.innerHTML = '';
            const allOpt = document.createElement('option');
            allOpt.value = 'ALL';
            allOpt.textContent = 'ALL';
            caseSelector.appendChild(allOpt);

            Object.keys(json).forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat;
                opt.textContent = cat;
                caseSelector.appendChild(opt);
            });
        }

        if (!category) category = caseSelector.value || 'ALL';
        caseSelector.value = category;

        const yearSelector = document.getElementById('yearSelector');
        if (yearSelector.options.length === 0) {
            yearSelector.innerHTML = '';
            const allYearOpt = document.createElement('option');
            allYearOpt.value = 'ALL';
            allYearOpt.textContent = 'ALL';
            yearSelector.appendChild(allYearOpt);

            let allDates = [];
            if (category === 'ALL') {
                const cats = Object.keys(json);
                allDates = json[cats[0]].dates;
            } else {
                allDates = json[category].dates;
            }
            const years = [...new Set(allDates.map(d => d.split('-')[0]))].sort((a, b) => b - a);
            years.forEach(y => {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                yearSelector.appendChild(opt);
            });
        }

        if (!year) year = yearSelector.value || 'ALL';
        yearSelector.value = year;

        let labels = [];
        let dataForYear = [];

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
            chartContainer.innerHTML = `<div class="text-center text-muted py-5">
                <strong>⚠ STL Chart can't load because of lack of data.</strong><br>
                At least 12+ months of records are required.
            </div>`;
            return;
        }

        if (!document.getElementById('combinedChart')) {
            chartContainer.innerHTML = `<canvas id="combinedChart" height="300" class="w-100"></canvas>`;
        }

        // --- render as before ---
        renderChart(labels, dataForYear, component);
        updateSummaryText(component, category, year, dataForYear, labels);

    } catch (err) {
        console.error("Error loading STL data:", err);
        chartContainer.innerHTML = `<div class="text-center text-danger py-5">
            Failed to load STL data.
        </div>`;
    }
}

function renderChart(labels, data, component) {
    const map = {
        observed: { color: '#3b82f6', fill: 'rgba(59,130,246,0.2)' },
        trend: { color: '#10b981', fill: 'rgba(16,185,129,0.2)' },
        seasonal: { color: '#facc15', fill: 'rgba(250,204,21,0.3)' },
        residual: { color: '#ef4444', fill: 'rgba(239,68,68,0.2)' }
    };
    const ctx = document.getElementById('combinedChart').getContext('2d');
    if (window.chartInstance) window.chartInstance.destroy();

    const displayLabels = labels.map(d => {
        const [y, m] = d.split('-');
        const monthName = new Date(y, parseInt(m) - 1).toLocaleString('default', { month: 'short' });
        return `${monthName} ${y}`;
    });

    let yMin = Math.min(...data) * 0.95;
    let yMax = Math.max(...data) * 1.05;
    if (component === 'residual') {
        const absMax = Math.max(...data.map(v => Math.abs(v)));
        yMin = -absMax * 1.05;
        yMax = absMax * 1.05;
    }

    window.chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: displayLabels,
            datasets: [{
                label: component,
                data,
                borderColor: map[component].color,
                backgroundColor: map[component].fill,
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { beginAtZero: false, min: yMin, max: yMax }
            }
        }
    });
}

function updateSummaryText(component, category, year, data, labels) {
    const total = data.reduce((a, b) => a + b, 0);
    const categoryText = category === 'ALL' ? 'all assistance types combined' : `"${category}"`;
    const startVal = data[0] || 0;
    const endVal = data[data.length - 1] || 0;
    const diff = endVal - startVal;
    const diffPercent = startVal ? (diff / startVal) * 100 : 0;

    let summaryHTML = '';

    switch (component) {
   case 'observed':
    const monthlyAvg = total / data.length;
    const highestVal = Math.max(...data);
    const lowestVal = Math.min(...data);
    const highMonth = labels[data.indexOf(highestVal)];
    const lowMonth = labels[data.indexOf(lowestVal)];

    summaryHTML = `
        <h6 class="fw-bold mb-2">🔍 Observed Data Summary (${year})</h6>
        <p>For <strong>${categoryText}</strong>, the observed applications in <strong>${year}</strong> 
        totaled <strong>${total.toLocaleString()}</strong>.</p>
        <p>On average, about <strong>${monthlyAvg.toFixed(0)}</strong> applications were processed per month.</p>
        <p>The busiest period was <strong>${highMonth}</strong> with around <strong>${highestVal.toFixed(0)}</strong> applications, 
        while the quietest was <strong>${lowMonth}</strong> with about <strong>${lowestVal.toFixed(0)}</strong>.</p>
        <p><strong>Conclusion:</strong> This summary shows the actual workload and demand pattern without adjustments. 
        It’s the most direct reflection of how many people applied during the year.</p>
    `;
    break;


case 'trend':
    const trendStart = data[0] || 0;
    const trendEnd = data[data.length - 1] || 0;
    const trendDiff = trendEnd - trendStart;
    const trendDiffPercent = trendStart ? (trendDiff / trendStart) * 100 : 0;

    const trendMax = Math.max(...data);
    const trendMin = Math.min(...data);
    const trendMaxMonth = labels[data.indexOf(trendMax)];
    const trendMinMonth = labels[data.indexOf(trendMin)];

    let trendConclusion = 'The demand appears stable throughout the year.';
    if (trendDiffPercent >= 10) trendConclusion = 'There is a strong upward trend, showing increasing demand over time.';
    else if (trendDiffPercent > 3) trendConclusion = 'The trend shows a steady increase in demand.';
    else if (trendDiffPercent <= -10) trendConclusion = 'There is a clear downward trend, indicating declining demand.';
    else if (trendDiffPercent < -3) trendConclusion = 'The trend suggests a gradual decline in applications.';

    summaryHTML = `
        <h6 class="fw-bold mb-2">📈 Trend Data Summary (${year})</h6>
        <p>The trend component smooths short-term fluctuations to reveal the overall direction of <strong>${categoryText}</strong> applications.</p>
        <p>It started at about <strong>${trendStart.toFixed(2)}</strong> and ended at <strong>${trendEnd.toFixed(2)}</strong>, 
        a change of <strong>${trendDiff.toFixed(2)}</strong> (${trendDiffPercent.toFixed(1)}%).</p>
        <p>The highest trend level was observed in <strong>${trendMaxMonth}</strong> (~${trendMax.toFixed(2)}), 
        while the lowest was in <strong>${trendMinMonth}</strong> (~${trendMin.toFixed(2)}).</p>
        <p><strong>Conclusion:</strong> ${trendConclusion}</p>
        <p>This helps identify long-term workload changes and supports better resource allocation for the future.</p>
    `;
    break;


        case 'seasonal':
            const maxVal = Math.max(...data);
            const minVal = Math.min(...data);
            const peakDiff = maxVal - minVal;

            const peakIndices = data.map((v, i) => v === maxVal ? i : -1).filter(i => i !== -1);
            const troughIndices = data.map((v, i) => v === minVal ? i : -1).filter(i => i !== -1);

            const peakMonths = peakIndices.map(i => labels[i]).join(', ');
            const troughMonths = troughIndices.map(i => labels[i]).join(', ');

            const avgMonthly = total / data.length;
            const strongSeasonality = peakDiff > (0.25 * avgMonthly);

            const seasonalConclusion = strongSeasonality ?
                `There is a strong seasonal pattern with peak demand in ${peakMonths} and lower demand around ${troughMonths}.` :
                `Seasonal fluctuations are mild, with no very strong peaks or troughs. Peak months are ${peakMonths}.`;

            summaryHTML = `
                <h6 class="fw-bold mb-2">📅 Seasonal Data Summary (${year})</h6>
                <p>Seasonal patterns show regular ups and downs throughout the year for <strong>${categoryText}</strong>.</p>
                <p>The highest monthly application count is around <strong>${peakMonths}</strong> (${maxVal.toFixed(2)}), 
                   while the lowest is about <strong>${troughMonths}</strong> (${minVal.toFixed(2)}).</p>
                <p>This gives a difference of approximately <strong>${peakDiff.toFixed(0)}</strong> applications between peak and low months.</p>
                <p><strong>Conclusion:</strong> ${seasonalConclusion}</p>
                <p>Plan for extra support during these busy periods to meet demand effectively.</p>
            `;
            break;

   case 'residual':
    const residualAbs = data.map(v => Math.abs(v));
    const residualAvg = residualAbs.reduce((a, b) => a + b, 0) / residualAbs.length;
    const residualMax = Math.max(...residualAbs);
    const residualMin = Math.min(...residualAbs);

    const residualMaxMonth = labels[residualAbs.indexOf(residualMax)];
    const residualMinMonth = labels[residualAbs.indexOf(residualMin)];

    let residualConclusion = 'Residuals are low and evenly distributed, indicating the model explains most variations.';
    if (residualMax > residualAvg * 2) {
        residualConclusion = `A significant irregularity occurred around <strong>${residualMaxMonth}</strong>, suggesting an unusual spike or drop in applications.`;
    } else if (residualAvg > 2) {
        residualConclusion = 'Residuals are moderately high, meaning there are some unpredictable variations not captured by trend or seasonality.';
    }

    summaryHTML = `
        <h6 class="fw-bold mb-2">🔎 Residual Data Summary (${year})</h6>
        <p>The residual component represents random noise and irregularities in <strong>${categoryText}</strong> applications after removing trend and seasonality.</p>
        <p>On average, the size of these irregulars was about <strong>${residualAvg.toFixed(2)}</strong>.</p>
        <p>The largest irregularity was in <strong>${residualMaxMonth}</strong> (~${residualMax.toFixed(2)}), 
        while the smallest was in <strong>${residualMinMonth}</strong> (~${residualMin.toFixed(2)}).</p>
        <p><strong>Conclusion:</strong> ${residualConclusion}</p>
        <p>Monitoring residuals is useful to detect shocks, anomalies, or events outside normal patterns.</p>
    `;
    break;


        default:
            summaryHTML = '<p>No summary available for this component.</p>';
    }

    document.getElementById('summary-content').innerHTML = summaryHTML;
}

// Event listeners
['chartSelector', 'yearSelector', 'caseTypeSelector'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => {
        loadStlData(
            document.getElementById('caseTypeSelector').value,
            document.getElementById('chartSelector').value,
            document.getElementById('yearSelector').value
        );
    });
});

// Dashboard summary
async function loadDashboardSummary() {
    try {
        const res = await fetch('/admin/statistics/get-statistics?type=cswd');
        const json = await res.json();
        const summary = json.overall?.dashboard_summary;
        if (!summary) return;

        document.getElementById('topAssistance').textContent = summary.top_assistance || 'N/A';
        document.getElementById('mostCommonCategory').textContent = summary.most_common_category || 'N/A';
        document.getElementById('totalApplicants').textContent = summary.total_applicants?.toLocaleString() || 0;
        document.getElementById('averageProcessingTime').textContent = summary.average_processing_time || '0 days';
    } catch (err) {
        console.error('Error loading dashboard summary:', err);
    }
}

loadDashboardSummary();
loadStlData();
</script>


</body>

</html>
