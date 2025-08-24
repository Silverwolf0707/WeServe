<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Budget Statistical Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .card {
            border-radius: 12px;
        }

        .chart-card {
            background: #fff;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        canvas {
            max-width: 100%;
            height: auto !important;
        }

        .form-select-sm,
        .form-control-sm {
            min-width: 110px;
        }

        .card-header {
            padding: 0.5rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }
    </style>
</head>

<body>
    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div
            class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
            <div class="text-success fw-semibold">
                <i class="fas fa-coins me-2"></i>Budget Analysis
            </div>
            <div class="d-flex gap-2">
                <select id="yearDropdown" class="form-select form-select-sm">
                    <!-- Years populated dynamically -->
                </select>
                <select id="statDropdown" class="form-select form-select-sm">
                    <option value="case_type">Case Type</option>
                    <option value="case_category">Case Category</option>
                </select>
            </div>
        </div>

        <div class="card-body pt-3 pb-1">
            <div class="row g-3">
                <!-- Mean/Median/Mode Chart -->
                <div class="col-md-6">
                    <div class="chart-card">
                        <canvas id="meanMedianModeChart" height="180"></canvas>
                    </div>
                </div>

                <!-- Std Dev / Variance Chart -->
                <div class="col-md-6">
                    <div class="chart-card">
                        <canvas id="standardDeviationVarianceChart" height="180"></canvas>
                    </div>
                </div>

                <!-- Statistical Summary -->
                <div class="col-lg-4">
                    <div class="card shadow-sm summary-equal-height h-100 border-0"
                        style="background: #e9fbe7; border-radius: 1rem;">
                        <div class="card-header d-flex align-items-center text-white border-0"
                            style="background-color: #28a745; border-radius: 1rem 1rem 0 0; padding: 1rem;">
                            <i class="fas fa-clipboard-list me-2 fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Budget Summary</h6>
                        </div>
                        <div class="card-body d-flex flex-column gap-3" style="padding: 1.2rem;">
                            <select id="summaryLabelDropdown" class="form-select form-select-sm mb-3"></select>
                            <div class="d-flex align-items-start text-dark">
                                <i class="fas fa-calculator text-success me-3 fs-5 mt-1"></i>
                                <span><strong>Mean:</strong> <span class="text-muted" id="summaryMean">—</span></span>
                            </div>
                            <div class="d-flex align-items-start text-dark">
                                <i class="fas fa-arrows-alt-v text-success me-3 fs-5 mt-1"></i>
                                <span><strong>Median:</strong> <span class="text-muted" id="summaryMedian">—</span></span>
                            </div>
                            <div class="d-flex align-items-start text-dark">
                                <i class="fas fa-chart-bar text-success me-3 fs-5 mt-1"></i>
                                <span><strong>Mode:</strong> <span class="text-muted" id="summaryMode">—</span></span>
                            </div>
                            <div class="d-flex align-items-start text-dark">
                                <i class="fas fa-wave-square text-success me-3 fs-5 mt-1"></i>
                                <span><strong>Std Dev:</strong> <span class="text-muted" id="summaryStdDev">—</span></span>
                            </div>
                            <div class="d-flex align-items-start text-dark">
                                <i class="fas fa-braille text-success me-3 fs-5 mt-1"></i>
                                <span><strong>Variance:</strong> <span class="text-muted" id="summaryVariance">—</span></span>
                            </div>
                                                      <p class="text-secondary small mt-3 mb-0">
                                This summary interprets the selected category's data for easier understanding. <br>
                                <strong>Mean:</strong> Average value.<br>
                                <strong>Median:</strong> Middle value.<br>
                                <strong>Mode:</strong> Most common value.<br>
                                <strong>Std Dev:</strong> Typical deviation from the mean.<br>
                                <strong>Variance:</strong> How spread out the values are.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-md-4 text-center">
                    <div class="chart-card h-100">
                        <div class="mb-2">
                            <select id="pieChartTypeSelector" class="form-select form-select-sm w-75 mx-auto mb-2">
                                <option value="total_applications_by_category">Applications by Category</option>
                                <option value="total_applications_by_type">Applications by Type</option>
                            </select>
                        </div>
                        <canvas id="categoryChart" style="max-width:260px; margin:20px auto 0 auto;"></canvas>
                        <ul id="customLegend"
                            class="list-unstyled mt-3 small d-flex flex-wrap justify-content-center gap-2"></ul>
                        <p id="pieSummary" class="mt-2 small text-muted text-center"></p>
                    </div>
                </div>
                                <!-- Document Deficiency Breakdown Panel -->
                <div class="col-md-4">
                    <div class="card shadow-sm summary-equal-height h-100 border border-warning"
                        style="background: #fef9e7; border-radius: 1rem;">
                        <div class="card-header d-flex align-items-center text-white border-0"
                            style="background-color: #0eb941; border-radius: 1rem 1rem 0 0;">
                            <h6 class="mb-0 text-center w-100">
                                <i class="fas fa-file-excel me-1"></i>Document Deficiency Breakdown
                            </h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <canvas id="deficiencyChart" style="max-height: 260px;"></canvas>

                            <p id="deficiencySummary" class="text-muted small mt-3 text-center"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
<script>
    let meanMedianModeChart = null;
    let dispersionChart = null;
    let pieChart = null;
    let cachedData = null;

    async function fetchStats() {
        try {
            if (!cachedData) {
                const res = await fetch('/admin/statistics/get-statistics?type=budget');
                cachedData = await res.json();

                // Populate year dropdown dynamically
                const yearDropdown = document.getElementById('yearDropdown');
                const years = Object.keys(cachedData.yearly || {});
                yearDropdown.innerHTML = '';
                years.forEach((y, i) => {
                    const opt = document.createElement('option');
                    opt.value = y;
                    opt.textContent = y;
                    if (i === 0) opt.selected = true;
                    yearDropdown.appendChild(opt);
                });
            }

            const year = document.getElementById('yearDropdown').value;
            const statType = document.getElementById('statDropdown').value;

            const yearData = cachedData.yearly[year];
            if (!yearData) return;

            const statsKey = statType === 'case_type' ? 'budget_stats_by_type' : 'budget_stats_by_category';
            const stats = yearData[statsKey];
            if (!stats) return;

            const labels = Object.keys(stats);
            const mean = labels.map(l => stats[l].mean);
            const median = labels.map(l => stats[l].median);
            const mode = labels.map(l => Array.isArray(stats[l].mode) && stats[l].mode.length ? stats[l].mode[0] : 0);
            const variance = labels.map(l => stats[l].variance);
            const stdDev = labels.map(l => stats[l].std_dev);

            updateCharts(labels, mean, median, mode, variance, stdDev);
            updateBudgetSummary(stats); // ✅ Fixed function call
            renderPieChart('total_applications_by_category', year);

        } catch (err) {
            console.error(err);
        }
    }

    function updateCharts(labels, mean, median, mode, variance, stdDev) {
        if (meanMedianModeChart) meanMedianModeChart.destroy();
        if (dispersionChart) dispersionChart.destroy();

        meanMedianModeChart = new Chart(document.getElementById('meanMedianModeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Mean', data: mean, backgroundColor: '#28a745' },
                    { label: 'Median', data: median, backgroundColor: '#007bff' },
                    { label: 'Mode', data: mode, backgroundColor: '#ffc107' }
                ]
            },
            options: { responsive: true }
        });

        dispersionChart = new Chart(document.getElementById('standardDeviationVarianceChart').getContext('2d'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Std Dev', data: stdDev, borderColor: '#28a745', fill: false, tension: 0.3 },
                    { label: 'Variance', data: variance, borderColor: '#007bff', fill: false, tension: 0.3 }
                ]
            },
            options: { responsive: true }
        });
    }

    function updateBudgetSummary(stats) {
        const labels = Object.keys(stats);
        if (!labels.length) return;

        const dropdown = document.getElementById('summaryLabelDropdown');
        dropdown.innerHTML = ''; // clear previous options

        // Populate dropdown
        labels.forEach((label, idx) => {
            const option = document.createElement('option');
            option.value = idx; // store index
            option.textContent = label;
            dropdown.appendChild(option);
        });

        function showSummary(index) {
            const label = labels[index];
            const stat = stats[label];

            // Display Mean
            document.getElementById('summaryMean').innerHTML =
                `${stat.mean.toLocaleString()} <br><small>Average value</small>`;

            // Display Median
            document.getElementById('summaryMedian').innerHTML =
                `${stat.median.toLocaleString()} <br><small>Middle value</small>`;

            // Display Mode
            document.getElementById('summaryMode').innerHTML =
                `${stat.mode.join(', ')} <br><small>Most frequent value(s)</small>`;

            // Display Std Dev
            document.getElementById('summaryStdDev').innerHTML =
                `${stat.std_dev.toLocaleString()} <br><small>Typical deviation, budget are ±${stat.std_dev.toLocaleString()}</small>`;

            // Display Variance or Spread
            if (stat.sample_spread && stat.sample_spread.length) {
                const minVal = Math.min(...stat.sample_spread);
                const maxVal = Math.max(...stat.sample_spread);
                document.getElementById('summaryVariance').innerHTML =
                    `${minVal.toLocaleString()} – ${maxVal.toLocaleString()} <br><small>Range of values</small>`;
            } else {
                document.getElementById('summaryVariance').innerHTML =
                    `${stat.variance.toLocaleString()} <br><small>Variance</small>`;
            }
        }

        // Default to first item
        showSummary(0);

        // Update summary on dropdown change
        dropdown.onchange = function() {
            showSummary(this.value);
        };
    }

    function renderPieChart(dataKey, year) {
        if (!cachedData) return;
        const yearData = cachedData.yearly[year];
        if (!yearData || !yearData[dataKey]) return;

        const dataObj = yearData[dataKey];
        const labels = Object.keys(dataObj);
        const data = Object.values(dataObj);

        if (pieChart) pieChart.destroy();
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const colors = labels.map((_, i) => `hsl(${i * 60}, 70%, 50%)`);

        pieChart = new Chart(ctx, {
            type: 'doughnut',
            data: { labels, datasets: [{ data, backgroundColor: colors }] },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        const legend = document.getElementById('customLegend');
        legend.innerHTML = '';
        labels.forEach((lbl, i) => {
            const li = document.createElement('li');
            li.innerHTML = `<span style="display:inline-block;width:12px;height:12px;background:${colors[i]};margin-right:5px;"></span>${lbl}: ${data[i]}`;
            legend.appendChild(li);
        });
    }

    // Event listeners
    document.getElementById('yearDropdown').addEventListener('change', fetchStats);
    document.getElementById('statDropdown').addEventListener('change', fetchStats);
    document.getElementById('pieChartTypeSelector').addEventListener('change', () =>
        renderPieChart(document.getElementById('pieChartTypeSelector').value, document.getElementById('yearDropdown').value)
    );

    // Initial fetch
    fetchStats();
</script>

</body>
</html>
