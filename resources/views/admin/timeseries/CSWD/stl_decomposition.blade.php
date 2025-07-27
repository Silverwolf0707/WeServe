<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">📈 Time Series Analytics</h5>
            <small>Trends, seasonality, and variations in application categories</small>
        </div>
        <button id="downloadChart" class="btn btn-sm btn-light">Download Chart</button>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-6 col-lg-4">
            <label for="dateRangePicker" class="form-label fw-semibold" style="margin-left: 10px;">📅 Select Date Range</label>
                <input type="text" id="dateRangePicker" class="form-control" placeholder="Select Date Range" readonly>
            </div>
            <div class="col-md-6 col-lg-4">
            <label for="caseCategorySelector" class="form-label fw-semibold"style="margin-left: 10px;">📂 Case Category</label>
                <select id="caseCategorySelector" class="form-select"></select>
            </div>
        </div>

        <div id="loadingSpinner" class="text-center py-5" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <canvas id="timeSeriesChart" height="120" class="w-100" style="display:none;"></canvas>

        <div id="summaryReport" class="mt-4" style="display: none;">
            <div class="card border-start border-4 border-primary shadow-sm">
                <div class="card-header bg-light d-flex align-items-center">
                    <i class="bi bi-clipboard-data me-2 text-primary"></i>
                    <h6 class="mb-0">Summary Report</h6>
                </div>
                <div class="card-body px-3 py-2">
                    <ul id="summaryContent" class="list-group list-group-flush">
                        <!-- Dynamically generated insights go here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
