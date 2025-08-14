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
        <!-- Summary Boxes -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm bg-light-blue border-blue-500">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Top Assistance</div>
                            <div class="fw-semibold fs-5">{{ $top_assistance ?? 'Medical' }}</div>
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
                            <div class="fw-semibold fs-5">{{ $most_common_category ?? 'Senior' }}</div>
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
                            <div class="fw-semibold fs-5">{{ $total_applicants ?? 0 }}</div>
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
                            <div class="fw-semibold fs-5">{{ $average_processing_time ?? '0 days' }}</div>
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
                async function loadStlData(category = null, component = 'observed', year = null) {
                    const res = await fetch('/admin/timeseries/get-stl-json');
                    const json = await res.json();

                    // Populate category selector dynamically if empty
                    const categorySelector = document.getElementById('caseTypeSelector');
                    if (categorySelector.options.length <= 1) {
                        categorySelector.innerHTML = '';
                        Object.keys(json).forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat;
                            option.textContent = cat;
                            categorySelector.appendChild(option);
                        });
                        const allOption = document.createElement('option');
                        allOption.value = 'ALL';
                        allOption.textContent = 'ALL';
                        categorySelector.appendChild(allOption);
                    }

                    if (!category) {
                        category = categorySelector.value || Object.keys(json)[0];
                    }

                    // Populate year selector dynamically (current year to current year - 4)
                    const yearSelector = document.getElementById('yearSelector');
                    if (yearSelector.options.length <= 1) {
                        const currentYear = new Date().getFullYear();
                        yearSelector.innerHTML = '';
                        for (let y = currentYear; y >= currentYear - 4; y--) {
                            const opt = document.createElement('option');
                            opt.value = y.toString();
                            opt.textContent = y.toString();
                            yearSelector.appendChild(opt);
                        }
                    }

                    if (!year) {
                        year = yearSelector.value || new Date().getFullYear().toString();
                    }

                    let labels = [];
                    let dataForYear = [];

                    if (category === 'ALL') {
                        const categories = Object.keys(json);
                        if (categories.length === 0) {
                            console.error('No categories found in data.');
                            return;
                        }
                        const dates = json[categories[0]].dates;
                        const yearIndices = dates
                            .map((dateStr, i) => dateStr.startsWith(year + '-') ? i : -1)
                            .filter(i => i !== -1);
                        if (yearIndices.length === 0) {
                            console.error(`No data for year ${year} in ALL categories`);
                            return;
                        }

                        dataForYear = yearIndices.map(i => {
                            return categories.reduce((sum, cat) => {
                                return sum + (json[cat][component][i] || 0);
                            }, 0);
                        });

                        labels = yearIndices.map(i => {
                            const monthNum = dates[i].split('-')[1];
                            return new Date(2000, parseInt(monthNum) - 1).toLocaleString('default', {
                                month: 'short'
                            });
                        });
                    } else {
                        const dataset = json[category];
                        if (!dataset) {
                            console.error('No data for category:', category);
                            return;
                        }
                        const yearIndices = dataset.dates
                            .map((dateStr, i) => dateStr.startsWith(year + '-') ? i : -1)
                            .filter(i => i !== -1);
                        if (yearIndices.length === 0) {
                            console.error(`No data for year ${year} in category ${category}`);
                            return;
                        }

                        dataForYear = yearIndices.map(i => dataset[component][i]);
                        labels = yearIndices.map(i => {
                            const monthNum = dataset.dates[i].split('-')[1];
                            return new Date(2000, parseInt(monthNum) - 1).toLocaleString('default', {
                                month: 'short'
                            });
                        });
                    }

                    renderChart(labels, dataForYear, component);
                    updateSummaryText(component, category, year, dataForYear);
                }

                function renderChart(labels, data, component) {
                    const dataMap = {
                        observed: {
                            color: '#3b82f6',
                            fill: 'rgba(59, 130, 246, 0.2)'
                        },
                        trend: {
                            color: '#10b981',
                            fill: 'rgba(16, 185, 129, 0.2)'
                        },
                        seasonal: {
                            color: '#facc15',
                            fill: 'rgba(250, 204, 21, 0.3)'
                        },
                        residual: {
                            color: '#ef4444',
                            fill: 'rgba(239, 68, 68, 0.2)'
                        }
                    };

                    const ctx = document.getElementById('combinedChart').getContext('2d');
                    if (window.chartInstance) window.chartInstance.destroy();

                    window.chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: component.charAt(0).toUpperCase() + component.slice(1),
                                data: data,
                                borderColor: dataMap[component].color,
                                backgroundColor: dataMap[component].fill,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Month'
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function updateSummaryText(component, category, year, data) {
                    const total = data.reduce((a, b) => a + b, 0);
                    let categoryText = category === 'ALL' ? 'all assistance types combined' : `"${category}"`;

                    // Helper to compare start vs end for trend
                    const startVal = data[0] || 0;
                    const endVal = data[data.length - 1] || 0;
                    const diff = endVal - startVal;
                    const diffPercent = startVal ? (diff / startVal) * 100 : 0;

                    let summaryHTML = '';

                    switch (component) {
                        case 'observed':
                            summaryHTML = `
        <h6 class="fw-bold mb-2">🔍 Observed Data Summary (${year})</h6>
        <p>The total number of applications for <strong>${categoryText}</strong> in <strong>${year}</strong> is <strong>${total.toLocaleString()}</strong>.</p>
        <p>This represents the actual number of cases processed throughout the year.</p>
        <p>Overall, this gives you a clear picture of workload and demand for assistance.</p>
      `;
                            break;

                        case 'trend':
                            let trendConclusion = 'The demand appears stable over the year.';
                            if (diffPercent > 5) {
                                trendConclusion = 'The demand is increasing steadily over the year.';
                            } else if (diffPercent < -5) {
                                trendConclusion = 'The demand is decreasing over the year.';
                            }
                            summaryHTML = `
        <h6 class="fw-bold mb-2">📈 Trend Data Summary (${year})</h6>
        <p>The trend component shows the general direction of applications for <strong>${categoryText}</strong>.</p>
        <p>The count started at about <strong>${startVal.toFixed(2)}</strong> and ended at <strong>${endVal.toFixed(2)}</strong>.</p>
        <p><strong>Conclusion:</strong> ${trendConclusion}</p>
        <p>This helps you plan resources for the coming months accordingly.</p>
      `;
                            break;

                        case 'seasonal':
                            // Find peak and trough values and their months
                            const maxVal = Math.max(...data);
                            const minVal = Math.min(...data);
                            const peakDiff = maxVal - minVal;

                            // Find indices for peak and trough
                            const peakIndices = data
                                .map((val, idx) => val === maxVal ? idx : -1)
                                .filter(idx => idx !== -1);

                            const troughIndices = data
                                .map((val, idx) => val === minVal ? idx : -1)
                                .filter(idx => idx !== -1);

                            // Map indices to month names
                            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                'July', 'August', 'September', 'October', 'November', 'December'
                            ];

                            // Filter only months actually in the data length (in case partial year)
                            const peakMonths = peakIndices.map(i => monthNames[i]).join(', ');
                            const troughMonths = troughIndices.map(i => monthNames[i]).join(', ');

                            // Seasonal conclusion threshold (tweakable)
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
                            const avgResidual = total / data.length;
                            const residualConclusion = avgResidual < 5 ?
                                'Residuals are small, meaning the model explains most of the variations well.' :
                                'Residuals are relatively large, indicating some unexpected fluctuations or anomalies.';

                            summaryHTML = `
        <h6 class="fw-bold mb-2">🌊 Residual Data Summary (${year})</h6>
        <p>Residuals represent unexpected changes not explained by trend or seasonality for <strong>${categoryText}</strong>.</p>
        <p>The average residual value is about <strong>${avgResidual.toFixed(2)}</strong>.</p>
        <p><strong>Conclusion:</strong> ${residualConclusion}</p>
        <p>Keep monitoring to spot unusual events early.</p>
      `;
                            break;

                        default:
                            summaryHTML = '<p>No summary available for this component.</p>';
                    }

                    const summaryDiv = document.getElementById('summary-content');
                    summaryDiv.innerHTML = summaryHTML;
                }


                // Initialize and event listeners remain as before

                document.getElementById('chartSelector').addEventListener('change', () => {
                    loadStlData(
                        document.getElementById('caseTypeSelector').value,
                        document.getElementById('chartSelector').value,
                        document.getElementById('yearSelector').value
                    );
                });

                document.getElementById('yearSelector').addEventListener('change', () => {
                    loadStlData(
                        document.getElementById('caseTypeSelector').value,
                        document.getElementById('chartSelector').value,
                        document.getElementById('yearSelector').value
                    );
                });

                document.getElementById('caseTypeSelector').addEventListener('change', () => {
                    loadStlData(
                        document.getElementById('caseTypeSelector').value,
                        document.getElementById('chartSelector').value,
                        document.getElementById('yearSelector').value
                    );
                });

                window.addEventListener('DOMContentLoaded', () => {
                    loadStlData();
                });
            </script>


</body>

</html>
