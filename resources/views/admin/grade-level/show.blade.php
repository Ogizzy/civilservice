@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Grade Level Details</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Employees on Grade Level</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4></h4>
                <a href="{{ route('grade-levels.index') }}" class="btn btn-secondary btn-sm">
                    <i class="lni lni-shift-left"></i> Back
                </a>
            </div>

            <div class="card" >
                <div class="card-body">
                    <p><strong>Grade Level:</strong> {{ $gradeLevel->level }}</p>
                    <p><strong>Total Employees:</strong> {{ $gradeLevel->employees->count() }}</p>
                </div>
            </div>
        </div>

        @if ($gradeLevel->employees->count() > 0)
            <div class="mt-4">
                <h5>List of Employees on Grade Level: <span style="color: royalblue">{{ $gradeLevel->level }}</span></h5>
                <div class="card">
                    <div class="card-body">
                        <!-- Toolbar for search + export/print -->
                        <div class="d-flex justify-content-between mb-3">
                            <!-- Laravel Search -->
                            <form method="GET" action="{{ route('grade-levels.show', $gradeLevel->id) }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2"
                                    value="{{ request('search') }}" placeholder="Search employees...">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>

                            <div id="exportButtons"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="gradelevelsTable" class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Employee No</th>
                                        <th>Name</th>
                                        <th>MDA</th>
                                        <th>Level</th>
                                        <th>Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $index => $employee)
                                        <tr>
                                            <td>{{ $employees->firstItem() + $index }}</td>
                                            <td>{{ $employee->employee_number }}</td>
                                            <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                                            <td>{{ $employee->mda->mda ?? 'N/A' }}</td>
                                            <td>Grade Level {{ $employee->gradeLevel->level ?? 'N/A' }}</td>
                                            <td>Step {{ $employee->step->step ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No employees found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
        @endif
    </div>
    </div>
    </div>

    </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables but disable pagination/search
            let table = $('#gradelevelsTable').DataTable({
                paging: false, // ❌ Disable DataTables pagination
                searching: false, // ❌ Disable DataTables search (we use Laravel search)
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

            table.buttons().container().appendTo('#exportButtons');
        });
    </script>
@endsection
