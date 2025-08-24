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
    async function loadStlData(category = null, component = 'observed', year = null) {
      const res = await fetch('/admin/timeseries/get-stl-json?type=budget');
      const json = await res.json();

      // Populate case type
      const caseSelector = document.getElementById('caseTypeSelector');
      if (caseSelector.options.length <= 1) {
        caseSelector.innerHTML = '';
        Object.keys(json).forEach(cat => {
          const opt = document.createElement('option');
          opt.value = cat;
          opt.textContent = cat;
          caseSelector.appendChild(opt);
        });
        const allOpt = document.createElement('option');
        allOpt.value = 'ALL';
        allOpt.textContent = 'ALL';
        caseSelector.appendChild(allOpt);
      }
      if (!category) category = caseSelector.value || Object.keys(json)[0];

      // Populate years dynamically from JSON
      const yearSelector = document.getElementById('yearSelector');
      if (yearSelector.options.length <= 1) {
        yearSelector.innerHTML = '';

        let allDates = [];
        if (category === 'ALL') {
          const cats = Object.keys(json);
          allDates = json[cats[0]].dates; // assume all categories share same dates
        } else {
          allDates = json[category].dates;
        }

        // Extract unique years
        const years = [...new Set(allDates.map(d => d.split('-')[0]))].sort((a, b) => b - a);

        years.forEach(y => {
          const opt = document.createElement('option');
          opt.value = y;
          opt.textContent = y;
          yearSelector.appendChild(opt);
        });
      }

      if (!year) year = yearSelector.value || new Date().getFullYear().toString();


      // Process data
      let labels = [];
      let dataForYear = [];
      if (category === 'ALL') {
        const cats = Object.keys(json);
        const dates = json[cats[0]].dates;
        const yearIdx = dates.map((d, i) => d.startsWith(year + '-') ? i : -1).filter(i => i >= 0);
        dataForYear = yearIdx.map(i =>
          cats.reduce((sum, c) => sum + (json[c][component][i] || 0), 0)
        );
        labels = yearIdx.map(i => {
          const month = dates[i].split('-')[1];
          return new Date(2000, parseInt(month) - 1).toLocaleString('default', { month: 'short' });
        });
      } else {
        const ds = json[category];
        const yearIdx = ds.dates.map((d, i) => d.startsWith(year + '-') ? i : -1).filter(i => i >= 0);
        dataForYear = yearIdx.map(i => ds[component][i]);
        labels = yearIdx.map(i => {
          const month = ds.dates[i].split('-')[1];
          return new Date(2000, parseInt(month) - 1).toLocaleString('default', { month: 'short' });
        });
      }

      renderChart(labels, dataForYear, component);
      updateSummaryText(component, category, year, dataForYear);
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
      window.chartInstance = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label: component, data, borderColor: map[component].color, backgroundColor: map[component].fill, fill: true, tension: 0.4, pointRadius: 4 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { title: { display: true, text: 'Month' } }, y: { beginAtZero: true } } }
      });
    }

    function updateSummaryText(component, category, year, data) {
      const total = data.reduce((a, b) => a + b, 0);
      const start = data[0] || 0, end = data[data.length - 1] || 0;
      const diff = end - start;
      const diffPct = start ? (diff / start) * 100 : 0;

      // Month labels
      const labels = data.map((_, i) => new Date(year, i, 1).toLocaleString('default', { month: 'short' }));

      let html = '';

      if (component === 'observed') {
        const maxVal = Math.max(...data);
        const minVal = Math.min(...data.filter(v => v > 0) || [0]);

        // Get all months with max/min values
        const maxMonths = labels.filter((m, i) => data[i] === maxVal).join(', ');
        const minMonths = labels.filter((m, i) => data[i] === minVal).join(', ');

        html = `
            <h6>📊 Observed Budget (${year})</h6>
            <p>
                Total disbursement for <b>${category}</b> over the year is <b>₱${total.toLocaleString()}</b>.<br>
                The month(s) with the highest spending reached <b>₱${maxVal.toLocaleString()}</b> in <b>${maxMonths}</b>.<br>
                The month(s) with the lowest spending was <b>₱${minVal.toLocaleString()}</b> in <b>${minMonths}</b>.<br>
                This gives an overview of how funds were actually spent across the months.
            </p>
        `;
      }
      else if (component === 'trend') {
        const startObserved = data[0] || 0;
        const endObserved = data[data.length - 1] || 0;
        const diffObserved = endObserved - startObserved;
        const diffPctObserved = startObserved ? (diffObserved / startObserved) * 100 : 0;

        let trendText = 'stable';
        let explanation = 'remained stable';

        if (diffPctObserved >= 5) {
          trendText = 'increasing steadily';
          explanation = 'rose significantly';
        } else if (diffPctObserved > 1) {
          trendText = 'slightly increasing';
          explanation = 'rose slightly';
        } else if (diffPctObserved <= -5) {
          trendText = 'decreasing steadily';
          explanation = 'fell significantly';
        } else if (diffPctObserved < -1) {
          trendText = 'slightly decreasing';
          explanation = 'fell slightly';
        }

        html = `
    <h6>📈 Spending Trend Summary (${year})</h6>
    <p>
      <b>Trend Baseline:</b> From start, the trend budget disbursed calculation was <b>₱${startObserved.toLocaleString()}</b>.<br>
      By the end, it reached <b>₱${endObserved.toLocaleString()}</b>.<br>
      Overall, the budget disbursed trend is <b>${trendText}</b>, meaning that allocation generally ${explanation} over the months.
    </p>
  `;
      }


      else if (component === 'seasonal') {
        const maxVal = Math.max(...data);
        const minVal = Math.min(...data);
        const maxMonths = labels.filter((m, i) => data[i] === maxVal).join(', ');
        const minMonths = labels.filter((m, i) => data[i] === minVal).join(', ');

        html = `
            <h6>🌊 Seasonal Insights (${year})</h6>
            <p>
                Certain months show higher or lower spending due to predictable seasonal effects.<br>
                The highest seasonal peak was in <b>${maxMonths}</b></b>.<br>
                The lowest seasonal peak was in <b>${minMonths}</b></b>.<br>
                These variations highlight months when spending naturally increases or decreases.
            </p>
        `;
      }
      else if (component === 'residual') {
        const avg = total / data.length;

        html = `
            <h6>🔍 Residual Analysis (${year})</h6>
            <p>
                The residual shows the part of the monthly spending not explained by trend or seasonality.<br>
                On average, these unexplained variations are <b>₱${avg.toLocaleString()}</b> per month.<br>
                Large residuals indicate months with unusually high or low spending compared to expected patterns.
            </p>
        `;
      }

      document.getElementById('summary-content').innerHTML = html;
    }



    // Event listeners
    document.getElementById('chartSelector').addEventListener('change', () =>
      loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value));
    document.getElementById('yearSelector').addEventListener('change', () =>
      loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value));
    document.getElementById('caseTypeSelector').addEventListener('change', () =>
      loadStlData(document.getElementById('caseTypeSelector').value, document.getElementById('chartSelector').value, document.getElementById('yearSelector').value));

    // Fetch Budget Dashboard Summary
    async function loadBudgetSummary() {
      try {
        const res = await fetch('/admin/statistics/get-statistics?type=budget');
        const json = await res.json();

        // Using your actual JSON keys
        const summary = json.dashboard_summary || {};

        document.getElementById('topBudgetCategory').textContent = summary.highest_allocation_category || 'N/A';
        document.getElementById('highestAllocation').textContent = summary.highest_allocation_type || 'N/A';
        document.getElementById('totalBudget').textContent = summary.total_budget_disbursed
          ? `₱${summary.total_budget_disbursed.toLocaleString()}` : '₱0';
        document.getElementById('unusedFunds').textContent = summary.monthly_average_budget_allocation
          ? `₱${summary.monthly_average_budget_allocation.toLocaleString()}` : '₱0';

      } catch (err) {
        console.error('Error fetching budget summary:', err);
      }
    } document.addEventListener('DOMContentLoaded', function () {
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