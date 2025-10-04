@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Employees</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">List of Employees</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add Employee
                        </a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <h6 class="mb-0 text-uppercase">Employees Table</h6>

            <hr>

           <div class="card">
    <div class="card-body">

        <!-- Toolbar for search + export/print -->
        <div class="d-flex justify-content-between mb-3">
            <!-- Laravel Search -->
            <form method="GET" action="{{ route('employees.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       value="{{ request('search') }}" placeholder="Search employees...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Export/Print Buttons -->
            <div id="exportButtons"></div>
        </div>

        <div class="table-responsive">
            <table id="employeesTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Employee No.</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $og => $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $og }}</td>
                            <td>{{ $employee->employee_number }}</td>
                            <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                            <td>
                                GL{{ $employee->gradeLevel->level ?? 'N/A' }}/Step {{ $employee->step->step ?? 'N/A' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- View Profile -->
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                       class="btn btn-sm btn-info" title="View Profile" data-bs-toggle="tooltip">
                                        <i class="fadeIn animated bx bx-list-ul"></i>
                                    </a>

                                    <!-- Action Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                id="actionDropdown{{ $employee->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false" title="More actions">
                                            <i class="bx bx-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu"
                                            aria-labelledby="actionDropdown{{ $employee->id }}">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.promotions.create', $employee->id) }}">
                                                    <i class="bx bx-chevrons-up text-success"></i> Promote Employee
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.transfers.create', $employee->id) }}">
                                                    <i class="bx bx-transfer-alt text-primary"></i> Transfer Employee
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.transfers.index', $employee->id) }}">
                                                    <i class="bx bx-history text-secondary"></i> Transfer History
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.documents.index', $employee->id) }}">
                                                    <i class="bx bx-file text-info"></i> Employee Documents
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.commendations.create', $employee->id) }}">
                                                    <i class="bx bx-trophy text-success"></i> Commendation/Award
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('employees.queries.create', $employee->id) }}">
                                                    <i class="bx bx-error-circle text-warning"></i> Query/Misconduct
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTables but disable pagination/search
    let table = $('#employeesTable').DataTable({
        paging: false,      // ❌ Disable DataTables pagination
        searching: false,   // ❌ Disable DataTables search (we use Laravel search)
        info: false,
        ordering: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-sm btn-secondary' },
            { extend: 'excel', className: 'btn btn-sm btn-success' },
            { extend: 'csv', className: 'btn btn-sm btn-info' },
            { extend: 'pdf', className: 'btn btn-sm btn-danger' },
            { extend: 'print', className: 'btn btn-sm btn-primary' }
        ]
    });

    // Move buttons to custom div
    table.buttons().container().appendTo('#exportButtons');
});
</script>


        @endsection
