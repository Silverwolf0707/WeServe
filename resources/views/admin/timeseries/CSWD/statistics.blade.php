<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Statistical Dashboard</title>
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
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
      <div class="text-primary fw-semibold">
        <i class="fas fa-chart-line me-2"></i>Statistical Analysis
      </div>
      <div class="d-flex gap-2">
        <select id="statDropdown" class="form-select form-select-sm">
          <option value="case_type">Case Type</option>
          <option value="case_category">Case Category</option>
        </select>
        <select id="typeDropdown" class="form-select form-select-sm">
          <option value="Age">Age</option>
          <option value="Application">Application</option>
        </select>
      </div>
    </div>

    <div class="card-body pt-3 pb-1">
      <div class="row g-3">
        <!-- Mean, Median, Mode Chart -->
        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="meanMedianModeChart" height="180"></canvas>
          </div>
        </div>

        <!-- Standard Deviation / Variance Chart -->
        <div class="col-md-6">
          <div class="chart-card">
            <div class="d-flex justify-content-end align-items-center mb-2">

            </div>
            <canvas id="standardDeviationVarianceChart" height="180"></canvas>
          </div>
        </div>

        <!-- Statistical Summary -->
        <div class="col-lg-4">
          <div class="card shadow-sm summary-equal-height h-100 border-0"
            style="background: linear-gradient(135deg, #e0eafc, #cfdef3); border-radius: 1rem;">
            <div class="card-header d-flex align-items-center text-white border-0"
              style="background-color: #004080; border-radius: 1rem 1rem 0 0; padding: 1rem;">
              <i class="fas fa-clipboard-list me-2 fs-5"></i>
              <h6 class="mb-0 fw-semibold">Statistical Summary</h6>
            </div>
            <div class="card-body d-flex flex-column gap-3" style="padding: 1.2rem;">
              <div class="d-flex align-items-start text-dark">
                <i class="fas fa-calculator text-primary me-3 fs-5 mt-1"></i>
                <span><strong>Mean:</strong> <span class="text-muted" id="summaryMean">—</span></span>
              </div>

              <div class="d-flex align-items-start text-dark">
                <i class="fas fa-arrows-alt-v text-primary me-3 fs-5 mt-1"></i>
                <span><strong>Median:</strong> <span class="text-muted" id="summaryMedian">—</span></span>
              </div>

              <div class="d-flex align-items-start text-dark">
                <i class="fas fa-chart-bar text-primary me-3 fs-5 mt-1"></i>
                <span><strong>Mode:</strong> <span class="text-muted" id="summaryMode">—</span></span>
              </div>

              <div class="d-flex align-items-start text-dark">
                <i class="fas fa-wave-square text-primary me-3 fs-5 mt-1"></i>
                <span><strong>Std Dev:</strong> <span class="text-muted" id="summaryStdDev">—</span></span>
              </div>

              <div class="d-flex align-items-start text-dark">
                <i class="fas fa-braille text-primary me-3 fs-5 mt-1"></i>
                <span><strong>Variance:</strong> <span class="text-muted" id="summaryVariance">—</span></span>
              </div>

              <div class="d-flex align-items-start text-muted mt-2 small">
                <i class="fas fa-calendar-alt me-2 mt-1"></i>
                <span>Date Selected: <span id="summaryTimeLabel">—</span></span>
              </div>

              <p class="text-secondary small mt-3 mb-0">
                This summary provides insights into the dispersion and central tendency of the selected category.
              </p>
            </div>
          </div>
        </div>


        <!-- Chart Panel -->
        <div class="col-md-4 text-center">
          <div class="chart-card h-100">
            <div class="mb-2">
              <select id="pieChartTypeSelector" class="form-select form-select-sm w-75 mx-auto mb-2">
                <option value="total_applications_by_category">Applications by Category</option>
                <option value="total_applications_by_type">Applications by Type</option>
              </select>

            </div>
            <div style="position:relative; width:100%; height:300px; max-width:260px; margin: 20px auto 0 auto;">
              <canvas id="categoryChart"></canvas>
            </div>

            <ul id="customLegend" class="list-unstyled mt-3 small d-flex flex-wrap justify-content-center gap-2"></ul>
            <p id="pieSummary" class="mt-2 small text-muted text-center"></p>
          </div>
        </div>

        <!-- Document Deficiency Breakdown Panel -->
        <div class="col-md-4">
          <div class="card shadow-sm summary-equal-height h-100 border border-warning"
            style="background: #fef9e7; border-radius: 1rem;">
            <div class="card-header d-flex align-items-center text-white border-0"
              style="background-color: #b9770e; border-radius: 1rem 1rem 0 0;">
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


  <script>
    let meanMedianModeChart = null;
    let dispersionChart = null;
    let pieChart = null;

    let cachedData = null;

    async function fetchStats() {
      try {
        // Fetch data only once and cache it
        if (!cachedData) {
          const response = await fetch('/admin/statistics/get-age-statistics?type=cswd');
          if (!response.ok) throw new Error('Network response was not ok');
          cachedData = await response.json();

          // Show initial pie chart with category data
          renderPieChart('total_applications_by_category');
        }

        const statType = document.getElementById('statDropdown').value;
        const dataType = document.getElementById('typeDropdown').value;

        if (!statType || !dataType) {
          // Clear charts or do nothing if incomplete selection
          clearChartsAndSummary();
          return;
        }

        // Pick stats group dynamically
        let statsKey = '';
        if (dataType === 'Age') {
          statsKey = statType === 'case_type' ? 'age_stats_by_type' : 'age_stats_by_category';
        } else if (dataType === 'Application') {
          statsKey = statType === 'case_type' ? 'application_stats_by_type' : 'application_stats_by_category';
        }

        const stats = cachedData[statsKey];
        if (!stats) {
          clearChartsAndSummary();
          return;
        }

        const labels = Object.keys(stats);

        // Extract chart data arrays
        const mean = labels.map(label => stats[label].mean);
        const median = labels.map(label => stats[label].median);
        const mode = labels.map(label => {
          let m = stats[label].mode;
          if (Array.isArray(m)) return m.length ? m[0] : 0;
          return m || 0;
        });
        const variance = labels.map(label => stats[label].variance);
        const stdDev = labels.map(label => stats[label].std_dev);

        // Update charts and summary
        updateCharts(labels, mean, median, mode, variance, stdDev, dataType);

      } catch (error) {
        console.error('Failed to fetch statistics:', error);
      }
    }

    function updateCharts(labels, mean, median, mode, variance, stdDev, dataType) {
      if (meanMedianModeChart) meanMedianModeChart.destroy();
      if (dispersionChart) dispersionChart.destroy();

      meanMedianModeChart = new Chart(
        document.getElementById('meanMedianModeChart').getContext('2d'),
        {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [
              { label: 'Mean', data: mean, backgroundColor: '#007bff' },
              { label: 'Median', data: median, backgroundColor: '#28a745' },
              { label: 'Mode', data: mode, backgroundColor: '#ffc107' },
            ],
          },
          options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
              y: {
                beginAtZero: true,
                title: { display: true, text: dataType === 'Age' ? 'Age' : 'Application Count' },
              },
            },
          },
        }
      );

      dispersionChart = new Chart(
        document.getElementById('standardDeviationVarianceChart').getContext('2d'),
        {
          type: 'line',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Standard Deviation',
                data: stdDev,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.3)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
              },
              {
                label: 'Variance',
                data: variance,
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.3)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
              y: {
                beginAtZero: true,
                title: { display: true, text: 'Value' },
              },
            },
          },
        }
      );

      // Update summary (using first label's data)
      if (labels.length > 0) {
        document.getElementById('summaryMean').textContent = mean[0].toFixed(2);
        document.getElementById('summaryMedian').textContent = median[0];
        document.getElementById('summaryMode').textContent = mode[0];
        document.getElementById('summaryStdDev').textContent = stdDev[0].toFixed(2);
        document.getElementById('summaryVariance').textContent = variance[0].toFixed(2);
        document.getElementById('summaryTimeLabel').textContent = new Date().toLocaleDateString();
      }
    }

    function renderPieChart(dataKey) {
      if (!cachedData || !cachedData[dataKey]) return;

      const pieCtx = document.getElementById('categoryChart').getContext('2d');
      if (pieChart) pieChart.destroy();

      const dataObj = cachedData[dataKey];
      const labels = Object.keys(dataObj);
      const data = Object.values(dataObj);

      const chartType = dataKey === 'total_applications_by_type' ? 'doughnut' : 'pie';
      const colors = ['#007bff', '#ffc107', '#28a745', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14'];

      pieChart = new Chart(pieCtx, {
        type: chartType,
        data: {
          labels: labels,
          datasets: [{
            data: data,
            backgroundColor: colors.slice(0, labels.length),
            borderRadius: 10,
            borderWidth: 1,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => {
                  const val = ctx.raw;
                  const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                  const pct = ((val / total) * 100).toFixed(1);
                  return `${ctx.label}: ${val} (${pct}%)`;
                }
              }
            }
          }
        }
      });

      // Update legend text
      const legend = document.getElementById('customLegend');
      legend.innerHTML = '';
      const total = data.reduce((a, b) => a + b, 0);
      labels.forEach((label, i) => {
        const val = data[i];
        const pct = ((val / total) * 100).toFixed(1);
        const li = document.createElement('li');
        li.innerHTML = `
        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background-color:${colors[i]};margin-right:6px;"></span>
        ${label}: <strong>${val}</strong> (${pct}%)
      `;
        legend.appendChild(li);
      });

      document.getElementById('pieSummary').textContent = `📝 Summary of ${dataKey.replace(/_/g, ' ')}`;
    }

    function clearChartsAndSummary() {
      if (meanMedianModeChart) meanMedianModeChart.destroy();
      if (dispersionChart) dispersionChart.destroy();
      if (pieChart) pieChart.destroy();

      document.getElementById('summaryMean').textContent = '—';
      document.getElementById('summaryMedian').textContent = '—';
      document.getElementById('summaryMode').textContent = '—';
      document.getElementById('summaryStdDev').textContent = '—';
      document.getElementById('summaryVariance').textContent = '—';
      document.getElementById('summaryTimeLabel').textContent = '—';

      const legend = document.getElementById('customLegend');
      if (legend) legend.innerHTML = '';
      document.getElementById('pieSummary').textContent = '';
    }

    // Event listeners for dropdowns
    document.getElementById('statDropdown').addEventListener('change', fetchStats);
    document.getElementById('typeDropdown').addEventListener('change', fetchStats);

    // When pie chart selector changes, re-render pie chart accordingly
    document.getElementById('pieChartTypeSelector').addEventListener('change', function () {
      renderPieChart(this.value);
    });

    // Initial fetch
    fetchStats();

    document.addEventListener('DOMContentLoaded', function () {
      fetch("{{ route('admin.statistics.deficiencies') }}")
        .then(res => res.json())
        .then(data => {
          const deficiencyCtx = document.getElementById('deficiencyChart').getContext('2d');

          new Chart(deficiencyCtx, {
            type: 'bar',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Deficiency Count',
                data: data.counts,
                backgroundColor: '#007bff',
                borderRadius: 6,
                barThickness: 18
              }]
            },
            options: {
              indexAxis: 'y',
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: {
                  callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.raw} cases`
                  }
                }
              },
              scales: {
                x: {
                  beginAtZero: true,
                  title: { display: true, text: 'Number of Cases' }
                },
                y: {
                  ticks: { autoSkip: false, maxRotation: 0, minRotation: 0 }
                }
              }
            }
          });

          // Set the summary text dynamically
          document.querySelector('#deficiencySummary').innerHTML = data.summary;
        });
    });
  </script>

</body>

</html>