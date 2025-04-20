@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Mange Pay Groups</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Pay Groups</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->


<div class="page-content">
    <h4>User Permissions</h4>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">+ Add Permission</a>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead class="thead-dark">
            <tr>
                <th>Role</th>
                <th>Feature</th>
                <th>Create</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($permissions as $permission)
            <tr>
                <td>{{ $permission->role->role }}</td>
                <td>{{ $permission->feature->feature }}</td>
                <td>{{ $permission->can_create ? '✔' : '❌' }}</td>
                <td>{{ $permission->can_edit ? '✔' : '❌' }}</td>
                <td>{{ $permission->can_delete ? '✔' : '❌' }}</td>
                <td>
                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
