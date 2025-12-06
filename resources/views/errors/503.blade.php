{{-- resources/views/errors/503.blade.php --}}
@extends('layouts.app')

@section('title', 'Under Maintenance')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 text-center">
                {{-- Animated 503 --}}
                <div class="error-number mb-4">
                    <span class="number-5 number-digit">5</span>
                    <div class="floating-orbit">
                        <div class="orbit-circle"></div>
                    </div>
                    <span class="number-0 number-digit">0</span>
                    <div class="floating-orbit">
                        <div class="orbit-circle"></div>
                    </div>
                    <span class="number-3 number-digit">3</span>
                </div>

                {{-- Animated Text --}}
                <div class="error-content">
                    <h2 class="error-title mb-3">Site Under Maintenance</h2>
                    <p class="error-message lead text-muted mb-4">
                        We're currently performing scheduled maintenance to improve your experience.
                        <br>
                        Please check back soon!
                    </p>

                    {{-- Maintenance Tools Animation --}}
                    <div class="tools-animation mb-4">
                        <div class="tools-container">
                            <div class="tool-icon" style="--delay: 0s;">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="tool-icon" style="--delay: 0.5s;">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <div class="tool-icon" style="--delay: 1s;">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="tool-icon" style="--delay: 1.5s;">
                                <i class="fas fa-server"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="error-actions">
                        <button onclick="tryReload()" class="btn btn-outline-primary btn-lg me-3">
                            <i class="fas fa-redo me-2"></i>Try Reload
                        </button>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                    </div>

                    {{-- Simple Message --}}
                    <div class="simple-message mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            We're working hard to get things back up and running
                        </small>
                    </div>

                    {{-- Floating elements for background --}}
                    <div class="floating-elements">
                        <div class="float-circle circle-1"></div>
                        <div class="float-circle circle-2"></div>
                        <div class="float-circle circle-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function tryReload() {
    const btn = event.target;
    const originalHTML = btn.innerHTML;
    
    // Show loading state
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking...';
    btn.disabled = true;
    
    // Try to fetch the homepage
    fetch('/', { 
        method: 'HEAD',
        cache: 'no-cache'
    })
    .then(response => {
        if (response.status === 200) {
            // Site is back up, reload
            location.reload();
        } else {
            // Still in maintenance
            showNotification('Maintenance still in progress. Please try again later.');
            // Reset button after 2 seconds
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }, 2000);
        }
    })
    .catch(() => {
        showNotification('Unable to check status. Please try again.');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }, 2000);
    });
}

// Auto-check for maintenance completion (every 30 seconds)
function startStatusCheck() {
    setInterval(() => {
        fetch('/', { method: 'HEAD', cache: 'no-cache' })
            .then(response => {
                if (response.status === 200) {
                    showNotification('Maintenance complete! Reloading...', 'success');
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(() => {
                // Silently fail, continue checking
            });
    }, 30000); // 30 seconds
}

// Notification function
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existing = document.querySelector('.status-notification');
    if (existing) existing.remove();
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `status-notification alert alert-${type === 'success' ? 'success' : 'warning'} 
                           alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Start checking status after page loads
document.addEventListener('DOMContentLoaded', function() {
    // Start checking after 1 minute
    setTimeout(startStatusCheck, 60000);
});
</script>

<style>
.error-number {
    position: relative;
    display: inline-flex;
    align-items: center;
    margin-bottom: 2rem;
}

.number-digit {
    font-size: 8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: float 3s ease-in-out infinite;
}

.number-5 { animation-delay: 0s; }
.number-0 { animation-delay: 0.3s; }
.number-3 { animation-delay: 0.6s; }

.floating-orbit {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 0.5rem;
}

.orbit-circle {
    position: absolute;
    width: 50px;
    height: 50px;
    border: 3px solid #f6ad55;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: orbit 6s linear infinite;
}

.orbit-circle::before {
    content: '';
    position: absolute;
    width: 15px;
    height: 15px;
    background: #ed8936;
    border-radius: 50%;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    animation: pulse 3s ease-in-out infinite;
}

.error-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    animation: slideInUp 0.8s ease-out;
}

.error-message {
    animation: slideInUp 0.8s ease-out 0.2s both;
}

.tools-animation {
    animation: slideInUp 0.8s ease-out 0.4s both;
}

.tools-container {
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.tool-icon {
    font-size: 2.5rem;
    color: #f6ad55;
    animation: toolBounce 2s ease-in-out infinite;
    animation-delay: var(--delay);
}

.error-actions {
    animation: slideInUp 0.8s ease-out 0.6s both;
}

.simple-message {
    animation: slideInUp 0.8s ease-out 0.8s both;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

.float-circle {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(246, 173, 85, 0.1) 0%, rgba(237, 137, 54, 0.1) 100%);
    animation: float 8s ease-in-out infinite;
}

.circle-1 {
    width: 120px;
    height: 120px;
    top: 15%;
    left: 15%;
    animation-delay: 0s;
}

.circle-2 {
    width: 180px;
    height: 180px;
    top: 65%;
    right: 15%;
    animation-delay: 3s;
}

.circle-3 {
    width: 90px;
    height: 90px;
    bottom: 15%;
    left: 25%;
    animation-delay: 6s;
}

/* Button Colors - Orange Theme */
.btn-primary {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    border-color: #f6ad55;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    border-color: #ed8936;
    color: white;
}

.btn-outline-primary {
    color: #f6ad55;
    border-color: #f6ad55;
}

.btn-outline-primary:hover {
    background-color: #f6ad55;
    border-color: #f6ad55;
    color: white;
}

.btn-outline-secondary {
    color: #718096;
    border-color: #718096;
}

.btn-outline-secondary:hover {
    background-color: #718096;
    border-color: #718096;
    color: white;
}

/* Animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-25px);
    }
}

@keyframes orbit {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1) translateX(-50%);
        opacity: 1;
    }
    50% {
        transform: scale(1.2) translateX(-50%);
        opacity: 0.8;
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes toolBounce {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    25% {
        transform: translateY(-10px) rotate(10deg);
    }
    75% {
        transform: translateY(-5px) rotate(-5deg);
    }
}

/* Button enhancements */
.btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: 140px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(246, 173, 85, 0.3);
}

.btn-primary:hover {
    box-shadow: 0 5px 15px rgba(246, 173, 85, 0.4);
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

/* Responsive Design */
@media (max-width: 768px) {
    .number-digit {
        font-size: 5rem;
    }
    
    .floating-orbit {
        width: 50px;
        height: 50px;
        margin: 0 0.3rem;
    }
    
    .orbit-circle {
        width: 30px;
        height: 30px;
        border-width: 2px;
    }
    
    .orbit-circle::before {
        width: 10px;
        height: 10px;
        top: -5px;
    }
    
    .error-title {
        font-size: 1.8rem;
    }
    
    .tools-container {
        gap: 1rem;
    }
    
    .tool-icon {
        font-size: 1.8rem;
    }
    
    .error-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .error-actions .btn:last-child {
        margin-bottom: 0;
    }
    
    .float-circle {
        display: none;
    }
}

/* Status Notification */
.status-notification {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

{{-- Add Font Awesome and Bootstrap --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection