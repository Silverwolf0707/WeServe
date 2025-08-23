@extends('layouts.admin')

@section('content')
<style>
    /* Modern Header Styles */
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    
    .header-flex {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 20px;
        position: relative;
        z-index: 2;
    }
    
    @media (min-width: 768px) {
        .header-flex {
            flex-direction: row;
            padding: 40px 30px;
        }
    }
    
    .profile-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    @media (min-width: 768px) {
        .profile-circle {
            margin-bottom: 0;
            margin-right: 30px;
        }
    }
    
    .profile-icon {
        font-size: 36px;
        color: white;
    }
    
    .welcome-content {
        text-align: center;
        flex: 1;
    }
    
    @media (min-width: 768px) {
        .welcome-content {
            text-align: left;
        }
    }
    
    .welcome-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    
    .user-name {
        font-weight: 800;
        position: relative;
        display: inline-block;
    }
    
    .user-name:after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 3px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 3px;
    }
    
    .datetime-display {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Animated bubbles background */
    .bubbles {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1;
        overflow: hidden;
        top: 0;
        left: 0;
        pointer-events: none;
    }
    
    .bubble {
        position: absolute;
        bottom: -100px;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: rise 15s infinite;
    }
    
    /* Card Styles */
    .dashboard-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header-bg {
        height: 90px;
        background: linear-gradient(45deg, var(--card-color), var(--card-color-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .card-icon {
        font-size: 32px;
        background: rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-body {
        padding: 20px;
        position: relative;
    }
    
    .card-content {
        text-align: center;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
    }
    
    .card-stats {
        display: flex;
        justify-content: space-around;
        border-top: 1px solid rgba(0,0,0,0.05);
        margin-top: 15px;
        padding-top: 15px;
    }
    
    .stats-item {
        text-align: center;
        padding: 0 10px;
    }
    
    .stats-value {
        font-size: 24px;
        font-weight: 800;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 5px;
    }
    
    .total-users .stats-value {
        color: var(--card-color);
    }
    
    .active-users .stats-value {
        color: #38a169;
    }
    
    .card-footer {
        background: rgba(0, 0, 0, 0.02);
        padding: 12px 20px;
        text-align: center;
    }
    
    .view-btn {
        font-size: 13px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        border: none;
    }

    /* Table Styles */
    .activity-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-top: 30px;
    }

    .activity-header {
        background-color: white;
        padding: 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .view-all-btn {
        font-size: 13px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        border: none;
        transition: all 0.2s;
    }

    .activity-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .activity-table thead th {
        background-color: #f7fafc;
        color: #4a5568;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .activity-table tbody tr {
        transition: background-color 0.2s;
    }

    .activity-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .activity-table td {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        vertical-align: middle;
    }

    .activity-table td:first-child {
        font-weight: 600;
        color: #4a5568;
    }

    .activity-table td:nth-child(2) {
        font-weight: 600;
        color: var(--dept-color);
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-completed {
        background-color: #48bb78;
        color: white;
    }

    .badge-in-progress {
        background-color: #4299e1;
        color: white;
    }

    .badge-pending {
        background-color: #ed8936;
        color: white;
    }

    @keyframes rise {
        0% {
            bottom: -100px;
            transform: translateX(0);
            opacity: 0;
        }
        25% {
            opacity: 0.8;
        }
        50% {
            transform: translateX(50px);
        }
        100% {
            bottom: 110%;
            transform: translateX(-50px);
            opacity: 0;
        }
    }
</style>

<div class="dashboard-header">
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>
    
    <div class="header-flex">
        <div class="profile-circle">
            <i class="fas fa-user profile-icon"></i>
        </div>
        
        <div class="welcome-content">
            <h1 class="welcome-title">
                Welcome, <span class="user-name">{{ Auth::user()->name }}</span>
            </h1>
            <p class="datetime-display" id="current-datetime">.</p>
        </div>
    </div>
</div>

<!-- Department Cards -->
<div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
@foreach($departments as $dept)
<div class="col">
    <div class="dashboard-card" style="--card-color: {{ $dept['color'] }}; --card-color-dark: {{ $dept['color-dark'] }}">
        <div class="card-header-bg">
            <div class="card-icon">
                <i class="fas {{ $dept['icon'] }}"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-content">
                <h5 class="card-title">{{ $dept['name'] }}</h5>
            </div>
            <div class="card-stats">
                <div class="stats-item total-users">
                    <div class="stats-value">{{ $dept['total_users'] }}</div>
                    <div class="stats-label">Total Users</div>
                </div>
                <div class="stats-item active-users">
                    <div class="stats-value">{{ $dept['active_users'] }}</div>
                    <div class="stats-label">Active</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

</div>

<!-- Recent Activity Table -->
<div class="activity-card">
    <div class="activity-header">
        <h2 class="activity-title">Recent Department Activities</h2>
    </div>
    
    <div class="table-responsive">
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-08-09</td>
                    <td style="--dept-color: #4e73df">CSWD</td>
                    <td>Submitted Application #A001</td>
                    <td><span class="status-badge badge-completed">Completed</span></td>
                </tr>
                <tr>
                    <td>2025-08-08</td>
                    <td style="--dept-color: #f6c23e">Budget</td>
                    <td>Processed Funding Request</td>
                    <td><span class="status-badge badge-in-progress">In Progress</span></td>
                </tr>
                <tr>
                    <td>2025-08-07</td>
                    <td style="--dept-color: #1cc88a">Mayor's Office</td>
                    <td>Signed Approval</td>
                    <td><span class="status-badge badge-completed">Completed</span></td>
                </tr>
                <tr>
                    <td>2025-08-06</td>
                    <td style="--dept-color: #36b9cc">Accounting</td>
                    <td>Verified Expense Report</td>
                    <td><span class="status-badge badge-pending">Pending</span></td>
                </tr>
                <tr>
                    <td>2025-08-05</td>
                    <td style="--dept-color: #e74a3b">Treasurer's</td>
                    <td>Released Payment Batch #102</td>
                    <td><span class="status-badge badge-completed">Completed</span></td>
                </tr>
                <tr>
                    <td>2025-08-04</td>
                    <td style="--dept-color: #4e73df">CSWD</td>
                    <td>Updated Beneficiary List</td>
                    <td><span class="status-badge badge-in-progress">In Progress</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-datetime').textContent = now.toLocaleDateString('en-US', options);
    }
    
    // Initialize bubbles with random positions
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        const bubbles = document.querySelectorAll('.bubble');
        bubbles.forEach(bubble => {
            bubble.style.left = `${Math.random() * 90}%`;
            bubble.style.width = `${20 + Math.random() * 30}px`;
            bubble.style.height = bubble.style.width;
        });

        // Add hover effects to buttons
        document.querySelectorAll('.view-btn, .view-all-btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endsection