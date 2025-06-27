@extends('admin.admin_dashboard')
@section('admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>My Leave History
                    </h4>
                    <div class="card-tools">
                        <span class="badge bg-info">{{ count($leaves) }} Records</span>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(count($leaves) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Type</th>
                                        <th>Period</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">
                                                    {{ $leave->leave_number ?? 'LV-' . str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $leave->leaveType->name ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <strong>From:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}<br>
                                                    <strong>To:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info fs-6">
                                                    {{ $leave->total_days ?? 0 }} day(s)
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match($leave->status ?? 'pending') {
                                                        'approved' => 'bg-success',
                                                        'rejected' => 'bg-danger',
                                                        'cancelled' => 'bg-warning text-dark',
                                                        default => 'bg-warning text-dark'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    <i class="fas fa-{{ $leave->status === 'approved' ? 'check' : ($leave->status === 'rejected' ? 'times' : 'clock') }} me-1"></i>
                                                    {{ ucfirst($leave->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->format('M d, Y') }}<br>
                                                    <em>{{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->diffForHumans() }}</em>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary btn-sm view-details-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewLeaveModal{{ $leave->id }}"
                                                            title="View Details">
                                                       <i class="lni lni-radio-button"></i>
                                                    </button>
                                                    @if(in_array($leave->status, ['pending', 'draft']))
                                                        <a href="{{ route('leaves.edit', $leave->id) }}" 
                                                           class="btn btn-outline-warning btn-sm" 
                                                           title="Edit">
                                                           <i class="fadeIn animated bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm cancel-leave-btn" 
                                                                data-leave-id="{{ $leave->id }}"
                                                                title="Cancel">
                                                             <i class="fadeIn animated bx bx-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if(method_exists($leaves, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $leaves->links() }}
                            </div>
                        @endif

                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No Leave Records Found</h4>
                            <p class="text-muted mb-4">You haven't applied for any leaves yet.</p>
                            <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Apply for Leave
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}

@foreach ($leaves as $leave)
<div class="modal fade" id="viewLeaveModal{{ $leave->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Details - {{ $leave->leave_number ?? 'LV-' . str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Leave Type:</strong> {{ $leave->leaveType->name ?? 'Unknown' }}</p>
                        <p><strong>Status:</strong> 
                            @php
                                $statusClass = match($leave->status ?? 'pending') {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'cancelled' => 'bg-warning text-dark',
                                    default => 'bg-warning text-dark'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($leave->status ?? 'Pending') }}</span>
                        </p>
                        <p><strong>Total Days:</strong> {{ $leave->total_days ?? 0 }} day(s)</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</p>
                        <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
                        <p><strong>Applied On:</strong> {{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>
                @if($leave->reason)
                    <div class="mt-3">
                        <strong>Reason:</strong>
                        <p class="text-muted">{{ $leave->reason }}</p>
                    </div>
                @endif
                @if($leave->comments)
                    <div class="mt-3">
                        <strong>Comments:</strong>
                        <p class="text-muted">{{ $leave->comments }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.cancel-leave-btn').forEach(button => {
        button.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-leave-id');
            cancelLeave(leaveId, this);
        });
    });

});

// Cancel leave function
function cancelLeave(leaveId, buttonElement) {
    if (confirm('Are you sure you want to cancel this leave application?')) {
        
        const originalContent = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        buttonElement.disabled = true;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            alert('CSRF token not found. Please refresh the page.');
            buttonElement.innerHTML = originalContent;
            buttonElement.disabled = false;
            return;
        }
        
        fetch(`/leaves/${leaveId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Leave application cancelled successfully.');
                location.reload();
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            buttonElement.innerHTML = originalContent;
            buttonElement.disabled = false;
            alert('An error occurred while cancelling the leave: ' + error.message);
        });
    }
}
</script>

@endsection
