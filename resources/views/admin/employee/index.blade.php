@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

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
        </div>
        <!--end breadcrumb-->

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="row align-items-center justify-content-between mb-3">
                <div class="col-md-6">
                    <div class="dataTables_length" id="example2_length">
                        <label>
                            Show 
                            <select name="example2_length" class="form-select form-select-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries
                        </label>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add Employee
                    </a>
                </div>
            </div>

            <hr>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="employeeTable" class="table table-striped table-bordered">
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
                                        <td>{{ $og + 1 }}</td>
                                        <td>{{ $employee->employee_number }}</td>
                                        <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                                        <td>GL{{ $employee->gradeLevel->level ?? 'N/A' }}/Step {{ $employee->step->step ?? 'N/A' }}</td>
                                        
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- View Profile -->
                                                <a href="{{ route('employees.show', $employee->id) }}"
                                                    class="btn btn-sm btn-info" title="View Profile" data-bs-toggle="tooltip">
                                                    <i class="fadeIn animated bx bx-list-ul"></i>
                                                </a>
                
                                                <!-- Edit -->
                                                {{-- <a href="{{ route('employees.edit', $employee->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="tooltip">
                                                    <i class="fadeIn animated bx bx-edit"></i>
                                                </a> --}}
                
                                                <!-- Action Dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" 
                                                            id="actionDropdown{{ $employee->id }}" data-bs-toggle="dropdown" 
                                                            aria-expanded="false" title="More actions">
                                                        <i class="bx bx-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $employee->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.promotions.create', $employee->id) }}">
                                                                <i class="bx bx-chevrons-up text-success"></i>Promote Employee
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.transfers.create', $employee->id) }}">
                                                                 <i class="bx bx-transfer-alt text-primary"></i>Transfer Employee
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.transfers.index', $employee->id) }}">
                                                                <i class="bx bx-history text-secondary"></i> Transfer History
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.documents.index', $employee->id) }}">
                                                                <i class="bx bx-file text-info"></i> Employee Documents
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.commendations.create', $employee->id) }}">
                                                               <i class="bx bx-trophy text-success"></i> Commendation/Award
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('employees.queries.create', $employee->id) }}">
                                                                 <i class="bx bx-error-circle text-warning"></i> Query/Miscounduct
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
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Initialize DataTable with enhanced features
                var table = $('#employeeTable').DataTable({
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'btn btn-sm btn-outline-secondary'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-outline-success'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-outline-danger'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-outline-primary'
                        }
                    ],
                    initComplete: function() {
                        // Add custom search input
                        this.api().columns().every(function() {
                            var column = this;
                            var header = $(column.header());
                            
                            // Only add search to specific columns if needed
                            if (header.index() === 2) { // Name column
                                var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search names..." />')
                                    .appendTo(header)
                                    .on('keyup change', function() {
                                        column.search(this.value).draw();
                                    });
                            }
                        });
                    }
                });
                
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Add buttons to the DOM
                table.buttons().container()
                    .appendTo('#employeeTable_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection