@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Edit Role</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <h6>Edit Role: {{ $role->role }}</h6>

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="role">Role Name</label>
            <input type="text" name="role" id="role" class="form-select" value="{{ old('role', $role->role) }}" required>
        </div>

        <h4>Update Permissions</h4>
        @foreach($features as $feature)
            @php
                $permission = $role->userPermissions->firstWhere('feature_id', $feature->id);
            @endphp
            <div class="card mb-2 p-2">
                <strong>{{ $feature->name }}</strong>
                <input type="hidden" name="permissions[{{ $loop->index }}][feature_id]" value="{{ $feature->id }}">
                <div class="form-check">
                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_create]" value="1" class="form-check-input"
                        {{ $permission && $permission->can_create ? 'checked' : '' }}>
                    <label class="form-check-label">Can Create</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_edit]" value="1" class="form-check-input"
                        {{ $permission && $permission->can_edit ? 'checked' : '' }}>
                    <label class="form-check-label">Can Edit</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_delete]" value="1" class="form-check-input"
                        {{ $permission && $permission->can_delete ? 'checked' : '' }}>
                    <label class="form-check-label">Can Delete</label>
                </div>
            </div>
        @endforeach

        <button class="btn btn-primary mt-3">Update Role</button>
    </form>
</div>
</div>
@endsection
