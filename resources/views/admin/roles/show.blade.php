@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">
                <i class="fas fa-user-shield text-muted"></i> Role Management
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bx bx-home-alt text-muted"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('roles.index') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-3">
                    <i class="fas fa-arrow-left me-1"></i> Back to Roles
                </a>
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary px-3">
                    <i class="fas fa-edit me-1"></i> Edit Permissions
                </a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom py-3">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-key me-2"></i> 
                        Permissions for: <span class="text-primary">{{ $role->role }}</span>
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle text-primary"></i>
                            <div>
                                <p class="mb-0">Below are all permissions assigned to this role. You can edit permissions using the button above.</p>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="permissionsTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="40%">
                                        <i class="fas fa-tasks me-2"></i>Feature
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-plus-circle me-2"></i>Create
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </th>
                                    <th class="text-center pe-4">
                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role->userPermissions as $perm)
                                    <tr class="border-top">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-wrapper bg-light p-2 rounded">
                                                    <i class="fas fa-{{ $perm->feature->icon ?? 'cog' }} text-muted"></i>
                                                </div>
                                                <span class="fw-medium">{{ $perm->feature->feature }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" 
                                                    {{ $perm->can_create ? 'checked' : '' }} disabled
                                                    style="width: 3em; height: 1.5em;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" 
                                                    {{ $perm->can_edit ? 'checked' : '' }} disabled
                                                    style="width: 3em; height: 1.5em;">
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" 
                                                    {{ $perm->can_delete ? 'checked' : '' }} disabled
                                                    style="width: 3em; height: 1.5em;">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8fafc;
        }
        .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.03);
            border-radius: 0.5rem;
        }
        .table th {
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            background-color: #f8fafc;
        }
        .table td {
            vertical-align: middle;
            border-top: 1px solid #e2e8f0;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
        .icon-wrapper {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
        .form-switch .form-check-input {
            cursor: default;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
            border: 1px solid #e2e8f0 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
            border: 1px solid #e2e8f0 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
        }
        .dataTables_wrapper .dt-buttons .btn {
            border-radius: 6px !important;
            margin-right: 8px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background-color: #fff;
        }
    </style>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#permissionsTable').DataTable({
                dom: '<"top"<"row"<"col-md-6"B><"col-md-6"f>>>rt<"bottom"<"row"<"col-md-6"l><"col-md-6"p>>>',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-light',
                        text: '<i class="far fa-copy me-1"></i>Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-light',
                        text: '<i class="far fa-file-excel me-1"></i>Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-light',
                        text: '<i class="far fa-file-pdf me-1"></i>PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-light',
                        text: '<i class="fas fa-print me-1"></i>Print'
                    }
                ],
                responsive: true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                language: {
                    search: "",
                    searchPlaceholder: "Search permissions...",
                    lengthMenu: "Show _MENU_ permissions",
                    info: "Showing _START_ to _END_ of _TOTAL_ permissions",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                order: [[0, 'asc']],
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control-sm');
                    $('.dataTables_length select').addClass('form-select-sm');
                }
            });
        });
    </script>
@endsection