@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">User Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="page-content">
            <h4>User Management</h4>

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Add New User
            </button>

            @include('admin.users.modals.create')

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->surname }} {{ $user->first_name }} {{ $user->other_names }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->role ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                            $badge = match($user->status) {
                                                'active' => 'success',
                                                'suspended' => 'warning',
                                                'banned' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst($user->status) }}</span>
                                        </td>

                                        <td>
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="btn btn-sm btn-info"><i class="lni lni-eye" title="View User Details"></i></a>

                                            {{-- <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal{{ $user->id }}"><i class="bx bxs-edit" title="Edit User"></i></button> --}}

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger " title="Delete User" 
                                                            onclick="confirmDelete(this.parentElement)">
                                                        <i class="bx bx-trash me-1"></i> 
                                                    </button>
                                                </form>
{{--                                            
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                style="display:inline-block">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger delete-btn"><i class="bx bxs-trash" title="Delete User"></i></button>
                                            </form> --}}
                                            @include('admin.users.modals.edit', ['user' => $user])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       

        @include('admin.users.modals.create')

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
        
        <script>
            function confirmDelete(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This User Account will be Permanently Deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        </script>

    @endsection
