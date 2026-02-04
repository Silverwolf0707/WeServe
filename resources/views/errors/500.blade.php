{{-- resources/views/errors/500.blade.php --}}
@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-container">
                <h1 class="display-1 text-muted">500</h1>
                <h2 class="mb-4">Server Error</h2>
                <p class="lead mb-4">Something went wrong on our end. Please try again later.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
                <a href="javascript:location.reload()" class="btn btn-outline-secondary">Reload Page</a>
            </div>
        </div>
    </div>
</div>
@endsection