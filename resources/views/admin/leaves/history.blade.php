@extends('admin.admin_dashboard')
@section('admin')

<style>
    .leave-history-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background-color: #fff;
    }
    
    .leave-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-radius: 10px 10px 0 0;
    }
    
    .leave-title {
        color: #2d3748;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .leave-count-badge {
        background-color: #4299e1;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    
    .leave-table {
        margin-bottom: 0;
    }
    
    .leave-table thead th {
        background-color: #f7fafc;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-top: none;
        padding: 0.75rem 1rem;
    }
    
    .leave-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-color: #edf2f7;
    }
    
    .leave-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .leave-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-pending {
        background-color: #fffaf0;
        color: #dd6b20;
    }
    
    .badge-approved {
        background-color: #f0fff4;
        color: #38a169;
    }
    
    .badge-rejected {
        background-color: #fff5f5;
        color: #e53e3e;
    }
    
    .badge-cancelled {
        background-color: #f8f9fa;
        color: #718096;
    }
    
    .leave-number {
        color: #3182ce;
        font-weight: 500;
    }
    
    .leave-date {
        color: #4a5568;
        font-size: 0.85rem;
    }
    
    .leave-actions .btn {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .empty-icon {
        color: #cbd5e0;
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }
    
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2d3748;
    }
</style>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="leave-history-card">
                <div class="leave-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-history text-blue-500 me-3" style="font-size: 1.5rem;"></i>
                        <h4 class="leave-title mb-0">My Leave History</h4>
                    </div>
                    <span class="leave-count-badge">{{ count($leaves) }} Records</span>
                </div>
                
                <div class="card-body p-0">
                    @if(count($leaves) > 0)
                        <div class="table-responsive">
                            <table class="table leave-table">
                                <thead>
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
                                                <span class="leave-number">
                                                    {{ $leave->leave_number ?? 'LV-' . str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $leave->leaveType->name ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="leave-date">
                                                    <div>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-blue-100 text-blue-800">
                                                    {{ $leave->total_days ?? 0 }} day(s)
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match($leave->status ?? 'pending') {
                                                        'approved' => 'badge-approved',
                                                        'rejected' => 'badge-rejected',
                                                        'cancelled' => 'badge-cancelled',
                                                        default => 'badge-pending'
                                                    };
                                                @endphp
                                                <span class="leave-badge {{ $statusClass }}">
                                                    <i class="fas fa-{{ $leave->status === 'approved' ? 'check' : ($leave->status === 'rejected' ? 'times' : 'clock') }} me-1"></i>
                                                    {{ ucfirst($leave->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="leave-date">
                                                    {{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->format('M d, Y') }}
                                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->diffForHumans() }}</div>
                                                </div>
                                            </td>
                                            <td class="leave-actions">
                                                <div class="d-flex">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary me-2" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewLeaveModal{{ $leave->id }}"
                                                            title="View Leave Details">
                                                       <i class="lni lni-radio-button"></i>
                                                    </button>
                                                    @if(in_array($leave->status, ['pending', 'draft']))
                                                        <a href="{{ route('leaves.edit', $leave->id) }}" 
                                                           class="btn btn-sm btn-outline-warning me-2" 
                                                           title="Edit">
                                                           <i class="fadeIn animated bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger cancel-leave-btn" 
                                                                data-leave-id="{{ $leave->id }}"
                                                                title="Cancel">
                                                             <i class="fadeIn animated bx bx-time"></i>
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
                            <div class="px-4 py-3 border-top">
                                {{ $leaves->links() }}
                            </div>
                        @endif

                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h5 class="text-gray-600 mb-2">No Leave Records Found</h5>
                            <p class="text-gray-500 mb-4">You haven't applied for any leaves yet.</p>
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Leave Type</label>
                            <p>{{ $leave->leaveType->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted">Status</label>
                            <p>
                                @php
                                    $statusClass = match($leave->status ?? 'pending') {
                                        'approved' => 'badge-approved',
                                        'rejected' => 'badge-rejected',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="leave-badge {{ $statusClass }}">
                                    {{ ucfirst($leave->status ?? 'Pending') }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Total Days</label>
                            <p>{{ $leave->total_days ?? 0 }} day(s)</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted">Applied On</label>
                            <p>{{ \Carbon\Carbon::parse($leave->applied_date ?? $leave->created_at)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Start Date</label>
                            <p>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">End Date</label>
                            <p>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($leave->reason)
                    <div class="mb-4">
                        <label class="text-muted">Reason</label>
                        <p class="bg-gray-50 p-3 rounded">{{ $leave->reason }}</p>
                    </div>
                @endif
                
                @if($leave->comments)
                    <div class="mb-4">
                        <label class="text-muted">Comments</label>
                        <p class="bg-gray-50 p-3 rounded">{{ $leave->comments }}</p>
                    </div>
                @endif
                
                @if($leave->supporting_document_url)
                    <div class="mb-4">
                        <label class="text-muted">Supporting Document</label>
                        <a href="{{ $leave->supporting_document_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fadeIn animated bx bx-download"></i>Download Document
                        </a>
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
    // Cancel leave function
    function cancelLeave(leaveId, buttonElement) {
        if (confirm('Are you sure you want to cancel this leave application?')) {
            const originalContent = buttonElement.innerHTML;
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            buttonElement.disabled = true;
            
            fetch(`/leaves/${leaveId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to cancel leave');
                    buttonElement.innerHTML = originalContent;
                    buttonElement.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling the leave');
                buttonElement.innerHTML = originalContent;
                buttonElement.disabled = false;
            });
        }
    }

    // Attach event listeners
    document.querySelectorAll('.cancel-leave-btn').forEach(button => {
        button.addEventListener('click', function() {
            cancelLeave(this.getAttribute('data-leave-id'), this);
        });
    });
});
</script>

@endsection