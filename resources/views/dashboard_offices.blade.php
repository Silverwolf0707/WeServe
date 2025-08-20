@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Welcome {{ Auth::user()->name }}</h4>
            <form method="GET" action="{{ route('admin.home') }}" class="d-flex align-items-center">
                <label for="year" class="me-2 mb-0 text-muted">Select Year:</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(session('status'))
            <div class="alert alert-success rounded shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- STAT CARDS --}}
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    ['label' => 'Total Patients', 'value' => $totalPatients, 'icon' => 'users', 'color' => 'primary'],
                    ['label' => 'Burial Aid Case', 'value' => $totalBurialPatient, 'icon' => 'dove', 'color' => 'success'],
                    ['label' => 'Educational Aid Case', 'value' => $totalEducationalPatient, 'icon' => 'book', 'color' => 'warning'],
                    ['label' => 'Medical Aid', 'value' => $totalMedicalPatient, 'icon' => 'hospital', 'color' => 'danger']
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 bg-{{ $card['color'] }} text-white">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($card['value']) }}</h2>
                                <p class="mb-0">{{ $card['label'] }}</p>
                            </div>
                            <div>
                                <i class="fas fa-{{ $card['icon'] }} fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- MAIN CONTENT --}}
        <div class="row g-4">
            {{-- LEFT: RECENTLY SUBMITTED APPLICATIONS --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">
                        Recently Submitted Applications
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>APP001</td>
                                    <td>Juan Dela Cruz</td>
                                    <td>Medical</td>
                                </tr>
                                <tr>
                                    <td>APP002</td>
                                    <td>Maria Santos</td>
                                    <td>Education</td>
                                </tr>
                                <tr>
                                    <td>APP003</td>
                                    <td>Pedro Reyes</td>
                                    <td>Burial</td>
                                </tr>
                                <tr>
                                    <td>APP004</td>
                                    <td>Anna Lopez</td>
                                    <td>Emergency</td>
                                </tr>
                                <tr>
                                    <td>APP005</td>
                                    <td>Carlos Garcia</td>
                                    <td>Medical</td>
                                </tr>
                                <tr>
                                    <td>APP006</td>
                                    <td>Liza Ramos</td>
                                    <td>Education</td>
                                </tr>
                                <tr>
                                    <td>APP007</td>
                                    <td>Michael Cruz</td>
                                    <td>Burial</td>
                                </tr>
                                <tr>
                                    <td>APP008</td>
                                    <td>Sofia Navarro</td>
                                    <td>Emergency</td>
                                </tr>
                                <tr>
                                    <td>APP009</td>
                                    <td>Jose Fernandez</td>
                                    <td>Medical</td>
                                </tr>
                                <tr>
                                    <td>APP010</td>
                                    <td>Angela Dizon</td>
                                    <td>Education</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT: STACKED CHARTS --}}
            <div class="col-lg-6 d-flex flex-column gap-4">
                {{-- BARANGAY CHART --}}
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

                {{-- MONTHLY CHART --}}
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
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dummy data - replace with dynamic values from Blade if needed
        const barangayLabels = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4'];
        const barangayData = [45, 30, 25, 10];

        const monthLabels = ['January', 'February', 'March', 'April', 'May'];
        const monthData = [10, 20, 15, 25, 30];

        // Patients per Barangay (Doughnut)
        new Chart(document.getElementById('barangayChart'), {
            type: 'doughnut',
            data: {
                labels: barangayLabels,
                datasets: [{
                    label: 'Patients',
                    data: barangayData,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
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

        // Patients Per Month (Line)
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Patients',
                    data: monthData,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
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
@endsection