@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

<div class="page-content">
    <!-- Hero Section -->
    <div class="card bg-gradient-blue-to-purple mb-4 shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="fw-bold text-white mb-1">Pay Group Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 bg-transparent small">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white opacity-75"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pay-groups.index') }}" class="text-white opacity-75">Pay Groups</a></li>
                            <li class="breadcrumb-item active text-white opacity-75" aria-current="page">{{ $payGroup->paygroup }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('pay-groups.index') }}" class="btn btn-light shadow-sm px-4 d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i> Back to Pay Groups
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0">
        <!-- Pay Group Info Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-white p-0">
                <div class="row g-0">
                    <div class="col-12">
                        <ul class="nav nav-tabs nav-fill border-bottom-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-3 px-4" data-bs-toggle="tab" data-bs-target="#info-tab" type="button" role="tab">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Group Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 px-4" data-bs-toggle="tab" data-bs-target="#employees-tab" type="button" role="tab">
                                    <i class="fas fa-users me-2 text-primary"></i> Employees 
                                    <span class="badge bg-primary rounded-pill ms-2">{{ $payGroup->employees->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 px-4" data-bs-toggle="tab" data-bs-target="#analytics-tab" type="button" role="tab">
                                    <i class="fas fa-chart-bar me-2 text-primary"></i> Analytics
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <!-- Info Tab -->
                    <div class="tab-pane fade show active" id="info-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 bg-light rounded-3">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-box bg-primary text-white rounded-3 me-3">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <h5 class="card-title mb-0">Group Details</h5>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-muted small d-block">Pay Group Name</label>
                                                    <h5 class="fw-bold mb-3">{{ $payGroup->paygroup }}</h5>
                                                </div>
                                                <div>
                                                    <label class="text-muted small d-block">Pay Group Code</label>
                                                    <h5 class="fw-bold">{{ $payGroup->paygroup_code }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 bg-light rounded-3">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-box bg-success text-white rounded-3 me-3">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <h5 class="card-title mb-0">Employee Statistics</h5>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center h-75">
                                                    <div class="text-center">
                                                        <div class="display-4 fw-bold text-success mb-1">{{ $payGroup->employees->count() }}</div>
                                                        <p class="text-muted mb-0">Total Employees</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mt-xl-0">
                                <div class="card h-100 border-0 bg-primary bg-opacity-10 rounded-3">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box bg-primary text-white rounded-3 me-3">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                            <h5 class="card-title mb-0">Quick Actions</h5>
                                        </div>
                                        <div class="list-group list-group-flush bg-transparent">
                                            <a href="{{ route('employees.create') }}" class="list-group-item list-group-item-action d-flex align-items-center border-0 bg-transparent px-0 py-2">
                                                <i class="fas fa-user-plus me-3 text-primary"></i>
                                                Add New Employee
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0 bg-transparent px-0 py-2">
                                                <i class="fas fa-print me-3 text-primary"></i>
                                                Print Pay Group Report
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0 bg-transparent px-0 py-2">
                                                <i class="fas fa-cog me-3 text-primary"></i>
                                                Manage Pay Group Settings
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employees Tab -->
                    <div class="tab-pane fade" id="employees-tab" role="tabpanel">
                        @if ($payGroup->employees->count() > 0)
                            <div class="card border-0 bg-transparent shadow-none">
                                <div class="card-header bg-light rounded-3 d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <h5 class="mb-0">Employees in {{ $payGroup->paygroup }}</h5>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control ps-0 border-start-0" id="searchBox" placeholder="Search employees...">
                                        </div>
                                        <button class="btn btn-outline-primary" id="refreshTable" title="Refresh">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0 pt-2">
                                    <div class="table-responsive">
                                        <table id="employeeTable" class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th class="ps-4">Employee No</th>
                                                    <th>Name</th>
                                                    <th>MDA</th>
                                                    <th>Grade Level</th>
                                                    <th>Step</th>
                                                    <th width="120" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payGroup->employees as $employee)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <span class="badge bg-light text-dark border">{{ $employee->employee_number }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                {{-- <div class="avatar avatar-sm me-3 bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"> --}}
                                                                    {{-- <span class="text-primary fw-medium">{{ substr($employee->first_name, 0, 1) }}{{ substr($employee->surname, 0, 1) }}</span> --}}
                                                                {{-- </div> --}}
                                                                <div>
                                                                    <h6 class="mb-0 fw-medium">{{ $employee->surname }} {{ $employee->first_name }}</h6>
                                                                    <small class="text-muted">Emp.No: {{ $employee->employee_number }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-building text-muted me-2 small"></i>
                                                                <span>{{ $employee->mda->mda ?? 'N/A' }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                                <i class="fas fa-layer-group me-1"></i>
                                                                GL-{{ $employee->gradeLevel->level ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                                <i class="fas fa-stairs me-1"></i>
                                                                Step-{{ $employee->step->step ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-vertical me-1"></i> Actions
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                                    <li>
                                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('employees.show', $employee->id) }}">
                                                                            <i class="fas fa-eye me-2 text-primary"></i> View Details
                                                                        </a>
                                                                    </li>
                                                                   
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small">Showing {{ $payGroup->employees->count() }} employees</span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" id="exportExcel">
                                            <i class="fas fa-file-excel me-1"></i> Excel
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" id="exportPdf">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="printTable">
                                            <i class="fas fa-print me-1"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon bg-light rounded-circle p-4 mb-3">
                                        <i class="fas fa-users-slash fa-2x text-muted"></i>
                                    </div>
                                    <h4>No Employees Found</h4>
                                    <p class="text-muted mb-4">This pay group doesn't have any employees assigned yet.</p>
                                    <a href="{{ route('employees.create') }}" class="btn btn-primary px-4">
                                        <i class="fas fa-user-plus me-2"></i> Add New Employee
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Analytics Tab -->
                    <div class="tab-pane fade" id="analytics-tab" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0"><i class="fas fa-chart-pie text-primary me-2"></i> Distribution by Grade Level</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center py-4">
                                            <div class="placeholder-chart bg-light rounded" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                                                <div class="text-muted">
                                                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                                    <p>Grade level distribution chart</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0"><i class="fas fa-chart-column text-primary me-2"></i> Distribution by MDA</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center py-4">
                                            <div class="placeholder-chart bg-light rounded" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                                                <div class="text-muted">
                                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                                    <p>MDA distribution chart</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with advanced features
        var table = $('#employeeTable').DataTable({
            lengthChange: false,
            searching: true,
            paging: true,
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy me-1"></i> Copy',
                    className: 'btn btn-sm btn-outline-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel me-1"></i> Excel',
                    className: 'btn btn-sm btn-outline-success',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                    className: 'btn btn-sm btn-outline-danger',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print me-1"></i> Print',
                    className: 'btn btn-sm btn-outline-primary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });

        // Connect custom buttons to DataTables buttons
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });
        
        $('#exportPdf').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });
        
        $('#printTable').on('click', function() {
            table.button('.buttons-print').trigger();
        });

        // Add refresh functionality
        $('#refreshTable').on('click', function() {
            $(this).addClass('fa-spin');
            setTimeout(function() {
                location.reload();
            }, 600);
        });

        // Custom search box
        $('#searchBox').on('keyup', function() {
            table.search($(this).val()).draw();
        });
    });
</script>

<style>
    /* Custom styling */
    .bg-gradient-blue-to-purple {
        background: linear-gradient(135deg, #4e73df 0%, #7c47d1 100%);
    }
    
    .avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .icon-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .bg-primary-subtle {
        background-color: rgba(78, 115, 223, 0.15);
    }
    
    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        border-color: #bac8f3;
    }
    
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: #4e73df;
        border-bottom-color: rgba(78, 115, 223, 0.5);
    }
    
    .nav-tabs .nav-link.active {
        color: #4e73df;
        background-color: transparent;
        border-bottom-color: #4e73df;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .dropdown-menu {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.25rem;
    }
    
    .dropdown-item:hover {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    .card {
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .card-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-pane {
        animation: fadeIn 0.3s ease-out;
    }
    
    .card {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>
@endsection