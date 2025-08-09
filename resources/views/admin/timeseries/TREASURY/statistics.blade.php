<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Statistical Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e0ff1f4e.js" crossorigin="anonymous"></script>
  <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
    }
    .card {
      border-radius: 12px;
    }
    .stat-card {
      height: 100%;
      border: none;
      border-radius: 12px;
      padding: 0.75rem 1rem;
    }
    .chart-card {
      background: #fff;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      height: 100%;
    }
    .chart-title {
      font-size: 0.95rem;
      font-weight: 600;
    }
    canvas {
      max-width: 100%;
      height: auto !important;
    }
    .form-select-sm, .form-control-sm {
      min-width: 110px;
    }
    .top-summary .card-body {
      padding: 0.75rem 1rem;
    }
    .card-header {
      padding: 0.5rem 1rem;
    }
    .card-body {
      padding: 1rem;
    }
    .summary-list li {
      font-size: 0.85rem;
      margin-bottom: 4px;
    }
    @media (max-width: 991px) {
      canvas {
        height: 250px !important;
      }
    }
  </style>
</head>
<body>

 <!-- Unified Statistical Dashboard Card -->
<div class="card shadow-sm border-0 rounded-4 mt-3">
  <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
    <div class="text-primary fw-semibold">
      <i class="fas fa-chart-line me-2"></i>Statistical Analysis
    </div>
    <div class="d-flex gap-2">
      <select id="statDropdown" class="form-select form-select-sm">
        <option value="case_category">Case Category</option>
        <option value="case_type">Case Type</option>
      </select>
      <input type="date" class="form-control form-control-sm" id="datePicker">
    </div>
  </div>

  <div class="card-body pt-3 pb-1">
    <div class="row g-3">
      <!-- Mean, Median, Mode Chart -->
      <div class="col-md-6">
        <div class="chart-card">
          <div class="d-flex justify-content-end align-items-center mb-2">
            <select id="centralFilter" class="form-select form-select-sm d-none"></select>
          </div>
          <canvas id="meanMedianModeChart" height="180"></canvas>
        </div>
      </div>

      <!-- Standard Deviation / Variance Chart -->
      <div class="col-md-6">
        <div class="chart-card">
          <div class="d-flex justify-content-end align-items-center mb-2">
           <select id="dispersionDropdown" class="form-select form-select-sm" style="width: 450px;">
            <option value="stdDev">Standard Deviation</option>
            <option value="variance">Variance</option>
           </select>

          </div>
          <canvas id="standardDeviationVarianceChart" height="180"></canvas>
        </div>
      </div>

      <!-- Statistical Summary -->
<div class="col-lg-4">
  <div class="card shadow-sm summary-equal-height h-100 border-0" style="background: linear-gradient(135deg, #e0eafc, #cfdef3); border-radius: 1rem;">

    <!-- Header -->
    <div class="card-header d-flex align-items-center text-white border-0" style="background-color: #004080; border-radius: 1rem 1rem 0 0; padding: 1rem;">
      <i class="fas fa-clipboard-list me-2 fs-5"></i>
      <h6 class="mb-0 fw-semibold">Statistical Summary</h6>
    </div>

    <!-- Body -->
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


      <!-- Chart Panel for Treasury Dashboard -->
<div class="col-md-4 text-center">
  <div class="chart-card h-100">
    <div class="mb-2">
      <select id="treasuryChartSelector" class="form-select form-select-sm w-75 mx-auto">
        <option value="check_status">Check Status Breakdown</option>
        <option value="approval">Approved vs Pending</option>
      </select>
    </div>
    <div style="position:relative; width:100%; height:300px; max-width:260px; margin: 0 auto;">
      <canvas id="treasuryChart"></canvas>
    </div>
    <ul id="treasuryLegend" class="list-unstyled mt-3 small d-flex flex-wrap justify-content-center gap-2"></ul>
    <p id="treasurySummary" class="mt-2 small text-muted text-center"></p>
  </div>
</div>


     <!-- Peak Activity Insights Panel (Treasury Style - Green Theme) -->
<div class="col-md-4">
  <div class="card shadow-sm summary-equal-height h-100 border border-success" style="background: #e9fbe7; border-radius: 1rem;">
    <div class="card-header d-flex align-items-center text-white border-0" style="background-color: #28a745; border-radius: 1rem 1rem 0 0;">
      <h6 class="mb-0 text-center w-100">
        <i class="fas fa-bolt me-1"></i>Peak Activity Insights
      </h6>
    </div>
    <div class="card-body d-flex flex-column justify-content-between">
      
      <!-- First Chart: Peak Hour -->
      <div class="mb-3">
        <div style="height: 140px;">
          <canvas id="peakHourChart"></canvas>
        </div>
        <div class="text-center small text-muted mt-1">Hourly Trend</div>
      </div>

      <!-- Second Chart: Category Breakdown -->
      <div class="mb-3">
        <div style="height: 140px;">
          <canvas id="peakCategoryChart"></canvas>
        </div>
        <div class="text-center small text-muted mt-1">Category Breakdown</div>
      </div>

      <!-- Summary List -->
      <ul class="list-unstyled mb-0 small">
        <li><strong>Peak Day:</strong> <span id="peakDay" class="text-muted">-</span></li>
        <li><strong>Total Requests:</strong> <span id="peakRequests" class="text-muted">-</span></li>
        <li><strong>Most Availed Category:</strong> <span id="peakCategory" class="text-muted">-</span></li>
      </ul>

    </div>
  </div>
</div>


    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ===== TREASURY PIE / DOUGHNUT CHART SECTION =====
  const treasuryCtx = document.getElementById('treasuryChart').getContext('2d');
  const treasuryLegend = document.getElementById('treasuryLegend');
  let treasuryChart;

  function renderTreasuryChart(type) {
    const treasuryConfigs = {
      check_status: {
        chartType: 'pie',
        labels: ['Received Checks', 'DV for Signature', 'Check Preparation', 'Voucher Scanned', 'Check Signed'],
        data: [45, 30, 25, 20, 35],
        text: 'Most documents are in "Received Checks" status.'
      },
      approval: {
        chartType: 'doughnut',
        labels: ['Approved', 'Pending'],
        data: [70, 30],
        text: '70% of treasury requests are approved.'
      }
    };

    const cfg = treasuryConfigs[type];
    const colors = ['#6610f2', '#20c997', '#fd7e14', '#6f42c1', '#e83e8c'];

    if (treasuryChart) treasuryChart.destroy();

    treasuryChart = new Chart(treasuryCtx, {
      type: cfg.chartType,
      data: {
        labels: cfg.labels,
        datasets: [{
          data: cfg.data,
          backgroundColor: colors.slice(0, cfg.labels.length),
          borderRadius: 10,
          borderWidth: 1,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function (context) {
                const value = context.raw;
                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                const percent = ((value / total) * 100).toFixed(1);
                return `${context.label}: ${value} (${percent}%)`;
              }
            }
          }
        }
      }
    });

    // Custom Legend
    treasuryLegend.innerHTML = '';
    const total = cfg.data.reduce((a, b) => a + b, 0);
    cfg.labels.forEach((label, i) => {
      const value = cfg.data[i];
      const percent = ((value / total) * 100).toFixed(1);
      const li = document.createElement('li');
      li.innerHTML = `
        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background-color:${colors[i]};margin-right:6px;"></span>
        ${label}: <strong>${value}</strong> (${percent}%)
      `;
      treasuryLegend.appendChild(li);
    });

    document.getElementById('treasurySummary').textContent = `📊 ${cfg.text}`;
  }

  document.getElementById('treasuryChartSelector').addEventListener('change', function () {
    renderTreasuryChart(this.value);
  });

  renderTreasuryChart(document.getElementById('treasuryChartSelector').value);

  // ===== STATISTICAL ANALYSIS SECTION =====
  const caseCategoryLabels = ['Burial', 'Medical', 'Educational', 'Emergenecy'];
  const caseTypeLabels = ['Student', 'PWD', 'Solo Parent', 'Senior Citizen'];

  const datasets = {
    case_category: {
      labels: caseCategoryLabels,
      data: caseCategoryLabels.map(() =>
        Array.from({ length: 12 }, () => Math.floor(Math.random() * 100 + 20))
      )
    },
    case_type: {
      labels: caseTypeLabels,
      data: caseTypeLabels.map(() =>
        Array.from({ length: 12 }, () => Math.floor(Math.random() * 50 + 10))
      )
    }
  };

  function calculateStatsOverTime(dataArray) {
    const mean = [], median = [], mode = [], stdDev = [], variance = [];

    dataArray.forEach(data => {
      const sorted = [...data].sort((a, b) => a - b);
      const total = sorted.length;
      const meanVal = sorted.reduce((a, b) => a + b, 0) / total;

      const medianVal = total % 2 === 0
        ? (sorted[total / 2 - 1] + sorted[total / 2]) / 2
        : sorted[Math.floor(total / 2)];

      const freq = {};
      sorted.forEach(val => freq[val] = (freq[val] || 0) + 1);
      const modeVal = +Object.keys(freq).reduce((a, b) => freq[a] > freq[b] ? a : b);

      const varianceVal = sorted.reduce((acc, val) => acc + Math.pow(val - meanVal, 2), 0) / total;
      const stdDevVal = Math.sqrt(varianceVal);

      mean.push(meanVal);
      median.push(medianVal);
      mode.push(modeVal);
      variance.push(varianceVal);
      stdDev.push(stdDevVal);
    });

    return { mean, median, mode, variance, stdDev };
  }

  let charts = {
    meanMedianMode: null,
    standardDeviationVariance: null
  };

  function renderCentralTendencyChart(ctx, stats, label) {
    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Mean', 'Median', 'Mode'],
        datasets: [{
          label: label,
          data: [stats.mean, stats.median, stats.mode],
          backgroundColor: ['#007bff', '#28a745', '#ffc107']
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            beginAtZero: true,
            max: 200,
            title: { display: true, text: 'Number of Disbursed Applications' }
          },
          y: { title: { display: true, text: '' } }
        }
      }
    });
  }

  function renderDispersionChart(ctx, dataPoints, xLabels, labelName, color) {
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: xLabels,
        datasets: [{
          label: labelName,
          data: dataPoints,
          fill: true,
          tension: 0.4,
          backgroundColor: color + '33',
          borderColor: color,
          pointBackgroundColor: color,
          pointBorderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { title: { display: true, text: 'Category / Type' } },
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Number of Disbursed Applications' }
          }
        }
      }
    });
  }

  function updateCharts(datasetKey, selectedIndex = 0) {
    const data = datasets[datasetKey];
    const stats = calculateStatsOverTime(data.data);
    const dispersionMetric = document.getElementById('dispersionDropdown').value;
    const color = dispersionMetric === 'stdDev' ? '#dc3545' : '#17a2b8';
    const filterSelect = document.getElementById('centralFilter');

    filterSelect.classList.remove('d-none');
    filterSelect.innerHTML = data.labels.map((l, i) =>
      `<option value="${i}">${l}</option>`
    ).join('');
    filterSelect.value = selectedIndex;

    const selectedLabel = data.labels[selectedIndex];

    if (charts.meanMedianMode) charts.meanMedianMode.destroy();
    charts.meanMedianMode = renderCentralTendencyChart(
      document.getElementById('meanMedianModeChart').getContext('2d'),
      {
        mean: stats.mean[selectedIndex],
        median: stats.median[selectedIndex],
        mode: stats.mode[selectedIndex]
      },
      selectedLabel
    );

    if (charts.standardDeviationVariance) charts.standardDeviationVariance.destroy();
    charts.standardDeviationVariance = renderDispersionChart(
      document.getElementById('standardDeviationVarianceChart').getContext('2d'),
      stats[dispersionMetric],
      data.labels,
      dispersionMetric === 'stdDev' ? 'Standard Deviation' : 'Variance',
      color
    );

    document.getElementById('summaryMean').textContent = stats.mean[selectedIndex].toFixed(2);
    document.getElementById('summaryMedian').textContent = stats.median[selectedIndex];
    document.getElementById('summaryMode').textContent = stats.mode[selectedIndex];
    document.getElementById('summaryStdDev').textContent = stats.stdDev[selectedIndex].toFixed(2);
    document.getElementById('summaryVariance').textContent = stats.variance[selectedIndex].toFixed(2);
    document.getElementById('summaryTimeLabel').textContent = new Date().toLocaleDateString();
  }

  document.getElementById('statDropdown').addEventListener('change', e => {
    updateCharts(e.target.value, 0);
  });

  document.getElementById('centralFilter').addEventListener('change', e => {
    updateCharts(document.getElementById('statDropdown').value, parseInt(e.target.value));
  });

  document.getElementById('dispersionDropdown').addEventListener('change', () => {
    updateCharts(document.getElementById('statDropdown').value, parseInt(document.getElementById('centralFilter').value || 0));
  });

  // Initial chart render
  updateCharts('case_category');

  // ===== PEAK ACTIVITY INSIGHTS SCRIPT =====
  (function renderPeakActivityInsight() {
    document.getElementById('peakDay').textContent = 'July 18, 2025';
    document.getElementById('peakRequests').textContent = 142;
    document.getElementById('peakCategory').textContent = 'Medical Assistance';

    new Chart(document.getElementById('peakHourChart').getContext('2d'), {
      type: 'line',
      data: {
        labels: ['8AM','9AM','10AM','11AM','12PM','1PM','2PM','3PM','4PM','5PM'],
        datasets: [{
          label: 'Requests',
          data: [5, 8, 12, 24, 30, 20, 15, 10, 8, 5],
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13,110,253,0.1)',
          tension: 0.3,
          fill: true,
          pointRadius: 2,
          borderWidth: 1.5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }},
        scales: {
          y: { display: false },
          x: { ticks: { font: { size: 10 } } }
        }
      }
    });

    new Chart(document.getElementById('peakCategoryChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: ['Medical', 'Burial', 'Education', 'Emergency'],
        datasets: [{
          label: 'Requests',
          data: [50, 35, 30, 27],
          backgroundColor: ['#0d6efd','#6c757d','#ffc107','#dc3545']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }},
        scales: {
          y: { display: false },
          x: { ticks: { font: { size: 10 } } }
        }
      }
    });
  })();
});
</script>