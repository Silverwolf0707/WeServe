<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Statistical Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e0ff1f4e.js" crossorigin="anonymous"></script>
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
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
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
    .bg-light-blue    { background-color: #e8f1ff; }
    .bg-light-green   { background-color: #e9f9ef; }
    .bg-light-yellow  { background-color: #fffbe7; }
    .bg-light-red     { background-color: #ffeaea; }

    .bg-blue    { background-color: #3b82f6; }
    .bg-green   { background-color: #10b981; }
    .bg-yellow  { background-color: #facc15; }
    .bg-red     { background-color: #ef4444; }

    .border-blue-500   { border-left: 6px solid #3b82f6 !important; }
    .border-green-500  { border-left: 6px solid #10b981 !important; }
    .border-yellow-500 { border-left: 6px solid #facc15 !important; }
    .border-red-500    { border-left: 6px solid #ef4444 !important; }

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

          <!-- Chart Type Dropdown -->
          <select id="chartSelector" class="form-select form-select-sm me-2" style="width: auto;">
            <option value="trend">Trend</option>
            <option value="seasonal">Seasonal</option>
            <option value="observed">Observed</option>
            <option value="residual">Residual</option>
          </select>

          <!-- Year Selector -->
          <select id="yearSelector" class="form-select form-select-sm me-2" style="width: auto;">
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
          </select>

          <!-- Date Range Picker (pure frontend) -->
          <input type="text" id="dateRangePicker" class="form-control form-control-sm" placeholder="Select date range" style="width: 140px;" />
        </div>
      </div>

      <div class="card-body">
        <canvas id="combinedChart" height="300" class="w-100"></canvas>
      </div>
    </div>
  </div>


    <!-- Summary Report -->
<div class="col-lg-4">
  <div class="card shadow-sm summary-equal-height h-100 border border-primary" style="background: #e0eafc; border-radius: 1rem;">

    <!-- Header -->
    <div class="card-header d-flex align-items-center text-white border-0" style="background-color: #004080; border-radius: 1rem 1rem 0 0; padding: 1rem;">
      <i class="fas fa-chart-line me-2 fs-5"></i>
      <h6 class="mb-0 fw-semibold">STL Decomposition Insights</h6>
    </div>

    <!-- Body -->
    <div class="card-body d-flex flex-column gap-3" style="padding: 1.2rem;">
      <!-- Trend -->
      <div id="summary-trend" class="summary-text">
        <div class="text-dark">
          <h6 class="fw-bold mb-1">📈 Trend Component</h6>
          <p><strong>Interpretation:</strong> The trend line shows a steady rise in the number of applicants or aid disbursed, particularly noticeable in the last two quarters.</p>
          <p><strong>Implication:</strong> This could be due to increased outreach, awareness campaigns, or post-pandemic recovery efforts.</p>
          <p><strong>Recommendation:</strong> Prepare for further increases by expanding staff or streamlining processes.</p>
        </div>
      </div>

      <!-- Seasonal -->
      <div id="summary-seasonal" class="summary-text d-none">
        <div class="text-dark">
          <h6 class="fw-bold mb-1">📅 Seasonal Component</h6>
          <p><strong>Interpretation:</strong> There is a recurring peak in Q2 (April–June) across multiple years, indicating seasonal demand.</p>
          <p><strong>Causes:</strong></p>
          <ul class="mb-1">
            <li>Start of school year (education aid)</li>
            <li>Mid-year budget releases</li>
            <li>Health-related spikes (e.g., dengue season)</li>
          </ul>
          <p><strong>Recommendation:</strong> Schedule extra manpower and budget buffers around Q2.</p>
        </div>
      </div>

      <!-- Observed -->
      <div id="summary-observed" class="summary-text d-none">
        <div class="text-dark">
          <h6 class="fw-bold mb-1">🟰 Observed Component</h6>
          <p><strong>Interpretation:</strong> Matches closely with the combined trend and seasonal curves, confirming predictable and explainable assistance behavior.</p>
          <p><strong>Insight:</strong> Data suggests reliability in demand forecasting.</p>
          <p><strong>Next Steps:</strong> Leverage this to automate early warnings or forecast aid logistics.</p>
        </div>
      </div>

      <!-- Residual -->
      <div id="summary-residual" class="summary-text d-none">
        <div class="text-dark">
          <h6 class="fw-bold mb-1">🌊 Residual Component</h6>
          <p><strong>Interpretation:</strong> Residuals are small and stable, which means most patterns are well explained by the trend and seasonal components.</p>
          <p><strong>Implication:</strong> The model used for decomposition is statistically sound, and random anomalies are minimal.</p>
          <p><strong>Recommendation:</strong> Continue collecting clean and complete data to preserve model accuracy.</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Chart Script -->
<script>
  const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

  const dataSeries = {
    observed: { data: [12, 15, 18, 16, 20, 23], color: '#3b82f6', fill: 'rgba(59, 130, 246, 0.2)' },
    trend: { data: [11, 13, 15, 17, 19, 21], color: '#10b981', fill: 'rgba(16, 185, 129, 0.2)' },
    seasonal: { data: [1, 2, 3, -1, 0, 2], color: '#facc15', fill: 'rgba(250, 204, 21, 0.3)' },
    residual: { data: [0, 0, 0, -1, 1, 0], color: '#ef4444', fill: 'rgba(239, 68, 68, 0.2)' }
  };

  const ctx = document.getElementById('combinedChart').getContext('2d');

  let chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Observed',
        data: dataSeries.observed.data,
        borderColor: dataSeries.observed.color,
        backgroundColor: dataSeries.observed.fill,
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
        legend: { display: false }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Time (Monthly)',
            color: '#6b7280',
            font: {
              size: 14,
              weight: 'bold'
            }
          }
        },
        y: {
  title: {
    display: true,
    text: 'Total Vouchers Processed',
    color: '#6b7280',
    font: {
      size: 14,
      weight: 'bold'
    }
  }
}

      }
    }
  });

  document.getElementById('chartSelector').addEventListener('change', function () {
    const selected = this.value;
    const selectedData = dataSeries[selected];

    chartInstance.data.datasets[0].label = selected.charAt(0).toUpperCase() + selected.slice(1);
    chartInstance.data.datasets[0].data = selectedData.data;
    chartInstance.data.datasets[0].borderColor = selectedData.color;
    chartInstance.data.datasets[0].backgroundColor = selectedData.fill;
    chartInstance.update();

    document.querySelectorAll('.summary-text').forEach(el => el.classList.add('d-none'));
    document.getElementById('summary-' + selected).classList.remove('d-none');
  });
</script>
</body>
</html>