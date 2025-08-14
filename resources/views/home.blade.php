@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">

                {{-- Page Title --}}
                <div class="mb-4">
                    <h1 class="fw-bold text-dark">Admin Dashboard</h1>
                    <p class="text-muted">Overview of department activities and key statistics</p>
                </div>

                {{-- Status Alert --}}
                @if(session('status'))
                    <div class="alert alert-success shadow-sm" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4 mb-4">
                    @php
                        $departments = [
                            ['name' => 'CSWD', 'color' => 'primary', 'icon' => 'fa-users', 'total_users' => 3, 'active_users' => 2],
                            ['name' => 'Mayor\'s Office', 'color' => 'success', 'icon' => 'fa-building', 'total_users' => 2, 'active_users' => 1],
                            ['name' => 'Budget', 'color' => 'warning', 'icon' => 'fa-wallet', 'total_users' => 2, 'active_users' => 1],
                            ['name' => 'Accounting', 'color' => 'info', 'icon' => 'fa-calculator', 'total_users' => 2, 'active_users' => 1],
                            ['name' => 'Treasurer\'s', 'color' => 'danger', 'icon' => 'fa-coins', 'total_users' => 2, 'active_users' => 2],
                        ];
                    @endphp

                    @foreach($departments as $dept)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 rounded-3 text-center"
                                style="border-top: 4px solid var(--bs-{{ $dept['color'] }});">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas {{ $dept['icon'] }} fa-2x text-{{ $dept['color'] }}"></i>
                                    <h5 class="mt-2 fw-bold">{{ $dept['name'] }}</h5>
                                    <p class="mb-1 text-muted">Total Users: {{ $dept['total_users'] }}</p>
                                    <p class="mb-0 text-success">Active Users: {{ $dept['active_users'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



                {{-- Recent Activity Table --}}
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold text-dark">Recent Department Activities</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover">
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
                                    <td>CSWD</td>
                                    <td>Submitted Application #A001</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-08</td>
                                    <td>Budget</td>
                                    <td>Processed Funding Request</td>
                                    <td><span class="badge bg-info">In Progress</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-07</td>
                                    <td>Mayor's Office</td>
                                    <td>Signed Approval</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-06</td>
                                    <td>Accounting</td>
                                    <td>Verified Expense Report</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-05</td>
                                    <td>Treasurer's</td>
                                    <td>Released Payment Batch #102</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-04</td>
                                    <td>CSWD</td>
                                    <td>Updated Beneficiary List</td>
                                    <td><span class="badge bg-info">In Progress</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-03</td>
                                    <td>Budget</td>
                                    <td>Reviewed Annual Budget Proposal</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>2025-08-02</td>
                                    <td>Mayor's Office</td>
                                    <td>Issued Special Event Permit</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection