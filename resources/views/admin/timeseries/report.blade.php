<div id="summaryReport" class="mt-4" style="display: none;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-2 d-flex align-items-center">
            <i class="bi bi-clipboard-data me-2"></i>
            <h6 class="mb-0">Summary Report</h6>
        </div>
        <div class="card-body px-3 py-2">
            <ul id="summaryContent" class="list-group list-group-flush">
                <!-- Example dynamically inserted items -->
                <!--
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Total Applications
                    <span class="badge bg-success rounded-pill">1,250</span>
                </li>
                -->
            </ul>
        </div>
    </div>
</div>

<style>
    #summaryReport .card {
        border-left: 5px solid #0d6efd;
        transition: all 0.3s ease;
        background-color: #ffffff;
        border-radius: 0.5rem;
    }

    #summaryReport .list-group-item {
        font-size: 0.95rem;
        padding: 0.65rem 1rem;
        border-left: 4px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    #summaryReport .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0d6efd;
    }

    #summaryReport .card-header h6 {
        font-weight: 600;
    }
</style>

