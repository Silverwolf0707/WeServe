<!-- Statistical Analysis Card -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">📊 Statistical Analysis</h5>
            <small class="text-white">Explore applicant age stats and category breakdowns</small>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-4">
            <!-- Left Column: Bar Chart -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div>
                        <label for="ageStatsYear" class="form-label mb-0 fw-semibold">Select Year</label>
                        <select id="ageStatsYear" class="form-select form-select-sm d-inline-block w-auto">
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                        <span id="ageStatsLoading" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </div>
                </div>
                <canvas id="ageStatsChart" height="200"></canvas>
                <ul id="ageStats" class="list-group mt-3 shadow-sm border rounded"></ul>
            </div>

            <!-- Right Column: Pie / Doughnut Chart -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <div>
                        <label class="form-label mb-0 fw-semibold">Select Date Range</label>
                        <input type="text" id="pieDateRange" class="form-control form-control-sm d-inline-block w-auto" />
                    </div>
                    <div>
                        <label class="form-label mb-0 fw-semibold">Report Type</label>
                        <select id="categoryBreakdown" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="tally" selected>Tally of Patients</option>
                            <option value="acceptance">Accept vs Reject</option>
                        </select>
                    </div>
                </div>
                <canvas id="categoryPieChart" height="200"></canvas>
                <p id="pieSummary" class="mt-3 text-muted small">📝 Tally shows most patients are students.</p>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {


    // Pie/Doughnut Chart Setup
    const pieCtx = document.getElementById('categoryPieChart').getContext('2d');
    let pieChart;

    function renderChart(type) {
        const configs = {
            tally: {
                chartType: 'pie',
                labels: ['Students', 'PWD', 'Senior Citizens', 'Solo Parents'],
                data: [45, 20, 15, 20],
                text: 'Most applicants are students.'
            },
            acceptance: {
                chartType: 'doughnut',
                labels: ['Accepted', 'Rejected'],
                data: [70, 30],
                text: '70% were accepted, 30% rejected.'
            }
        };

        const cfg = configs[type];
        if (pieChart) pieChart.destroy();

        pieChart = new Chart(pieCtx, {
            type: cfg.chartType,
            data: {
                labels: cfg.labels,
                datasets: [{
                    data: cfg.data,
                    backgroundColor: ['#36a2eb', '#ff6384', '#4bc0c0', '#9966ff']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true }
                }
            }
        });

        document.getElementById('pieSummary').textContent = `📝 ${cfg.text}`;
    }

    $('#categoryBreakdown').on('change', function () {
        renderChart(this.value);
    });
    renderChart($('#categoryBreakdown').val());
});
</script>
