@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Roles</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Permissions</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
            <h4>Role Details: {{ $role->role }}</h4>

            <h4>Permissions:</h4>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Feature</th>
                                    <th>Can Create</th>
                                    <th>Can Edit</th>
                                    <th>Can Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role->userPermissions as $perm)
                                    <tr>
                                        <td>{{ $perm->feature->feature }}</td>
                                        <td>{{ $perm->can_create ? 'Yes' : 'No' }}</td>
                                        <td>{{ $perm->can_edit ? 'Yes' : 'No' }}</td>
                                        <td>{{ $perm->can_delete ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back to Roles</a>
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
