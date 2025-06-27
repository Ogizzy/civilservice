@extends('admin.admin_dashboard')
@section('admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Application Details</h3>
                    <div class="card-tools">
                        @if($leave->status === 'pending')
                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Application
                            </a>
                        @endif
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Application Status Alert --}}
                    <div class="alert 
                        @if($leave->status === 'approved') alert-success
                        @elseif($leave->status === 'rejected') alert-danger
                        @elseif($leave->status === 'cancelled') alert-warning
                        @else alert-info
                        @endif">
                        <i class="fas 
                            @if($leave->status === 'approved') fa-check-circle
                            @elseif($leave->status === 'rejected') fa-times-circle
                            @elseif($leave->status === 'cancelled') fa-ban
                            @else fa-info-circle
                            @endif"></i>
                        <strong>Application Status:</strong> {{ ucfirst($leave->status) }}
                        @if($leave->status === 'approved' && $leave->approved_by)
                            <br><small>Approved by: {{ $leave->approver->name ?? 'N/A' }} on {{ \Carbon\Carbon::parse($leave->approved_at)->format('d M Y, H:i') }}</small>
                        @elseif($leave->status === 'rejected' && $leave->rejected_by)
                            <br><small>Rejected by: {{ $leave->rejector->name ?? 'N/A' }} on {{ \Carbon\Carbon::parse($leave->rejected_at)->format('d M Y, H:i') }}</small>
                        @endif
                    </div>

                    <div class="row">
                        {{-- Employee Information --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Employee Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Employee Number:</strong></td>
                                            <td>{{ $employee->employee_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Full Name:</strong></td>
                                            <td>{{ $employee->surname ?? '' }}, {{ $employee->first_name ?? '' }} {{ $employee->middle_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>MDA:</strong></td>
                                            <td>{{ $employee->mda->mda ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                                            <td>{{ $employee->department ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position:</strong></td>
                                            <td>{{ $employee->position ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Leave Balance Information --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Leave Balance ({{ date('Y') }})</h4>
                                </div>
                                <div class="card-body">
                                    @if(isset($leaveBalance))
                                        <div class="row">
                                            <div class="col-4 text-center">
                                                <h5 class="text-primary">{{ $leaveBalance->entitled_days }}</h5>
                                                <small>Entitled</small>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h5 class="text-warning">{{ $leaveBalance->used_days }}</h5>
                                                <small>Used</small>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h5 class="text-success">{{ $leaveBalance->remaining_days }}</h5>
                                                <small>Remaining</small>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">No balance information available for this leave type</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Leave Application Details --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Leave Application Details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Application ID:</strong></td>
                                                    <td>#{{ $leave->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Leave Type:</strong></td>
                                                    <td>{{ $leave->leaveType->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Start Date:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>End Date:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Days:</strong></td>
                                                    <td><span class="badge badge-primary">{{ $leave->total_days }} days</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Applied On:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y, H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Reason for Leave:</strong></label>
                                                <div class="border p-3 bg-light rounded">{{ $leave->reason }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Contact Information --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Contact Information (While on Leave)</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Contact Address:</strong></td>
                                            <td>{{ $leave->contact_address ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Phone:</strong></td>
                                            <td>{{ $leave->contact_phone ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Emergency Contact Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Emergency Contact:</strong></td>
                                            <td>{{ $leave->emergency_contact ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Emergency Phone:</strong></td>
                                            <td>{{ $leave->emergency_phone ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Supporting Document --}}
                    @if($leave->supporting_document_url)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Supporting Document</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-alt fa-2x text-primary mr-3"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $leave->supporting_document_name }}</h6>
                                                <small class="text-muted">Uploaded on {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}</small>
                                            </div>
                                            <div class="ml-auto">
                                                <a href="{{ $leave->supporting_document_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i> View/Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Approval/Rejection Comments --}}
                    @if($leave->comments)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            @if($leave->status === 'approved')
                                                Approval Comments
                                            @elseif($leave->status === 'rejected')
                                                Rejection Comments
                                            @else
                                                Comments
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="border p-3 bg-light rounded">{{ $leave->comments }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            @if($leave->status === 'pending')
                                <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Application
                                </a>
                                
                                <form method="POST" action="{{ route('leaves.cancel', $leave) }}" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to cancel this leave application?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-ban"></i> Cancel Application
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Back to List
                            </a>

                            {{-- Admin Actions --}}
                            @can('approve-leaves')
                                @if($leave->status === 'pending')
                                    <div class="float-right">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                @endif
                            @endcan

                            {{-- Print Button --}}
                            <div class="float-right mr-2">
                                <button onclick="window.print()" class="btn btn-outline-secondary">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Approval Modal --}}
@can('approve-leaves')
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Approve Leave Application</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this leave application?</p>
                    <div class="form-group">
                        <label for="approve_comments">Approval Comments (Optional)</label>
                        <textarea name="comments" id="approve_comments" class="form-control" rows="3" 
                                  placeholder="Enter any comments for the approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Rejection Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('leaves.reject', $leave) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Leave Application</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this leave application?</p>
                    <div class="form-group">
                        <label for="reject_comments">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="comments" id="reject_comments" class="form-control" rows="3" 
                                  placeholder="Enter reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<style>
.card-header h4 {
    margin: 0;
    font-size: 1.1rem;
}

#leave-balance-info h5 {
    font-size: 1.5rem;
    margin-bottom: 0;
}

#leave-balance-info small {
    color: #6c757d;
    font-weight: 500;
}

.table td {
    padding: 0.5rem 0.75rem;
    border-top: none;
}

.table td:first-child {
    width: 40%;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.btn {
    border-radius: 4px;
}

.bg-light {
    background-color: #f8f9fa!important;
}

.alert {
    border-radius: 4px;
}

@media print {
    .card-tools,
    .card-footer,
    .btn {
        display: none !important;
    }
    
    .container-fluid {
        padding: 0;
    }
    
    .card {
        border: none;
        box-shadow: none;
    }
}
</style>

@endsection