@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center bg-primary text-white">
        <h4 class="mb-0 fw-bold">
            <i class="fas fa-file-medical me-2"></i> Online Patient Applications
        </h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Case Type</th>
                        <th>Case Category</th>
                        <th>Claimant Name</th>
                        <th>Applicant Name</th>
                        <th>Diagnosis</th>
                        <th>Age</th>
                        <th>Tracking ID</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->id }}</td>
                            <td>{{ $app->case_type }}</td>
                            <td>{{ $app->case_category }}</td>
                            <td>{{ $app->claimant_name }}</td>
                            <td>{{ $app->applicant_name }}</td>
                            <td class="text-truncate" style="max-width:200px">{{ $app->diagnosis }}</td>
                            <td>{{ $app->age }}</td>
                            <td>{{ $app->tracking_number }}</td>
                            <td>
                                <a href="{{ route('admin.online-applications.show', $app->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
