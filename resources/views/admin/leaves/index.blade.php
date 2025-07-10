@extends('admin.admin_dashboard')
@section('admin')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Light color scheme */
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --text-color: #495057;
            --text-muted: #6c757d;
        }

        /* Base styles */
        body {
            color: var(--text-color);
            background-color: #f8fafc;
        }

        /* Header section */
        .page-header {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .page-description {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Stats cards */
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 4px solid transparent;
        }

        .stats-card.pending {
            border-left-color: var(--warning-color);
        }

        .stats-card.approved {
            border-left-color: var(--success-color);
        }

        .stats-card.rejected {
            border-left-color: var(--danger-color);
        }

        .stats-card.total {
            border-left-color: var(--primary-color);
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Filter section */
        .filter-section {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filter-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            display: block;
        }

        /* Table styles */
        .data-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background-color: #f1f5f9;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status badges */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }

        /* Action buttons */
        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        /* Employee info */
        .employee-name {
            font-weight: 500;
        }

        .employee-number {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Document indicator */
        .document-indicator {
            font-size: 0.75rem;
            color: var(--primary-color);
            margin-top: 0.25rem;
        }

        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 2.5rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* Modal styles */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Form elements */
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 111, 165, 0.1);
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
        }
    </style>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="fas fa-calendar-alt"></i> Leave Management
                    </h1>
                    <p class="page-description">View and manage leave applications</p>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                        <i class="fadeIn animated bx bx-plus-circle"></i> Apply for Leave
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card pending">
                    <div class="stats-number text-warning">{{ $leaves->where('status', 'pending')->count() }}</div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card approved">
                    <div class="stats-number text-success">{{ $leaves->where('status', 'approved')->count() }}</div>
                    <div class="stats-label">Approved</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card rejected">
                    <div class="stats-number text-danger">{{ $leaves->where('status', 'rejected')->count() }}</div>
                    <div class="stats-label">Rejected</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card total">
                    <div class="stats-number text-primary">{{ $leaves->count() }}</div>
                    <div class="stats-label">Total Applications</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('leaves.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label">Leave Type</label>
                        <select name="leave_type_id" class="form-select">
                            <option value="">All Types</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Main Data Table -->
        <div class="data-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>
                                    <div class="employee-name">{{ $leave->employee->surname }},
                                        {{ $leave->employee->first_name }}</div>
                                    <div class="employee-number">{{ $leave->employee->employee_number }}</div>
                                </td>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>
                                    <div>{{ $leave->start_date->format('M d, Y') }}</div>
                                    <div class="text-muted small">to {{ $leave->end_date->format('M d, Y') }}</div>
                                </td>
                                <td>{{ $leave->total_days }}</td>
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
                                            <i class="lni lni-files"></i> Document
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $documentPath = 'storage/' . $leave->supporting_document_url;
                                        $fullPath = public_path($documentPath);
                                    @endphp

                                    @if (!empty($leave->supporting_document_url) && file_exists(public_path('storage/' . $leave->supporting_document_url)))
                                        <button
                                            onclick="previewDocument('{{ asset('storage/' . $leave->supporting_document_url) }}')"
                                            class="btn btn-sm btn-outline-primary" title="Preview Document">
                                            <i class="fadeIn animated bx bx-show"></i> Preview
                                        </button>

                                        <a href="{{ asset('storage/' . $leave->supporting_document_url) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            download="{{ $leave->supporting_document_name }}">
                                            <i class="lni lni-download"></i> 
                                        </a>
                                    @elseif (!empty($leave->supporting_document_name))
                                        <span class="text-muted">
                                            {{ $leave->supporting_document_name }} <br>
                                            <small class="text-warning">File not found or missing link</small>
                                        </span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif

                                </td>


                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('leaves.show', $leave) }}" class="action-btn btn-primary"
                                            title="View Leave Details">
                                            <i class="lni lni-radio-button"></i>
                                        </a>

                                        @if ($leave->status == 'pending' && (Auth::user()->employee && Auth::user()->employee->id == $leave->employee_id))
                                            <a href="{{ route('leaves.edit', $leave) }}" class="action-btn btn-warning"
                                                title="Edit">
                                                <i class="fadeIn animated bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="action-btn btn-secondary"
                                                data-bs-toggle="modal" data-bs-target="#cancelModal{{ $leave->id }}"
                                                title="Cancel">
                                                <i class="fadeIn animated bx bx-time"></i>
                                            </button>
                                        @endif

                                        @if ($leave->status == 'pending' && Auth::user()->canApproveLeaves())
                                            <button type="button" class="action-btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#approveModal{{ $leave->id }}" title="Approve">
                                                <i class="fadeIn animated bx bx-check"></i>
                                            </button>
                                            <button type="button" class="action-btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $leave->id }}" title="Reject">
                                                <i class="fadeIn animated bx bx-time"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fadeIn animated bx bx-calendar"></i>
                                        </div>
                                        <h5>No leave applications found</h5>
                                        <p class="text-muted">Try adjusting your filters or create a new application</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($leaves->hasPages())
                    <div class="p-3">
                        {{ $leaves->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Include your existing modal code here without changes --}}
        @foreach ($leaves as $leave)
            @if ($leave->status == 'pending' && Auth::user()->canManageOwnLeave($leave))
                <!-- Cancel Modal -->
                <div class="modal fade" id="cancelModal{{ $leave->id }}" tabindex="-1"
                    aria-labelledby="cancelModalLabel{{ $leave->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('leaves.cancel', $leave) }}">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="cancelModalLabel{{ $leave->id }}"
                                        style="color: whitesmoke">
                                        <i class="lni lni-alarm-clock me-2"></i>Cancel Leave Application
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                        <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                        <div class="col-sm-8">
                                            {{ $leave->start_date->format('M d, Y') }} to
                                            {{ $leave->end_date->format('M d, Y') }}
                                            <span class="badge bg-primary ms-2">{{ $leave->total_days }} days</span>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="fadeIn animated bx bx-exclamation-triangle me-2"></i>
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
                                        <i class="fadeIn animated bx bx-chevrons-left me-1"></i> Keep Application
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fadeIn animated bx bx-time me-1"></i> Cancel Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if ($leave->status == 'pending' && Auth::user()->canApproveLeaves())
                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1"
                    aria-labelledby="approveModalLabel{{ $leave->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="approveModalLabel{{ $leave->id }}"
                                        style="color: whitesmoke">
                                        <i class="fadeIn animated bx bx-check-circle"></i> Approve Leave Application
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Employee:</div>
                                        <div class="col-sm-8">{{ $leave->employee->surname }},
                                            {{ $leave->employee->first_name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                        <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                        <div class="col-sm-8">
                                            {{ $leave->start_date->format('M d, Y') }} to
                                            {{ $leave->end_date->format('M d, Y') }}
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
                                        <i class="fadeIn animated bx bx-check-circle"></i> Approve Leave
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1"
                    aria-labelledby="rejectModalLabel{{ $leave->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('leaves.reject', $leave) }}">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="rejectModalLabel{{ $leave->id }}"
                                        style="color: whitesmoke">
                                        <i class="fadeIn animated bx bx-time"></i> Reject Leave Application
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Employee:</div>
                                        <div class="col-sm-8">{{ $leave->employee->surname }},
                                            {{ $leave->employee->first_name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-semibold text-muted">Leave Type:</div>
                                        <div class="col-sm-8">{{ $leave->leaveType->name }}</div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-4 fw-semibold text-muted">Duration:</div>
                                        <div class="col-sm-8">
                                            {{ $leave->start_date->format('M d, Y') }} to
                                            {{ $leave->end_date->format('M d, Y') }}
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
                                        <i class="fadeIn animated bx bx-time"></i> Reject Leave
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Document Preview Modal -->
        <div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: white">Supporting Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="documentPreview"></div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="downloadDocument" class="btn btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // In your blade file's script section
            function previewDocument(url) {
                const extension = url.split('.').pop().toLowerCase();
                const previewDiv = $('#documentPreview');
                const downloadBtn = $('#downloadDocument');

                downloadBtn.attr('href', url);

                if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                    previewDiv.html(`<img src="${url}" class="img-fluid" alt="Document Preview">`);
                } else if (extension === 'pdf') {
                    previewDiv.html(`
            <iframe src="${url}" 
                    style="width:100%; height:500px;" 
                    frameborder="0"></iframe>
        `);
                } else {
                    previewDiv.html(`
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Document cannot be previewed. Please download to view.
            </div>
        `);
                }

                $('#documentModal').modal('show');
            }
        </script>
    @endsection
