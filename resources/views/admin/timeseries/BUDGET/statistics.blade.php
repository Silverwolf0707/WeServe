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
        <option value="case_type">Case Type</option>
        <option value="case_category">Case Category</option>
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

   <!-- Budget Summary Report -->
<div class="col-md-4">
  <div class="card border border-success shadow-sm rounded-4 bg-white h-100">
    <div class="card-header bg-success text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-coins me-2 fs-5"></i>
      <h6 class="mb-0 fw-semibold">Summary Report</h6>
    </div>
    <div class="card-body py-3 px-4 d-flex flex-column gap-2">

      <div class="d-flex align-items-start text-dark">
        <i class="fas fa-calculator text-success me-3 mt-1"></i>
        <span><strong>Mean:</strong> <span class="text-muted" id="budgetSummaryMean">—</span></span>
      </div>

      <div class="d-flex align-items-start text-dark">
        <i class="fas fa-arrows-alt-v text-success me-3 mt-1"></i>
        <span><strong>Median:</strong> <span class="text-muted" id="budgetSummaryMedian">—</span></span>
      </div>

      <div class="d-flex align-items-start text-dark">
        <i class="fas fa-chart-bar text-success me-3 mt-1"></i>
        <span><strong>Mode:</strong> <span class="text-muted" id="budgetSummaryMode">—</span></span>
      </div>

      <div class="d-flex align-items-start text-dark">
        <i class="fas fa-wave-square text-success me-3 mt-1"></i>
        <span><strong>Std Dev:</strong> <span class="text-muted" id="budgetSummaryStdDev">—</span></span>
      </div>

      <div class="d-flex align-items-start text-dark">
        <i class="fas fa-braille text-success me-3 mt-1"></i>
        <span><strong>Variance:</strong> <span class="text-muted" id="budgetSummaryVariance">—</span></span>
      </div>

      <div class="d-flex align-items-start text-muted mt-2 small">
        <i class="fas fa-calendar-alt me-2 mt-1"></i>
        <span>Date Selected: <span id="budgetSummaryTimeLabel">—</span></span>
      </div>

      <p class="text-secondary small mt-3 mb-0">
        This summary provides insights into the mean, median, mode, statistical and variance of the selected budget data.
      </p>
    </div>
  </div>
</div>



      <!-- Chart Panel -->
      <div class="col-md-4 text-center">
        <div class="chart-card h-100">
          <div class="mb-2">
            <select id="chartTypeSelector" class="form-select form-select-sm w-75 mx-auto">
              <option value="applicants">Applicants by Category</option>
              <option value="acceptance">Accepted vs Rejected</option>
            </select>
          </div>
          <div style="position:relative; width:100%; height:300px; max-width:260px; margin: 0 auto;">
            <canvas id="categoryChart"></canvas>
          </div>
          <ul id="customLegend" class="list-unstyled mt-3 small d-flex flex-wrap justify-content-center gap-2"></ul>
          <p id="pieSummary" class="mt-2 small text-muted text-center"></p>
        </div>
      </div>

  <!-- Per Beneficiary Budget Average  -->
<div class="col-md-4">
  <div class="card shadow-sm summary-equal-height h-100 border border-success" style="background: #e9fbe7; border-radius: 1rem;">

    <!-- Header -->
    <div class="card-header d-flex align-items-center text-white border-0" style="background-color: #28a745; border-radius: 1rem 1rem 0 0;">
      <h6 class="mb-0 w-100 text-center">
        <i class="fas fa-wallet me-1"></i> Per Beneficiary Budget Average
      </h6>
    </div>

    <!-- Body -->
    <div class="card-body d-flex flex-column justify-content-between">

      <!-- Chart Section -->
      <div class="text-center mb-4">
        <div style="height: 160px;">
          <canvas id="perBeneficiaryChart"></canvas>
        </div>
        <p class="small text-muted mt-2 mb-0">Average Budget Allocation</p>
      </div>

      <!-- Value Summary Section -->
      <div class="px-1">
        <div class="mb-3">
          <p class="fw-bold text-success fs-4 mb-1" id="avgBudgetPerBeneficiary">₱—</p>
          <p class="text-muted small mb-0" id="avgBudgetInfo">Across — accepted applicants</p>
        </div>

        <div class="row gx-2 gy-2 small text-muted">
          <div class="col-6">
            <p class="mb-1"><strong>Highest Budget</strong></p>
            <p class="text-dark mb-0" id="maxBudget">₱—</p>
          </div>
          <div class="col-6">
            <p class="mb-1"><strong>Lowest Budget</strong></p>
            <p class="text-dark mb-0" id="minBudget">₱—</p>
          </div>
          <div class="col-12 mt-2">
            <p class="mb-1"><strong>Total Beneficiaries</strong></p>
            <p class="text-dark mb-0" id="totalBeneficiaries">—</p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ===== PIE / DOUGHNUT CHART SECTION =====
  const pieCtx = document.getElementById('categoryChart').getContext('2d');
  const customLegendContainer = document.getElementById('customLegend');
  let pieChart;

  function renderChart(type) {
    const configs = {
      applicants: {
        chartType: 'pie',
        labels: ['Student', 'PWD', 'Solo Parent', 'Senior Citizen'],
        data: [120, 90, 60, 30],
        text: 'Most applicants are students.'
      },
      acceptance: {
        chartType: 'doughnut',
        labels: ['Accepted', 'Rejected'],
        data: [80, 20],
        text: '80% of applications were accepted.'
      }
    };

    const cfg = configs[type];
    const colors = ['#007bff', '#ffc107', '#28a745', '#dc3545', '#17a2b8'];

    if (pieChart) pieChart.destroy();

    pieChart = new Chart(pieCtx, {
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
    customLegendContainer.innerHTML = '';
    const total = cfg.data.reduce((a, b) => a + b, 0);
    cfg.labels.forEach((label, i) => {
      const value = cfg.data[i];
      const percent = ((value / total) * 100).toFixed(1);
      const li = document.createElement('li');
      li.innerHTML = `
        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background-color:${colors[i]};margin-right:6px;"></span>
        ${label}: <strong>${value}</strong> (${percent}%)
      `;
      customLegendContainer.appendChild(li);
    });

    document.getElementById('pieSummary').textContent = `📝 ${cfg.text}`;
  }

  document.getElementById('chartTypeSelector').addEventListener('change', function () {
    renderChart(this.value);
  });

  renderChart(document.getElementById('chartTypeSelector').value);


  // ===== STATISTICAL ANALYSIS SECTION =====
  const caseCategoryLabels = ['Burial', 'Medical', 'Educational', 'Transportation'];
  const caseTypeLabels = ['Student', 'PWD', 'Solo Parent', 'Senior Citizen'];

  const datasets = {
    case_category: {
      labels: caseCategoryLabels,
      data: caseCategoryLabels.map(() =>
        Array.from({ length: 12 }, () => Math.floor(Math.random() * 10000 + 1000))
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
    standardDeviationVariance: null, 
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
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Amount'
            }
          }
        }
      }
    });
  }

  function renderDispersionChart(ctx, dataPoints, labels, labelName, color) {
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: labelName,
          data: dataPoints,
          borderColor: color,
          backgroundColor: color + '33',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Amount'
            }
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

    // Render Central Tendency Chart
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

    // Render Dispersion Chart
    if (charts.standardDeviationVariance) charts.standardDeviationVariance.destroy();
    charts.standardDeviationVariance = renderDispersionChart(
      document.getElementById('standardDeviationVarianceChart').getContext('2d'),
      stats[dispersionMetric],
      data.labels,
      dispersionMetric === 'stdDev' ? 'Standard Deviation' : 'Variance',
      color
    );

    // Update summary
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
});
</script>