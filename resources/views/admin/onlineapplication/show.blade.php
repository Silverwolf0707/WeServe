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
                <form action="{{ route('admin.applications.confirm', $application->id) }}" method="POST"
                    class="d-flex align-items-center gap-2">
                    @csrf
                    <button type="submit"
                        class="btn btn-success px-4 py-2 rounded-lg fw-semibold hover:bg-green-700 transition">
                        <i class="fas fa-check-circle me-1"></i> Confirm & Transfer
                    </button>
                    <a href="{{ route('admin.online-applications.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-lg fw-semibold">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </form>
            </div>



            </form>
        </div>
    </div>

    </div>


@endsection