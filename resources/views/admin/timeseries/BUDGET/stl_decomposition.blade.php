<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Budget Statistical Dashboard</title>
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
    <!-- KPI cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card shadow-sm bg-light-blue border-blue-500">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Top Allocated Category</div>
              <div class="fw-semibold fs-5" id="topBudgetCategory">Loading...</div>
            </div>
            <div class="icon-circle bg-blue text-white">
              <i class="fas fa-wallet fa-lg"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm bg-light-green border-green-500">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Top Allocated Type</div>
              <div class="fw-semibold fs-5" id="highestAllocation">Loading...</div>
            </div>
            <div class="icon-circle bg-green text-white">
              <i class="fas fa-chart-pie fa-lg"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm bg-light-yellow border-yellow-500">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Total Budget Disbursed</div>
              <div class="fw-semibold fs-5" id="totalBudget">Loading...</div>
            </div>
            <div class="icon-circle bg-yellow text-white">
              <i class="fas fa-coins fa-lg"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm bg-light-red border-red-500">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Monthly Average Allocation</div>
              <div class="fw-semibold fs-5" id="unusedFunds">Loading...</div>
            </div>
            <div class="icon-circle bg-red text-white">
              <i class="fas fa-exclamation-circle fa-lg"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="row align-items-stretch">
      <!-- Chart -->
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4 h-100">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap align-items-center gap-2">
              <h6 class="mb-0 fw-bold me-3">📊 Budget Time Series Component</h6>

              <select id="chartSelector" class="form-select form-select-sm me-2" style="width:auto;">
                <option value="observed">Observed</option>
                <option value="seasonal">Seasonal</option>
                <option value="trend">Trend</option>
                <option value="residual">Residual</option>
              </select>

              <select id="yearSelector" class="form-select form-select-sm me-2" style="width:auto;"></select>
              <select id="caseTypeSelector" class="form-select form-select-sm me-2" style="width:auto;"></select>
            </div>
          </div>
          <div class="card-body">
            <canvas id="combinedChart" height="300" class="w-100"></canvas>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="col-lg-4">
        <div class="card shadow-sm summary-equal-height h-100 border border-primary"
          style="background: #e0eafc; border-radius: 1rem;">
          <div class="card-header d-flex align-items-center text-white border-0"
            style="background-color:#004080; border-radius:1rem 1rem 0 0; padding:1rem;">
            <i class="fas fa-coins me-2 fs-5"></i>
            <h6 class="mb-0 fw-semibold">Budget STL Insights</h6>
          </div>
          <div class="card-body" style="padding:1.2rem;">
            <div id="summary-content" class="text-dark fs-6"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
async function loadStlData(category = 'ALL', component = 'observed', year = 'ALL') {
  const res = await fetch('/admin/timeseries/get-stl-json?type=budget');
  const json = await res.json();

  // Populate case type selector
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

  // Populate year selector
  const yearSelector = document.getElementById('yearSelector');
  if (yearSelector.options.length === 0) {
    yearSelector.innerHTML = '';
    const allYearOpt = document.createElement('option');
    allYearOpt.value = 'ALL';
    allYearOpt.textContent = 'ALL';
    yearSelector.appendChild(allYearOpt);

    // Collect unique years
    let allDates = [];
    if (category === 'ALL') {
      const cats = Object.keys(json);
      allDates = json[cats[0]].dates; // assume all categories share same dates
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

  // Process data for chart
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

  renderChart(labels, dataForYear, component);
  updateSummaryText(component, category, year, dataForYear, labels); // pass actual dates
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
        y: {
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString();
            }
          },
          min: Math.min(...data) * 0.95,
          max: Math.max(...data) * 1.05
        }
      }
    }
  });
}

function updateSummaryText(component, category, year, data, labels) {
  const total = data.reduce((a, b) => a + b, 0);
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
        <h6 class="fw-bold mb-2">📊 Observed Budget Summary (${year})</h6>
        <p>For <strong>${category}</strong>, the total observed disbursement in <strong>${year}</strong> 
        reached <strong>₱${total.toLocaleString()}</strong>.</p>
        <p>On average, about <strong>₱${monthlyAvg.toLocaleString()}</strong> was released monthly.</p>
        <p>The peak allocation occurred in <strong>${highMonth}</strong> with around 
        <strong>₱${highestVal.toLocaleString()}</strong>, while the lowest was in 
        <strong>${lowMonth}</strong> (~₱${lowestVal.toLocaleString()}).</p>
        <p><strong>Conclusion:</strong> This view reflects the actual budget flow without adjustments — 
        a clear snapshot of how much funding was disbursed month by month.</p>
      `;
      break;

    case 'trend':
      const trendMax = Math.max(...data);
      const trendMin = Math.min(...data);
      const trendMaxMonth = labels[data.indexOf(trendMax)];
      const trendMinMonth = labels[data.indexOf(trendMin)];

      let trendConclusion = 'The funding level appears relatively stable across the year.';
      if (diffPercent >= 10) trendConclusion = 'There is a strong upward funding trend, showing steadily higher allocations.';
      else if (diffPercent > 3) trendConclusion = 'The budget shows a moderate upward trend, reflecting gradual growth in support.';
      else if (diffPercent <= -10) trendConclusion = 'There is a clear downward trend, signaling reduced allocations.';
      else if (diffPercent < -3) trendConclusion = 'A slight downward trend is observed, indicating mild funding cuts.';

      summaryHTML = `
        <h6 class="fw-bold mb-2">💰 Budget Trend Insights (${year})</h6>
        <p>The trend line smooths short-term changes to highlight long-term allocation direction.</p>
        <p>Funding started at <strong>₱${startVal.toFixed(2)}</strong> and ended at 
        <strong>₱${endVal.toFixed(2)}</strong>, a net change of 
        <strong>₱${diff.toFixed(2)}</strong> (${diffPercent.toFixed(1)}%).</p>
        <p>The highest trend level was in <strong>${trendMaxMonth}</strong> (~₱${trendMax.toFixed(2)}), 
        while the lowest was in <strong>${trendMinMonth}</strong> (~₱${trendMin.toFixed(2)}).</p>
        <p><strong>Conclusion:</strong> ${trendConclusion}</p>
        <p>This helps decision makers assess the sustainability of funding levels over time.</p>
      `;
      break;

    case 'seasonal':
      const maxVal = Math.max(...data);
      const minVal = Math.min(...data);
      const peakMonths = labels.filter((m, i) => data[i] === maxVal).join(', ');
      const lowMonths = labels.filter((m, i) => data[i] === minVal).join(', ');
      const peakDiff = maxVal - minVal;
      const avgMonthly = total / data.length;
      const strongSeasonality = peakDiff > (0.25 * avgMonthly);

      const seasonalConclusion = strongSeasonality
        ? `A clear seasonal pattern exists, with peak allocations in <strong>${peakMonths}</strong> and lower allocations in <strong>${lowMonths}</strong>.`
        : `Seasonal effects are mild, with only small shifts between months.`;

      summaryHTML = `
        <h6 class="fw-bold mb-2">🌊 Seasonal Budget Summary (${year})</h6>
        <p>Seasonal components highlight recurring patterns in allocations.</p>
        <p>The highest disbursement was around <strong>₱${maxVal.toLocaleString()}</strong> in 
        <strong>${peakMonths}</strong>, while the lowest was around in <strong>${lowMonths}</strong>.</p>
        <p>The gap between high and low months is about <strong>₱${peakDiff.toLocaleString()}</strong>.</p>
        <p><strong>Conclusion:</strong> ${seasonalConclusion}</p>
        <p>This insight helps prepare for periods of increased or decreased funding demand.</p>
      `;
      break;

    case 'residual':
      const residualAbs = data.map(v => Math.abs(v));
      const residualAvg = residualAbs.reduce((a, b) => a + b, 0) / residualAbs.length;
      const residualMax = Math.max(...residualAbs);
      const residualMin = Math.min(...residualAbs);
      const residualMaxMonth = labels[residualAbs.indexOf(residualMax)];
      const residualMinMonth = labels[residualAbs.indexOf(residualMin)];

      let residualConclusion = 'Residuals are small and evenly distributed — most variations are well explained by trend and seasonality.';
      if (residualMax > residualAvg * 2) {
        residualConclusion = `A significant irregularity occurred in <strong>${residualMaxMonth}</strong>, suggesting unexpected spending behavior.`;
      } else if (residualAvg > 2) {
        residualConclusion = 'Residuals show moderate unpredictability, indicating budget anomalies not captured by trend or seasonality.';
      }

      summaryHTML = `
        <h6 class="fw-bold mb-2">🔍 Residual Budget Summary (${year})</h6>
        <p>The residual component captures irregular, unpredictable variations after removing trend and seasonal patterns.</p>
        <p>On average, residual variations were about <strong>₱${residualAvg.toFixed(2)}</strong>.</p>
        <p>The largest irregularity was in <strong>${residualMaxMonth}</strong> (~₱${residualMax.toFixed(2)}), 
        while the smallest was in <strong>${residualMinMonth}</strong> (~₱${residualMin.toFixed(2)}).</p>
        <p><strong>Conclusion:</strong> ${residualConclusion}</p>
        <p>Monitoring residuals helps identify shocks or anomalies in budget disbursement.</p>
      `;
      break;
  }

  document.getElementById('summary-content').innerHTML = summaryHTML;
}


// Event listeners
document.getElementById('chartSelector').addEventListener('change', () =>
  loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value)
);
document.getElementById('yearSelector').addEventListener('change', () =>
  loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value)
);
document.getElementById('caseTypeSelector').addEventListener('change', () =>
  loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value)
);

// Fetch Budget Dashboard Summary
async function loadBudgetSummary() {
  try {
    const res = await fetch('/admin/statistics/get-statistics?type=budget');
    const json = await res.json();

    const summary = (json.overall && json.overall.dashboard_summary) || {};

    document.getElementById('topBudgetCategory').textContent = summary.highest_allocation_category || 'N/A';
    document.getElementById('highestAllocation').textContent = summary.highest_allocation_type || 'N/A';
    document.getElementById('totalBudget').textContent = summary.total_budget_disbursed
      ? `₱${Number(summary.total_budget_disbursed).toLocaleString()}`
      : '₱0';
    document.getElementById('unusedFunds').textContent = summary.monthly_average_budget_allocation
      ? `₱${Number(summary.monthly_average_budget_allocation).toLocaleString()}`
      : '₱0';

  } catch (err) {
    console.error('Error fetching budget summary:', err);
    document.getElementById('topBudgetCategory').textContent = 'N/A';
    document.getElementById('highestAllocation').textContent = 'N/A';
    document.getElementById('totalBudget').textContent = '₱0';
    document.getElementById('unusedFunds').textContent = '₱0';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  fetch("{{ route('admin.statistics.deficiencies') }}")
    .then(res => res.json())
    .then(data => {
      const deficiencyCtx = document.getElementById('deficiencyChart').getContext('2d');

      new Chart(deficiencyCtx, {
        type: 'bar',
        data: { labels: data.labels, datasets: [{ label: 'Deficiency Count', data: data.counts, backgroundColor: '#007bff', borderRadius: 6, barThickness: 18 }] },
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw} cases` } } }, scales: { x: { beginAtZero: true, title: { display: true, text: 'Number of Cases' } }, y: { ticks: { autoSkip: false, maxRotation: 0, minRotation: 0 } } } }
      });

      document.querySelector('#deficiencySummary').innerHTML = data.summary;
    });
});

// Call immediately on page load
loadBudgetSummary();
loadStlData();
</script>

</body>

</html>