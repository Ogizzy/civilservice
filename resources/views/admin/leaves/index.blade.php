@extends('admin.admin_dashboard')
@section('admin')

<style>
    .leave-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .filter-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .data-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }
    
    .modern-table {
        margin: 0;
        border: none;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        font-weight: 600;
        color: #495057;
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modern-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }
    
    .modern-table tbody tr:hover {
        background-color: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .modern-table tbody td {
        border: none;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
        color: #d63031;
    }
    
    .badge-approved {
        background: linear-gradient(135deg, #55efc4 0%, #00b894 100%);
        color: white;
    }
    
    .badge-rejected {
        background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
        color: white;
    }
    
    .badge-cancelled {
        background: linear-gradient(135deg, #b2bec3 0%, #636e72 100%);
        color: white;
    }
    
    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        border: none;
        margin: 0 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .btn-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }
    .btn-warning { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }
    .btn-success { background: linear-gradient(135deg, #00b894 0%, #00a085 100%); }
    .btn-danger { background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); }
    .btn-secondary { background: linear-gradient(135deg, #b2bec3 0%, #636e72 100%); }
    
    .filter-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .form-select, .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .filter-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .employee-info {
        font-weight: 600;
        color: #2d3748;
    }
    
    .employee-number {
        color: #718096;
        font-size: 0.8rem;
        font-weight: 400;
    }
    
    .document-indicator {
        color: #667eea;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .leave-number {
        font-family: 'Courier New', monospace;
        background: #f7fafc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e9ecef;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
        border: none;
        border-radius: 10px;
        color: #d63031;
    }
    
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        color: #718096;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="leave-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 fw-bold">
                    <i class="bx bx-calendar-event me-2"></i>
                    Leave Management System
                </h2>
                <p class="mb-0 opacity-90">Manage and track employee leave applications efficiently</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('leaves.create') }}" class="btn btn-light btn-lg px-4 py-2 fw-bold">
                    <i class="bx bx-plus me-2"></i> Apply for Leave
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-warning">{{ $leaves->where('status', 'pending')->count() }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-success">{{ $leaves->where('status', 'approved')->count() }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-danger">{{ $leaves->where('status', 'rejected')->count() }}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-info">{{ $leaves->count() }}</div>
                <div class="stats-label">Total Applications</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('leaves.index') }}">
                <div class="filter-section">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    🟡 Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    🟢 Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    🔴 Rejected</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    ⚫ Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Leave Type</label>
                            <select name="leave_type_id" class="form-select">
                                <option value="">All Leave Types</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary filter-btn me-2">
                                <i class="bx bx-filter-alt me-1"></i> Filter
                            </button>
                            <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary filter-btn">
                                <i class="bx bx-refresh me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="data-card">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="bx bx-hash me-1"></i> Leave Number</th>
                        <th><i class="bx bx-user me-1"></i> Employee</th>
                        <th><i class="bx bx-category me-1"></i> Leave Type</th>
                        <th><i class="bx bx-calendar me-1"></i> Start Date</th>
                        <th><i class="bx bx-calendar-check me-1"></i> End Date</th>
                        <th><i class="bx bx-time me-1"></i> Days</th>
                        <th><i class="bx bx-flag me-1"></i> Status</th>
                        <th><i class="bx bx-calendar-plus me-1"></i> Applied Date</th>
                        <th><i class="bx bx-cog me-1"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <span class="leave-number">{{ $leave->leave_number }}</span>
                            </td>
                            <td>
                                <div class="employee-info">
                                    {{ $leave->employee->surname }}, {{ $leave->employee->first_name }}
                                </div>
                                <div class="employee-number">{{ $leave->employee->employee_number }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $leave->leaveType->name }}</span>
                            </td>
                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $leave->total_days }}</span>
                            </td>
                            <td>
                                @switch($leave->status)
                                    @case('pending')
                                        <span class="status-badge badge-pending">Pending</span>
                                        @break
                                    @case('approved')
                                        <span class="status-badge badge-approved">Approved</span>
                                        @break
                                    @case('rejected')
                                        <span class="status-badge badge-rejected">Rejected</span>
                                        @break
                                    @case('cancelled')
                                        <span class="status-badge badge-cancelled">Cancelled</span>
                                        @break
                                @endswitch
                                @if ($leave->supporting_document_url)
                                    <div class="document-indicator">
                                        <i class="fas fa-paperclip me-1"></i> Document Attached
                                    </div>
                                @endif
                            </td>
                            <td>{{ $leave->applied_date->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    {{-- View Button --}}
                                    <a href="{{ route('leaves.show', $leave) }}"
                                        class="action-btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Employee Actions (Edit/Cancel) --}}
                                    @if ($leave->status == 'pending' && (Auth::user()->employee && Auth::user()->employee->id == $leave->employee_id))
                                        <a href="{{ route('leaves.edit', $leave) }}"
                                            class="action-btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="action-btn btn-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal{{ $leave->id }}" 
                                            title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    {{-- Admin Actions (Approve/Reject) --}}
                                    @if ($leave->status == 'pending' && Auth::user()->role && in_array(Auth::user()->role->role, ['BDIC Super Admin', 'Director', 'Commissioner', 'Head of Service']))
                                        <button type="button" class="action-btn btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $leave->id }}" 
                                            title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="action-btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $leave->id }}" 
                                            title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bx bx-calendar-x" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No leave applications found</h5>
                                    <p>Try adjusting your filters or create a new leave application.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($leaves->hasPages())
            <div class="d-flex justify-content-center py-4">
                {{ $leaves->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
@foreach($leaves as $leave)
    @if($leave->status == 'pending' && Auth::user()->canManageOwnLeave($leave))
        <div class="modal fade" id="cancelModal{{ $leave->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $leave->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('leaves.cancel', $leave) }}">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="cancelModalLabel{{ $leave->id }}">
                                <i class="fas fa-times-circle me-2"></i>Cancel Leave Application
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                <div class="col-sm-8">
                                    {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                                    <span class="badge bg-primary ms-2">{{ $leave->total_days }} days</span>
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Are you sure you want to cancel this leave application?</strong>
                                <br><small>This action cannot be undone.</small>
                            </div>
                            <div class="mb-3">
                                <label for="cancelRemarks{{ $leave->id }}" class="form-label fw-semibold">
                                    Reason for Cancellation <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea class="form-control" id="cancelRemarks{{ $leave->id }}" name="remarks" rows="3" 
                                    placeholder="Please provide a reason for cancellation..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i> Keep Application
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i> Cancel Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- Approve Modal --}}
@foreach($leaves as $leave)
    @if($leave->status == 'pending' && Auth::user()->canApproveLeaves())
        <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $leave->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="approveModalLabel{{ $leave->id }}">
                                <i class="fas fa-check-circle me-2"></i>Approve Leave Application
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Employee:</div>
                                <div class="col-sm-8">{{ $leave->employee->surname }}, {{ $leave->employee->first_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                <div class="col-sm-8">
                                    {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                                    <span class="badge bg-success ms-2">{{ $leave->total_days }} days</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="remarks{{ $leave->id }}" class="form-label fw-semibold">
                                    Approval Remarks <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea class="form-control" id="remarks{{ $leave->id }}" name="remarks" rows="3" 
                                    placeholder="Add any remarks for approval..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> Approve Leave
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- Reject Modal --}}
@foreach($leaves as $leave)
    @if($leave->status == 'pending' && Auth::user()->canApproveLeaves())
        <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $leave->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('leaves.reject', $leave) }}">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="rejectModalLabel{{ $leave->id }}">
                                <i class="fas fa-times-circle me-2"></i>Reject Leave Application
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Employee:</div>
                                <div class="col-sm-8">{{ $leave->employee->surname }}, {{ $leave->employee->first_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                <div class="col-sm-8">
                                    {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                                    <span class="badge bg-danger ms-2">{{ $leave->total_days }} days</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="rejectRemarks{{ $leave->id }}" class="form-label fw-semibold">
                                    Reason for Rejection <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="rejectRemarks{{ $leave->id }}" name="remarks" rows="3" 
                                    placeholder="Please provide a detailed reason for rejection..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i> Reject Leave
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection