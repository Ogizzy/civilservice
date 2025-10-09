@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Documents</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Employee Documents</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

    <div class="container">
        @if (auth()->user()->role->role === 'Employee')
        <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Documents for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>
        <a href="{{ route('employee.dashboard') }}" class="btn btn-primary btn-sm">
            <i class="fadeIn animated bx bx-chevrons-left"></i> Go Back
        </a>
    </div>

        @else
        <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Documents for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>
        <a href="{{ route('employees.documents.create', $employee->id) }}" class="btn btn-primary btn-sm">
            <i class="lni lni-upload"></i> Upload Document
        </a>
    </div>
    @endif
 
    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <hr>
    <div class="card">
        <div class="card-body">

            <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="{{ route('employees.documents.index', $employee->id) }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search employees...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>
                    
            <div class="table-responsive">
                <table id="employeesTable" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Type</th>
                        <th>Uploaded On</th>
                        <th>Uploaded By</th>
                        <th>Document</th>
                        @if(auth()->user()->role->role != 'Employee')
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $doc)
                        <tr>
                            <td>{{ $doc->document_type }}</td>
                            <td>{{ $doc->created_at->format('d M, Y') }}</td>
                            <td>{{ $doc->user->surname ?? 'N/A' }} {{ $doc->user->first_name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $doc->document) }}" target="_blank" class="btn btn-sm btn-info"><i class="lni lni-eye" title="View This Document"></i> View Document</a>
                            </td>

                             @if(auth()->user()->role->role != 'Employee')
                            <td>
                                <a href="{{ route('employees.documents.edit', [$employee->id, $doc->id]) }}" class="btn btn-warning btn-sm"><i class="bx bxs-edit" title="Edit This Document"></i></a>
                                <form action="{{ route('employees.documents.destroy', [$employee->id, $doc->id]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm delete-btn"><i class="bx bxs-trash" title="Delete This Document"></i></button>
                                </form>
                            </td>
                             @endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No documents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $documents->links() }}</div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>


            <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#employeesTable').DataTable({
                        paging: false, // ❌ Disable DataTables pagination
                        searching: false, // ❌ Disable DataTables search (we use Laravel search)
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
