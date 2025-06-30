{{-- resources/views/leaves/show.blade.php --}}
@extends('admin.admin_dashboard')
@section('admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i> Leave Application Details
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @if($leave->status === 'pending')
                            <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        @can('approve-leaves') {{-- Adjust permission as needed --}}
                            @if($leave->status === 'pending')
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{-- Status Badge --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="float-right">
                                @if($leave->status === 'pending')
                                    <span class="badge badge-warning badge-lg">
                                        <i class="fas fa-clock"></i> Pending Approval
                                    </span>
                                @elseif($leave->status === 'approved')
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                @elseif($leave->status === 'rejected')
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @elseif($leave->status === 'cancelled')
                                    <span class="badge badge-secondary badge-lg">
                                        <i class="fas fa-ban"></i> Cancelled
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Employee Information --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h4><i class="fas fa-user"></i> Employee Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Employee Number:</strong></td>
                                            <td>{{ $leave->employee->employee_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Full Name:</strong></td>
                                            <td>{{ $leave->employee->surname }}, {{ $leave->employee->first_name }} {{ $leave->employee->middle_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>MDA:</strong></td>
                                            <td>{{ $leave->employee->mda->mda ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                                            <td>{{ $leave->employee->department ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position:</strong></td>
                                            <td>{{ $leave->employee->position ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Leave Details --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h4><i class="fas fa-calendar-alt"></i> Leave Details</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Leave Type:</strong></td>
                                            <td>
                                                <span class="badge badge-info">{{ $leave->leaveType->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Start Date:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('D, M j, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>End Date:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('D, M j, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Days:</strong></td>
                                            <td>
                                                <span class="badge badge-primary">{{ $leave->total_days }} day(s)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Application Date:</strong></td>
                                            <td>{{ $leave->created_at->format('D, M j, Y g:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Leave Reason --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h4><i class="fas fa-comment-alt"></i> Leave Reason</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-justify">{{ $leave->reason }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Information --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h4><i class="fas fa-address-book"></i> Contact Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Contact Address:</strong></td>
                                            <td>{{ $leave->contact_address ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Phone:</strong></td>
                                            <td>{{ $leave->contact_phone ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Emergency Contact:</strong></td>
                                            <td>{{ $leave->emergency_contact ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Emergency Phone:</strong></td>
                                            <td>{{ $leave->emergency_phone ?? 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Supporting Document --}}
                    @if($leave->supporting_document)
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h4><i class="fas fa-paperclip"></i> Supporting Document</h4>
                                </div>
                                <div class="card-body">
                                    <div class="attachment-block clearfix">
                                        <img class="attachment-img" src="{{ asset('admin/dist/img/file-icon.png') }}" alt="Attachment">
                                        <div class="attachment-pushed">
                                            <h4 class="attachment-heading">
                                                <a href="{{ asset('storage/' . $leave->supporting_document) }}" target="_blank">
                                                    {{ basename($leave->supporting_document) }}
                                                </a>
                                            </h4>
                                            <div class="attachment-text">
                                                Uploaded: {{ $leave->created_at->format('M j, Y g:i A') }}
                                                <a href="{{ asset('storage/' . $leave->supporting_document) }}" target="_blank" class="btn btn-primary btn-sm pull-right">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Approval Information --}}
                    @if($leave->status !== 'pending')
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline {{ $leave->status === 'approved' ? 'card-success' : 'card-danger' }}">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-{{ $leave->status === 'approved' ? 'check-circle' : 'times-circle' }}"></i> 
                                        {{ ucfirst($leave->status) }} Information
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ ucfirst($leave->status) }} By:</strong></td>
                                            <td>{{ $leave->approvedBy->name ?? 'System' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ ucfirst($leave->status) }} Date:</strong></td>
                                            <td>{{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('D, M j, Y g:i A') : 'N/A' }}</td>
                                        </tr>
                                        @if($leave->approval_comments)
                                        <tr>
                                            <td><strong>Comments:</strong></td>
                                            <td>{{ $leave->approval_comments }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Timeline --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-dark">
                                <div class="card-header">
                                    <h4><i class="fas fa-history"></i> Timeline</h4>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span class="bg-info">{{ $leave->created_at->format('M j, Y') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-paper-plane bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fas fa-clock"></i> {{ $leave->created_at->format('g:i A') }}</span>
                                                <h3 class="timeline-header">Leave Application Submitted</h3>
                                                <div class="timeline-body">
                                                    {{ $leave->employee->first_name }} submitted a leave application for {{ $leave->total_days }} day(s).
                                                </div>
                                            </div>
                                        </div>

                                        @if($leave->status !== 'pending')
                                        <div class="time-label">
                                            <span class="bg-{{ $leave->status === 'approved' ? 'success' : 'danger' }}">
                                                {{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('M j, Y') : 'N/A' }}
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-{{ $leave->status === 'approved' ? 'check' : 'times' }} bg-{{ $leave->status === 'approved' ? 'success' : 'danger' }}"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="fas fa-clock"></i> 
                                                    {{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('g:i A') : 'N/A' }}
                                                </span>
                                                <h3 class="timeline-header">Leave Application {{ ucfirst($leave->status) }}</h3>
                                                <div class="timeline-body">
                                                    @if($leave->approval_comments)
                                                        <strong>Comments:</strong> {{ $leave->approval_comments }}
                                                    @else
                                                        Leave application was {{ $leave->status }}.
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Created:</strong> {{ $leave->created_at->format('D, M j, Y g:i A') }}<br>
                                <strong>Last Updated:</strong> {{ $leave->updated_at->format('D, M j, Y g:i A') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            @if($leave->status === 'pending' && auth()->user()->id === $leave->employee->user_id)
                                <form action="{{ route('leaves.cancel', $leave->id) }}" method="POST" style="display: inline-block;" 
                                      onsubmit="return confirm('Are you sure you want to cancel this leave application?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ban"></i> Cancel Application
                                    </button>
                                </form>
                            @endif
                            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                                <i class="fas fa-print"></i> Print
                            </button>
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
            <form action="{{ route('leaves.approve', $leave->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-check-circle"></i> Approve Leave Application
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this leave application?</p>
                    <div class="form-group">
                        <label for="approval_comments">Comments (Optional)</label>
                        <textarea name="approval_comments" id="approval_comments" class="form-control" rows="3" 
                                  placeholder="Add any comments about the approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
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
            <form action="{{ route('leaves.reject', $leave->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">
                        <i class="fas fa-times-circle"></i> Reject Leave Application
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this leave application?</p>
                    <div class="form-group">
                        <label for="rejection_comments">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="approval_comments" id="rejection_comments" class="form-control" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

{{-- Print Styles --}}
<style>
@media print {
    .card-tools, .modal, .btn, .timeline { display: none !important; }
    .card { border: 1px solid #ddd !important; }
    .badge-lg { font-size: 14px !important; }
}
.badge-lg { font-size: 1.1em; padding: 8px 12px; }
.timeline { margin-top: 20px; }
</style>

@endsection