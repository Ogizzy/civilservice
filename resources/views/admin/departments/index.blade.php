@extends('admin.admin_dashboard')
@section('admin')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    
   
    <div class="page-content">

         <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Departments, MDAs and HODs</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
             <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('departments.create') }}" class="btn btn-primary" title="Add Departments">
                        <i class="fadeIn animated bx bx-list-ul"></i> Add Departments
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr>
        <h6 class="mb-0 text-uppercase">Manage Departments</h6>
        
        <div class="page-content">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">

                    <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="{{ route('departments.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search roles...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>

                    <div class="table-responsive">
                        <table id="rolesTable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>Department</th>
                                    <th>MDA</th>
                                    <th>HOD</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departments as $index => $department)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $department->department_name }}</td>
                                        <td>{{ $department->mda?->mda }}</td>
                                        <td>{{ $department->hod?->surname }} {{ $department->hod?->first_name }}</td> 
                                        <td>  
                                             
                                            <a href="{{ route('departments.show', $department->id) }}"
                                                class="btn btn-info btn-sm"><i class="fadeIn animated bx bx-list-ul" title="View d Details"></i></a>
                                            <a href="{{ route('departments.edit', $department->id) }}"
                                                class="btn btn-warning btn-sm"><i class="lni lni-pencil-alt"></i></a>
                                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm delete-btn"><i class="lni lni-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $departments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#rolesTable').DataTable({
                        paging: false, 
                        searching: false, 
                        info: false,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-sm bg-secondary text-white'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm bg-success text-white'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-sm bg-info text-white'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-sm bg-danger text-white'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-sm bg-primary text-white'
                            }
                        ]
                    });

                    // Move buttons to custom div
                    table.buttons().container().appendTo('#exportButtons');
                });
            </script>
@endsection

