@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Step Details</h4>
        <a href="{{ route('steps.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Step:</strong> {{ $step->step }}</p>
        </div>
    </div>
</div>

@endsection
