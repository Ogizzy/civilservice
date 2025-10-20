@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Permissions</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Permissions</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                        <i class="bx bxs-plus-square"></i> Add Permission
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <h6 class="mb-0 text-uppercase">Permission Management</h6>

        <hr>

        <div class="card">
            <div class="card-body">

                <!-- Toolbar for search + export/print -->
                <div class="d-flex justify-content-between mb-3">
                    <!-- Laravel Search -->
                    <form method="GET" action="{{ route('permissions.index') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                            placeholder="Search permissions...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <!-- Export/Print Buttons -->
                    <div id="exportButtons"></div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="permissionsTable" class="table table-hover table-striped align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th>Feature</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>
                                        <span class="badge bg-light-primary text-dark">
                                            {{ $permission->role->role }}
                                        </span>
                                    </td>
                                    <td>{{ $permission->feature->feature }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill bg-{{ $permission->can_create ? 'success' : 'secondary' }}">
                                            {{ $permission->can_create ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill bg-{{ $permission->can_edit ? 'success' : 'secondary' }}">
                                            {{ $permission->can_edit ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill bg-{{ $permission->can_delete ? 'success' : 'secondary' }}">
                                            {{ $permission->can_delete ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                                class="btn btn-sm btn-warning px-3" title="Edit">
                                                <i class="bx bxs-edit"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3 delete-btn"
                                                    title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $permissions->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables but disable pagination/search
            let table = $('#permissionTable').DataTable({
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
