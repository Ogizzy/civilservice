@extends('admin.admin_dashboard')
@section('admin')

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .leave-type-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        }

        .leave-type-header {
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }

        .leave-type-title {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.75rem;
        }

        .btn-primary {
            border-radius: 8px;
        }

        .table {
            font-size: 0.95rem;
        }

        .leave-type-table th {
            background-color: #f1f5f9;
            color: #34495e;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .leave-type-table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 0.4rem 0.75rem;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .badge-active {
            background-color: #e6fffa;
            color: #2f855a;
        }

        .badge-inactive {
            background-color: #fff5f5;
            color: #c53030;
        }

        .action-btn {
            width: 40px;
            height: 34px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 4px;
            background-color: #f1f7f9;
            border: none;
        }

        .action-btn:hover {
            background-color: #e2e8f0;
        }

        .pagination {
            justify-content: center;
        }

        .modal-header,
        .modal-footer {
            background-color: #f8fafc;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control,
        .form-check-input {
            border-radius: 8px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="leave-type-card">
                    <div class="d-flex justify-content-between align-items-center leave-type-header">

                        <div class="d-flex justify-content-between align-items-center leave-type-header flex-wrap gap-2">
                            <h2 class="leave-type-title mb-0">
                                <i class="fadeIn animated bx bx-calendar me-2"></i>Leave Types Management
                            </h2>

                            <form method="GET" action="" class="d-flex gap-2 align-items-center">
                                <input type="text" name="search" class="form-control" placeholder="Search by name"
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="bx bx-search"></i>
                                </button>
                            </form>

                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaveTypeModal">
                                <i class="fadeIn animated bx bx-plus-circle"></i>Create New
                            </button>
                        </div>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered leave-type-table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Max Days/Year</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveTypes as $type)
                                        <tr>
                                            <td><strong>{{ $type->code }}</strong></td>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $type->description ?? 'N/A' }}</td>
                                            <td>{{ $type->max_days_per_year }}</td>
                                            <td>
                                                <span
                                                    class="status-badge {{ $type->is_active ? 'badge-active' : 'badge-inactive' }}">
                                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <button class="action-btn" data-bs-toggle="modal"
                                                        data-bs-target="#editLeaveTypeModal{{ $type->id }}"
                                                        title="Edit Leave Type">
                                                        <i class="fadeIn animated bx bx-edit"></i>
                                                    </button>

                                                    <form action="{{ route('leave-types.toggle-status', $type->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn"
                                                            title="{{ $type->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class="bx {{ $type->is_active ? 'bx-toggle-right text-success' : 'bx-toggle-left text-muted' }} fs-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editLeaveTypeModal{{ $type->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('leave-types.update', $type->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Leave Type</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Code</label>
                                                                <input type="text" name="code" class="form-control"
                                                                    value="{{ $type->code }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ $type->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Description</label>
                                                                <textarea name="description" class="form-control" rows="3">{{ $type->description }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Max Days Per Year</label>
                                                                <input type="number" name="max_days_per_year"
                                                                    class="form-control"
                                                                    value="{{ $type->max_days_per_year }}" required>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="is_active" id="is_active_{{ $type->id }}"
                                                                    value="1" {{ $type->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_active_{{ $type->id }}">Active</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $leaveTypes->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createLeaveTypeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('leave-types.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Leave Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" required
                                    placeholder="e.g. ML, AL, SL">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="e.g. Maternity Leave">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Leave type description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Max Days Per Year <span class="text-danger">*</span></label>
                                <input type="number" name="max_days_per_year" min="1" max="365"
                                    class="form-control" placeholder="Minimum 1 Day | Maximum 365 Days" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active_new">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Leave Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
