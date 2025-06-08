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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
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
            

            <div class="row align-items-center justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        Add Employee
                    </a>
                </div>
            </div>

            <hr>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>Employee No.</th>
                                    <th>Name</th>
                                    {{-- <th>MDA</th> --}}
                                    {{-- <th>Pay Group</th> --}}
                                    <th>Grade Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $og => $employee)
                                    <tr>
                                        <td>{{ $og + 1 }}</td>
                                        <td>{{ $employee->employee_number }}</td>
                                        <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                                        {{-- <td>{{ $employee->mda->mda ?? 'N/A' }}</td> --}}
                                        {{-- <td>{{ $employee->paygroup->paygroup ?? 'N/A' }}</td> --}}
                                        <td>GL{{ $employee->gradeLevel->level ?? 'N/A' }}/Step {{ $employee->step->step ?? 'N/A' }}
                                        </td>
                                        
                                        <td>
                                            <!-- View Profile -->
                                            <a href="{{ route('employees.show', $employee->id) }}"
                                                class="btn btn-sm btn-info" title="View Employee">
                                                <i class="fadeIn animated bx bx-list-ul"></i>
                                            </a>
            
                                            <!-- Edit -->
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fadeIn animated bx bx-edit"></i>
                                            </a>
            
                                            <!-- Promote -->
                                            <a href="{{ route('employees.promotions.create', $employee->id) }}"
                                                class="btn btn-sm btn-success" title="Promote Employee">
                                                <i class="fadeIn animated bx bx-chevrons-up"></i>
                                            </a>
            
                                            <!-- Transfer History -->
                                            <a href="{{ route('employees.transfers.index', $employee->id) }}" class="btn btn-sm btn-secondary" title="Transfer History">
                                                <i class="fadeIn animated bx bx-refresh"></i>
                                            </a>
                                            
                                            <a href="{{ route('employees.transfers.create', $employee->id) }}" class="btn btn-sm btn-primary" title="Initiate Transfer">
                                                <i class="fadeIn animated bx bx-transfer-alt"></i>
                                            </a>
            
                                            <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="lni lni-files" title="Upload Documents"></i>
                                            </a>
                                            
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
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#example2')) {
                    $('#example2').DataTable().destroy();
                }
                
                // Initialize with minimal config to ensure search appears
                var table = $('#example2').DataTable({
                    searching: true,
                    lengthChange: true,
                    info: true,
                    paging: true,
                    ordering: true,
                    // Use simple dom layout
                    dom: 'lfrtip',
                    buttons: ['copy', 'excel', 'pdf', 'print']
                });
                
                // Force refresh the search box after initialization
                setTimeout(function() {
                    $('#example2_wrapper .dataTables_filter').show();
                    $('#example2_wrapper .dataTables_filter input').attr('placeholder', 'Search employees...');
                }, 100);
            });
        </script>
    @endsection
