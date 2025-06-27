@extends('admin.admin_dashboard')
@section('admin')

<div class="card mb-4">
    <div class="card-header">
        <h5>My Leave Balances ({{ date('Y') }})</h5>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Entitled</th>
                    <th>Used</th>
                    <th>Remaining</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($balances as $balance)
                    <tr>
                        <td>{{ $balance->leaveType->name }}</td>
                        <td>{{ $balance->entitled_days }}</td>
                        <td>{{ $balance->used_days }}</td>
                        <td>{{ $balance->remaining_days }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No balances found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection