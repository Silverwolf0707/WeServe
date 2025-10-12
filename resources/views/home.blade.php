@extends('layouts.admin')

@section('content')
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
                <p class="datetime-display" id="current-datetime">Loading date & time...</p>
            </div>
        </div>
    </div>

    <!-- Department Cards -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
        @foreach ($departments as $dept)
            <div class="col">
                <div class="dashboard-card"
                    style="--card-color: {{ $dept['color'] }}; --card-color-dark: {{ $dept['color-dark'] }}">
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
                        <th>Subject Type</th>
                        <th>Action</th>
                        <th>Username</th>
                        <th>Host</th>
                    </tr>
                </thead>
                <tbody> @forelse ($recentActivities as $activity) <tr>
                    <td>{{ $activity['date'] }}</td>
                    <td style="--dept-color: {{ $activity['color'] }}">{{ $activity['department'] }}</td>
                    <td>{{ $activity['subject_type'] }}</td>
                    <td>
                        <span class="status-badge badge {{ $activity['badge'] }}">
                            {{ $activity['action'] }}
                        </span>
                    </td>

                    <td>{{ $activity['username'] ?? 'System' }}</td>
                    <td>{{ $activity['host'] ?? 'N/A' }}</td>
                </tr> @empty <tr>
                        <td colspan="4" class="text-center text-muted">No recent activities found.</td>
                    </tr> @endforelse </tbody>
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

        document.addEventListener('DOMContentLoaded', function () {
            // Start datetime updater
            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Initialize bubble positions
            const bubbles = document.querySelectorAll('.bubble');
            bubbles.forEach(bubble => {
                bubble.style.left = `${Math.random() * 90}%`;
                const size = 20 + Math.random() * 30;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
            });

            // Add hover effects to buttons
            document.querySelectorAll('.view-btn, .view-all-btn').forEach(button => {
                button.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                });
                button.addEventListener('mouseleave', function () {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
@endsection