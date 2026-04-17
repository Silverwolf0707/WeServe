{{-- resources/views/errors/429.blade.php --}}
@extends('layouts.app')

@section('title', 'Too Many Requests')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 text-center">
                {{-- Animated 429 --}}
                <div class="error-number mb-4">
                    <span class="number-digit">4</span>
                    <div class="floating-orbit">
                        <div class="orbit-circle"></div>
                    </div>
                    <span class="number-digit">9</span>
                </div>

                {{-- Animated Text --}}
                <div class="error-content">
                    <h2 class="error-title mb-3">Too Many Requests</h2>
                    <p class="error-message lead text-muted mb-4">
                        You've sent too many requests in a short period. Please slow down and try again in a moment.
                    </p>

                    {{-- Animated Throttle / Gauge Icon --}}
                    <div class="search-animation mb-4">
                        <div class="gauge-icon">
                            <div class="gauge-arc"></div>
                            <div class="gauge-needle"></div>
                            <div class="gauge-dot"></div>
                        </div>
                    </div>

                    {{-- Countdown --}}
                    <div class="countdown-wrap mb-4">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Try again in</p>
                        <span class="countdown-badge" id="countdown">5:00</span>
                        <p class="text-muted mt-1" style="font-size: 0.9rem;">minutes</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="error-actions">
                        <a onclick="reloadPreviousPage()" class="btn btn-outline-secondary btn-lg me-3">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-primary btn-lg" id="retry-btn" disabled>
                            <i class="fas fa-redo me-2"></i>Retry
                        </button>
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
function reloadPreviousPage() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
        setTimeout(() => {
            if (window.location.href === document.referrer) {
                location.reload();
            }
        }, 100);
    } else {
        window.location.href = '/';
    }
}

// Countdown timer
let seconds = 300;
const countdownEl = document.getElementById('countdown');
const retryBtn = document.getElementById('retry-btn');

function formatTime(s) {
    const m = Math.floor(s / 60);
    const sec = s % 60;
    return m + ':' + String(sec).padStart(2, '0');
}

const timer = setInterval(() => {
    seconds--;
    countdownEl.textContent = formatTime(seconds);
    if (seconds <= 0) {
        clearInterval(timer);
        countdownEl.textContent = '0:00';
        retryBtn.removeAttribute('disabled');
        retryBtn.classList.add('ready');
    }
}, 1000);
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
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: float 3s ease-in-out infinite;
}

.number-digit:nth-child(1) {
    animation-delay: 0s;
}

.number-digit:nth-child(3) {
    animation-delay: 0.5s;
}

.floating-orbit {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 1rem;
}

.orbit-circle {
    position: absolute;
    width: 80px;
    height: 80px;
    border: 4px solid #48bb78;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: orbit 4s linear infinite;
}

.orbit-circle::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: #38a169;
    border-radius: 50%;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    animation: pulse 2s ease-in-out infinite;
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

.search-animation {
    animation: slideInUp 0.8s ease-out 0.4s both;
}

/* Gauge / speedometer icon */
.gauge-icon {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 36px;
    overflow: visible;
}

.gauge-arc {
    width: 54px;
    height: 27px;
    border: 3px solid #48bb78;
    border-bottom: none;
    border-radius: 27px 27px 0 0;
    position: absolute;
    top: 0;
    left: 0;
}

.gauge-needle {
    width: 2px;
    height: 22px;
    background: #38a169;
    position: absolute;
    top: 5px;
    left: 26px;
    transform-origin: bottom center;
    transform: rotate(60deg);
    border-radius: 2px;
    animation: needleSweep 1.5s ease-in-out infinite alternate;
}

.gauge-dot {
    width: 8px;
    height: 8px;
    background: #48bb78;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    left: 23px;
}

/* Countdown badge */
.countdown-wrap {
    animation: slideInUp 0.8s ease-out 0.45s both;
}

.countdown-badge {
    display: inline-block;
    font-size: 2.2rem;
    font-weight: 700;
    color: #38a169;
    background: rgba(72, 187, 120, 0.1);
    border: 2px solid #48bb78;
    border-radius: 50%;
    width: 70px;
    height: 70px;
    line-height: 66px;
    text-align: center;
    animation: pulse 1s ease-in-out infinite;
}

.error-actions {
    animation: slideInUp 0.8s ease-out 0.6s both;
}

/* Disabled retry button */
#retry-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

#retry-btn.ready {
    animation: pulse 0.6s ease-in-out 2;
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
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.1) 0%, rgba(56, 161, 105, 0.1) 100%);
    animation: float 6s ease-in-out infinite;
}

.circle-1 { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
.circle-2 { width: 150px; height: 150px; top: 60%; right: 10%; animation-delay: 2s; }
.circle-3 { width: 80px; height: 80px; bottom: 20%; left: 20%; animation-delay: 4s; }

.btn-primary {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border-color: #48bb78;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    border-color: #38a169;
}

.btn-outline-primary {
    color: #48bb78;
    border-color: #48bb78;
}

.btn-outline-primary:hover:not(:disabled) {
    background-color: #48bb78;
    border-color: #48bb78;
    color: white;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@keyframes orbit {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

@keyframes slideInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes needleSweep {
    0% { transform: rotate(-70deg); }
    100% { transform: rotate(70deg); }
}

@media (max-width: 768px) {
    .number-digit { font-size: 5rem; }
    .floating-orbit { width: 80px; height: 80px; margin: 0 0.5rem; }
    .orbit-circle { width: 50px; height: 50px; }
    .error-title { font-size: 2rem; }
    .error-actions .btn { display: block; width: 100%; margin-bottom: 1rem; }
    .error-actions .btn:last-child { margin-bottom: 0; }
}

.btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

.btn:hover::before { left: 100%; }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection