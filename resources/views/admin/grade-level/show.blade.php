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

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4></h4>
                <a href="{{ route('grade-levels.index') }}" class="btn btn-secondary btn-sm">
                    <i class="lni lni-shift-left"></i> Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <p><strong>Grade Level:</strong> {{ $gradeLevel->level }}</p>
                    <p><strong>Total Employees:</strong> {{ $gradeLevel->employees->count() }}</p>
                </div>
            </div>
        </div>


        @if ($gradeLevel->employees->count() > 0)
            <div class="mt-4">
                <h5>List of Employees on Grade Level:  <span style="color: royalblue">{{ $gradeLevel->level }}</span></h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Employee No</th>
                                        <th>Name</th>
                                        <th>MDA</th>
                                        <th>Level</th>
                                        <th>Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gradeLevel->employees as $employee)
                                        <tr>
                                            <td>{{ $employee->employee_number }}</td>
                                            <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                                            <td>{{ $employee->mda->mda ?? 'N/A' }}</td>
                                            <td>GL {{ $employee->gradeLevel->level ?? 'N/A' }}</td>
                                            <td>Step {{ $employee->step->step ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
