@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-info-circle me-2"></i> Application Details
            </h4>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Case Type</dt>
                <dd class="col-sm-9">{{ $application->case_type }}</dd>

                <dt class="col-sm-3">Claimant Name</dt>
                <dd class="col-sm-9">{{ $application->claimant_name }}</dd>

                <dt class="col-sm-3">Applicant Name</dt>
                <dd class="col-sm-9">{{ $application->applicant_name }}</dd>

                <dt class="col-sm-3">Diagnosis</dt>
                <dd class="col-sm-9">{{ $application->diagnosis }}</dd>

                <dt class="col-sm-3">Age</dt>
                <dd class="col-sm-9">{{ $application->age }}</dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">{{ $application->address }}</dd>

                <dt class="col-sm-3">Contact</dt>
                <dd class="col-sm-9">{{ $application->contact_number }}</dd>

                <dt class="col-sm-3">Tracking #</dt>
                <dd class="col-sm-9">{{ $application->tracking_number }}</dd>
            </dl>
            <div class="mt-4">
                <form id="confirmTransferForm" action="{{ route('admin.applications.confirm', $application->id) }}" method="POST"
                    class="d-flex align-items-center gap-2">
                    @csrf
                    <button type="submit" id="confirmTransferBtn"
                        class="btn btn-success px-4 py-2 rounded-lg fw-semibold transition">
                        <i class="fas fa-check-circle me-1"></i> Confirm & Transfer
                    </button>
                    <a href="{{ route('admin.online-applications.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-lg fw-semibold" id="backBtn">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmTransferForm = document.getElementById('confirmTransferForm');
        const confirmTransferBtn = document.getElementById('confirmTransferBtn');
        const backBtn = document.getElementById('backBtn');
        
        if (confirmTransferForm && confirmTransferBtn) {
            confirmTransferForm.addEventListener('submit', function(e) {
                // Prevent double submission
                if (confirmTransferBtn.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }
                
                // Disable both buttons immediately
                confirmTransferBtn.disabled = true;
                confirmTransferBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                if (backBtn) {
                    backBtn.classList.add('disabled');
                    backBtn.style.pointerEvents = 'none';
                    backBtn.style.opacity = '0.6';
                }
                
                // Show processing message
                showAlert('Transferring application to patient records...', 'info');
            });
        }
        
        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert.position-fixed');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Add to body
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
        
        // Prevent form submission on page refresh or back button
        let formSubmitted = false;
        
        if (confirmTransferForm) {
            confirmTransferForm.addEventListener('submit', function() {
                formSubmitted = true;
                setTimeout(function() {
                    formSubmitted = false;
                }, 3000); // Reset after 3 seconds
            });
        }
        
        window.addEventListener('beforeunload', function(e) {
            if (confirmTransferBtn && confirmTransferBtn.disabled && !formSubmitted) {
                // Show warning if form is submitting and user tries to leave
                e.preventDefault();
                e.returnValue = 'Your application transfer is in progress. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    });
</script>
@endsection

<style>
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    
    .transition {
        transition: all 0.3s ease;
    }
    
    .disabled {
        cursor: not-allowed !important;
        pointer-events: none !important;
        opacity: 0.6 !important;
    }
    
    /* Loading animation for button */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
</style>