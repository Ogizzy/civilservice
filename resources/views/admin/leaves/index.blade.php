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

        {{-- ===================== HEADER ===================== --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="fas fa-calendar-alt"></i> Leave Management
                    </h1>
                    <p class="page-description">View and manage leave applications</p>
                </div>

                {{-- Disable Apply for Leave button after cutoff date --}}
                @php
                    $cutoffReached = now()->month == 11 && now()->day >= 28;
                @endphp

                @if (auth()->user()->employee)
                    <div class="col-md-4 text-md-end">

                        @if (!$cutoffReached)
                            <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus-circle"></i> Apply for Leave
                            </a>
                        @endif

                    </div>
                @endif
            </div>
        </div>

        {{-- ===================== STATS ===================== --}}
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
                    <div class="stats-label">Total</div>
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
                            {{-- <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled --}}
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

        {{-- ===================== TABLE ===================== --}}
        <div class="data-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
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

                        @forelse($leaves as $index => $leave)
                            <tr>
                                <td>{{ $leaves->firstItem() + $loop->index }}</td>

                                <td>
                                    <strong>{{ $leave->employee->surname }} {{ $leave->employee->first_name }}</strong>
                                    <div class="text-muted small">{{ $leave->employee->employee_number }}</div>
                                </td>

                                <td>{{ $leave->leaveType->name }}</td>

                                <td>
                                    {{ $leave->start_date->format('M d, Y') }} <br>
                                    <small class="text-muted">to {{ $leave->end_date->format('M d, Y') }}</small>
                                </td>

                                <td>{{ $leave->total_days }}</td>

                                <td>
                                    @if ($leave->status === 'pending')
                                        <span class="status-badge badge-pending">Pending</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="status-badge badge-approved">Approved</span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="status-badge badge-rejected">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($leave->supporting_document_url)
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="previewDocument('{{ asset('storage/' . $leave->supporting_document_url) }}')">
                                            Preview
                                        </button>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- VIEW --}}
                                        <a href="{{ route('leaves.show', $leave) }}" class="action-btn btn-primary">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        {{-- ===================== HOD ACTIONS ===================== --}}
                                        @if (
                                            $leave->status === 'pending' &&
                                                $leave->approval_stage === 'hod' &&
                                                auth()->user()->employee &&
                                                auth()->user()->employee->department &&
                                                auth()->user()->employee->department->hod_id === auth()->user()->employee->id)
                                            <button class="action-btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#approveModal{{ $leave->id }}">
                                                <i class="bx bx-check"></i>
                                            </button>

                                            <button class="action-btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $leave->id }}">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        @endif

                                        {{-- ===================== MDA HEAD ACTIONS ===================== --}}
                                        @if (
                                            $leave->status === 'pending' &&
                                                $leave->approval_stage === 'mda_head' &&
                                                auth()->user()->role->role === 'MDA Head' &&
                                                auth()->user()->mda_id === $leave->employee->mda_id)
                                            <button class="action-btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#mdaApproveModal{{ $leave->id }}">
                                                <i class="bx bx-check"></i>
                                            </button>

                                            <button class="action-btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#mdaRejectModal{{ $leave->id }}">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-calendar"></i>
                                    <p class="text-muted">No leave records found</p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                <div class="p-3">
                    {{ $leaves->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== MODALS ===================== --}}
    @foreach ($leaves as $leave)
        {{-- ===================== HOD APPROVE MODAL ===================== --}}
        @if (
            $leave->status === 'pending' &&
                $leave->approval_stage === 'hod' &&
                auth()->user()->employee &&
                auth()->user()->employee->department &&
                auth()->user()->employee->department->hod_id === auth()->user()->employee->id)
            <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('leaves.hod.approve', $leave) }}">
                            @csrf
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" style="color: white">
                                    <i class="bx bx-check-circle"></i> HOD Approval
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Employee:</strong>
                                    {{ $leave->employee->surname }} {{ $leave->employee->first_name }} -
                                    ({{ $leave->employee->employee_number }})
                                </p>

                                <p><strong>Leave Type:</strong>
                                    {{ $leave->leaveType->name }} ({{ $leave->total_days }} days)</p>

                                <div class="mb-3">
                                    <label class="form-label">HOD Remarks (Optional)</label>
                                    <textarea name="hod_remarks" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm"><i
                                        class="bx bx-check-circle"></i>Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===================== HOD REJECT MODAL ===================== --}}
            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('leaves.hod.reject', $leave) }}">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" style="color: white">
                                    <i class="bx bx-x-circle"></i> HOD Rejection
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Employee:</strong>
                                    {{ $leave->employee->surname }} {{ $leave->employee->first_name }} -
                                    ({{ $leave->employee->employee_number }})</p>

                                <p><strong>Leave Type:</strong>
                                    {{ $leave->leaveType->name }} ({{ $leave->total_days }} days)</p>

                                <div class="mb-3">
                                    <label class="form-label text-danger">Reason for Rejection *</label>
                                    <textarea name="hod_remarks" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i
                                        class="bx bx-x-circle"></i>Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- ===================== MDA APPROVE MODAL ===================== --}}
    @foreach ($leaves as $leave)
        @if (
            $leave->status === 'pending' &&
                $leave->approval_stage === 'mda_head' &&
                auth()->user()->role->role === 'MDA Head' &&
                $leave->employee->mda_id === auth()->user()->mda_id)
            <div class="modal fade" id="mdaApproveModal{{ $leave->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('leaves.mda.approve', $leave->id) }}">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <div class="modal-header bg-success" style="color: white">
                                <h5 class="modal-title" style="color: white">
                                    <i class="bx bx-check-circle"></i> MDA Head Approval
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $leave->employee->surname }}
                                    {{ $leave->employee->first_name }} - ({{ $leave->employee->employee_number }})</p>
                                <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }} ({{ $leave->total_days }}
                                    days)
                                </p>

                                <div class="mb-3">
                                    <label class="form-label">MDA Head Remarks (Optional)</label>
                                    <textarea name="mda_head_remarks" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm"><i
                                        class="bx bx-check-circle"></i>Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===================== MDA REJECT MODAL ===================== --}}
            <div class="modal fade" id="mdaRejectModal{{ $leave->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('leaves.mda.reject', $leave->id) }}">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" style="color: white">
                                    <i class="bx bx-x-circle"></i> MDA Head Rejection
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $leave->employee->surname }}
                                    {{ $leave->employee->first_name }} - ({{ $leave->employee->employee_number }})</p>
                                <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }} ({{ $leave->total_days }}
                                    days)
                                </p>

                                <div class="mb-3">
                                    <label class="form-label text-danger">Reason for Rejection *</label>
                                    <textarea name="mda_head_remarks" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i
                                        class="bx bx-x-circle"></i>Reject</button>
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
        // Blade file's script section
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
