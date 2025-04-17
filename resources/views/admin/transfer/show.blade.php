@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h5>Transfer Details</h5>

    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Employee:</strong> {{ $employee->surname }} {{ $employee->first_name }}</p>
            <p><strong>From:</strong> {{ $transfer->previousMda->mda ?? 'N/A' }}</p>
            <p><strong>To:</strong> {{ $transfer->currentMda->mda ?? 'N/A' }}</p>
            <p><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($transfer->effective_date)->format('d M, Y') }}</p>
            <p><strong>Uploaded By:</strong> {{ $transfer->user->surname ?? 'N/A' }}</p>
            <p><strong>Document:</strong> <a href="{{ $transfer->document->document }}" target="_blank">View Letter</a></p>
        </div>
    </div>

    <a href="{{ route('employees.transfers.index', $employee->id) }}" class="btn btn-secondary mt-3">Back to List</a>
</div>

@endsection
