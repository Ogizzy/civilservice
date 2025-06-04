@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!-- Header Section -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Transfer Management</h3>
                <ul class="breadcrumb">
                    {{-- <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li> --}}
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Employee</a></li>
                    <li class="breadcrumb-item active">Transfer History</li>
                </ul>
            </div>
            @if(auth()->user()->role->role != 'Employee')
            <div class="col-auto">
                <a href="{{ route('employees.transfers.create', $employee->id) }}" class="btn btn-primary">
                    <i class="lni lni-circle-plus"></i> New Transfer
                </a>
            </div>
            @endif
        </div>
    </div>
    <!-- End Header Section -->

    <div class="container-fluid">
        <!-- Employee Info Card -->
        <div class="card employee-card mb-4">
            <div class="card-body">
                <div class="employee-info">
                    <h5 class="card-title">Transfer History For:</h5>
                    <div class="employee-details">
                        <span class="employee-name">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span>
                        <span class="employee-id"> | Employee No: {{ $employee->employee_number }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Transfer Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Transfer Records</h5>
                <div class="card-actions">
                    <button class="btn btn-sm btn-outline-secondary" id="printBtn">
                        <i class="lni lni-printer"></i> Print
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="exportBtn">
                        <i class="lni lni-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="transferTable" class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>From MDA</th>
                                <th>To MDA</th>
                                <th>Effective Date</th>
                                <th>Document</th>
                                <th>Processed By</th>
                                @if(auth()->user()->role->role != 'Employee')
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transfers as $transfer)
                            <tr>
                                <td>
                                    <div class="mda-info">
                                        <span class="mda-name">{{ $transfer->previousMda->mda ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mda-info">
                                        <span class="mda-name">{{ $transfer->currentMda->mda ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        {{ \Carbon\Carbon::parse($transfer->effective_date)->format('d M, Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($transfer->document && $transfer->document->document)
                                    <a href="{{ $transfer->document->document }}" target="_blank" class="btn btn-sm btn-outline-primary" title="View Document">
                                        <i class="lni lni-eye"></i> View
                                    </a>
                                    @else
                                    <span class="text-muted">No Document</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">{{ $transfer->user->surname ?? 'N/A' }} {{ $transfer->user->first_name ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $transfer->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                @if(auth()->user()->role->role != 'Employee')
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('employees.transfers.destroy', [$employee->id, $transfer->id]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-btn" title="Delete Transfer" onclick="return confirm('Are you sure you want to delete this transfer record?')">
                                                <i class="lni lni-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role->role != 'Employee' ? '6' : '5' }}" class="text-center text-muted py-4">
                                    No transfer records found for this employee
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transfers->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $transfers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#transferTable').DataTable({
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="lni lni-printer"></i> Print',
                    className: 'btn btn-outline-secondary'
                },
                {
                    extend: 'excel',
                    text: '<i class="lni lni-download"></i> Excel',
                    className: 'btn btn-outline-secondary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="lni lni-file-pdf"></i> PDF',
                    className: 'btn btn-outline-secondary'
                }
            ]
        });

        // Custom button handlers
        $('#printBtn').click(function() {
            $('#transferTable').DataTable().button('.buttons-print').trigger();
        });

        $('#exportBtn').click(function() {
            $('#transferTable').DataTable().button('.buttons-excel').trigger();
        });

        // Add confirmation for delete actions
        $('.delete-btn').click(function(e) {
            if(!confirm('Are you sure you want to delete this transfer record?')) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    .employee-card {
        border-left: 4px solid #5D78FF;
    }
    
    .employee-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .employee-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2E384D;
    }
    
    .employee-id {
        font-size: 0.85rem;
        color: #6B7A99;
        background: #F5F6FA;
        padding: 3px 8px;
        border-radius: 4px;
    }
    
    .mda-info {
        display: flex;
        flex-direction: column;
    }
    
    .mda-name {
        font-weight: 500;
    }
    
    .date-badge {
        background: #F0F7FF;
        color: #2E5BFF;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .table-hover tbody tr:hover {
        background-color: #F9FAFC;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #fff;
        border-bottom: 1px solid #E9EDF4;
    }
    
    .card-actions {
        display: flex;
        gap: 10px;
    }
</style>

@endsection