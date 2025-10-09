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
                @if (auth()->user()->role->role != 'Employee')
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
                            <span class="employee-name">{{ $employee->surname }} {{ $employee->first_name }}
                                {{ $employee->middle_name }}</span>
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transferTable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>From MDA</th>
                                    <th>To MDA</th>
                                    <th>Effective Date</th>
                                    <th>Document</th>
                                    <th>Processed By</th>
                                    @if (auth()->user()->role->role != 'Employee')
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
                                            @if ($transfer->document && $transfer->document->document)
                                                <a href="{{ $transfer->document->document }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="View Document">
                                                    <i class="lni lni-eye"></i> View
                                                </a>
                                            @else
                                                <span class="text-muted">No Document</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <span class="user-name">{{ $transfer->user->surname ?? 'N/A' }}
                                                    {{ $transfer->user->first_name ?? 'N/A' }}</span><br>
                                                <small
                                                    class="text-muted">{{ $transfer->created_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        @if (auth()->user()->role->role != 'Employee')
                                            <td>
                                                <div class="action-buttons">
                                                    <form
                                                        action="{{ route('employees.transfers.destroy', [$employee->id, $transfer->id]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                       
                                                            <button class="btn btn-danger btn-sm delete-btn"><i class="bx bxs-trash" title="Delete This Transfer?"></i></button>
                                                        
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role->role != 'Employee' ? '6' : '5' }}"
                                            class="text-center text-muted py-4">
                                            No transfer records found for this employee
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($transfers->hasPages())
                        <div class="mt-3">
                            {{ $transfers->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables but disable pagination/search
            let table = $('#transferTable').DataTable({
                paging: false, // ❌ Disable DataTables pagination
                searching: false, // ❌ Disable DataTables search (I use Laravel search)
                info: false,
                ordering: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-sm btn-info'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-danger'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-primary'
                    }
                ]
            });

            // Move buttons to custom div
            table.buttons().container().appendTo('#exportButtons');
        });
    </script>
@endsection
