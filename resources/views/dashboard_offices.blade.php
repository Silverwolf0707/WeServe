@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="dashboard-header position-relative mb-4">
            <div class="bubbles">
                <div class="bubble" style="left:10%; width:20px; height:20px; animation-duration:12s;"></div>
                <div class="bubble" style="left:30%; width:30px; height:30px; animation-duration:18s;"></div>
                <div class="bubble" style="left:50%; width:25px; height:25px; animation-duration:15s;"></div>
                <div class="bubble" style="left:70%; width:35px; height:35px; animation-duration:20s;"></div>
                <div class="bubble" style="left:90%; width:20px; height:20px; animation-duration:17s;"></div>
            </div>

            <div class="header-flex container-fluid d-flex flex-column flex-md-row align-items-center gap-3">
                <div class="profile-circle d-flex align-items-center justify-content-center">
                    <i class="fas fa-user profile-icon"></i>
                </div>

                <div class="welcome-content flex-grow-1">
                    <h2 class="welcome-title">
                        Welcome, <span class="user-name">{{ Auth::user()->name }}</span>!
                    </h2>
                    <p class="datetime-display" id="current-datetime">Loading date & time...</p>
                </div>
            </div>
        </div>
    </div>


    @if (session('status'))
        <div class="alert alert-success rounded shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="row g-4 mb-4">
        @php
            $cards = [
                [
                    'label' => 'Total Patients',
                    'value' => $totalPatients,
                    'icon' => 'users',
                    'color' => '#4e73df',
                    'colorDark' => '#2e59d9',
                ],
                [
                    'label' => 'Burial Aid Case',
                    'value' => $totalBurialPatient,
                    'icon' => 'dove',
                    'color' => '#38a169',
                    'colorDark' => '#2f855a',
                ],
                [
                    'label' => 'Educational Aid Case',
                    'value' => $totalEducationalPatient,
                    'icon' => 'book',
                    'color' => '#f6c23e',
                    'colorDark' => '#d69e2e',
                ],
                [
                    'label' => 'Medical Aid',
                    'value' => $totalMedicalPatient,
                    'icon' => 'hospital',
                    'color' => '#e74a3b',
                    'colorDark' => '#c5302c',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-sm-6 col-xl-3">
                <div class="dashboard-card"
                    style="--card-color: {{ $card['color'] }}; --card-color-dark: {{ $card['colorDark'] }};">
                    {{-- Card Header --}}
                    <div class="card-header-bg">
                        <div class="card-icon">
                            <i class="fas fa-{{ $card['icon'] }}"></i>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">
                        <div class="card-content">
                            <h3 class="card-title">{{ $card['label'] }}</h3>
                            <p class="stats-value">{{ number_format($card['value']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        @can('submit_patient_application')
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">
                        Pending Submission
                    </div>

                    <div class="card-body p-0">
                        <div style="max-height: 1000px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentlyDraft as $log)
                                        <tr>
                                            <td>{{ $log->patient->control_number ?? 'N/A' }}</td>
                                            <td>{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                            <td>{{ $log->patient->case_category ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.patient-records.show', $log->patient->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No pending submission.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('approve_patient')
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">
                        Pending Approvals
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 1000px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentlySubmitted as $log)
                                        <tr>
                                            <td>{{ $log->patient->control_number ?? 'N/A' }}</td>
                                            <td>{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                            <td>{{ $log->patient->case_category ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.process-tracking.show', $log->patient->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No pending approvals or rejections.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('accounting_dv_input')
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">
                        Pending DV Input
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 1000px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentlyBudgetAllocated as $log)
                                        <tr>
                                            <td>{{ $log->patient->control_number ?? 'N/A' }}</td>
                                            <td>{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                            <td>{{ $log->patient->case_category ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.process-tracking.show', $log->patient->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No pending dv input.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('budget_allocate')
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">
                        Pending Budget Allocation
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 1000px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentlyApprovedRejected as $log)
                                        <tr>
                                            <td>{{ $log->patient->control_number ?? 'N/A' }}</td>
                                            <td>{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                            <td>{{ $log->patient->case_category ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.process-tracking.show', $log->patient->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No pending budget allocations.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        @endcan
            @can('treasury_disburse')
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white fw-semibold">
                            Pending Disbursement
                        </div>
                        <div class="card-body p-0">
                            <div style="max-height: 1000px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentlyDvSubmitted as $log)
                                        <tr>
                                            <td>{{ $log->patient->control_number ?? 'N/A' }}</td>
                                            <td>{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                            <td>{{ $log->patient->case_category ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.process-tracking.show', $log->patient->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No pending disbursement.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            @endcan

            <div class="col-lg-6 d-flex flex-column gap-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Patients per Barangay
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 250px;">
                            <canvas id="barangayChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Patients Per Month
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 250px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @section('scripts')
        @parent
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const barangayLabels = @json($barangayLabels);
            const barangayData = @json($barangayData);

            const monthLabels = @json($monthlyLabels);
            const monthData = @json($monthlyData);

            new Chart(document.getElementById('barangayChart'), {
                type: 'doughnut',
                data: {
                    labels: barangayLabels,
                    datasets: [{
                        data: barangayData,
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc',
                            '#f6c23e', '#e74a3b', '#858796',
                            '#20c997', '#6610f2', '#6f42c1',
                            '#fd7e14', '#e83e8c', '#198754'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Patients',
                        data: monthData,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <script>
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const dateStr = now.toLocaleDateString(undefined, options);
                const timeStr = now.toLocaleTimeString();
                document.getElementById('current-datetime').textContent = `${dateStr} | ${timeStr}`;
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();
        </script>
    @endsection