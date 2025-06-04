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

        <!-- Status Alert for Current User -->
        @if(auth()->user()->isSuspended())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bx bx-info-circle me-2"></i>
                Your account is currently suspended. You have limited access to system features.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="page-content">
            <h4>User Management</h4>

            <!-- Only active users can create new users -->
            @if(auth()->user()->isActive())
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    Add New User
                </button>
            @endif

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
                                    <tr class="{{ $user->isBanned() ? 'table-danger' : ($user->isSuspended() ? 'table-warning' : '') }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $user->surname }} {{ $user->first_name }} {{ $user->other_names }}
                                            @if($user->id === auth()->id())
                                                <small class="text-muted">(You)</small>
                                            @endif
                                        </td>
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
                                            
                                            <!-- Quick Status Change Dropdown for Active Users Only -->
                                            @if(auth()->user()->isActive() && $user->id !== auth()->id())
                                                <div class="dropdown d-inline-block ms-2">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bx bx-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($user->status !== 'active')
                                                            <li><a class="dropdown-item status-change" href="#" data-user-id="{{ $user->id }}" data-status="active">
                                                                <i class="bx bx-check text-success"></i> Activate
                                                            </a></li>
                                                        @endif
                                                        @if($user->status !== 'suspended')
                                                            <li><a class="dropdown-item status-change" href="#" data-user-id="{{ $user->id }}" data-status="suspended">
                                                                <i class="bx bx-pause text-warning"></i> Suspend
                                                            </a></li>
                                                        @endif
                                                        @if($user->status !== 'banned')
                                                            <li><a class="dropdown-item status-change" href="#" data-user-id="{{ $user->id }}" data-status="banned">
                                                                <i class="bx bx-block text-danger"></i> Ban
                                                            </a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                                <i class="lni lni-eye" title="View User Details"></i>
                                            </a>

                                            @if(auth()->user()->isActive() && $user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger" title="Delete User" 
                                                            onclick="confirmDelete(this.parentElement)">
                                                        <i class="bx bx-trash me-1"></i>
                                                    </button>
                                                </form>
                                            @endif

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

            // Quick status change functionality
            $(document).on('click', '.status-change', function(e) {
                e.preventDefault();
                
                const userId = $(this).data('user-id');
                const newStatus = $(this).data('status');
                const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                
                Swal.fire({
                    title: `${statusText} User?`,
                    text: `Are you sure you want to ${newStatus} this user?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: newStatus === 'banned' ? '#d33' : '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Yes, ${statusText}!`
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Make AJAX request to change status
                        $.ajax({
                            url: `/users/${userId}/status`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: newStatus
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.error || 'An error occurred',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        </script>
@endsection