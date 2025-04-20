@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Edit Permissions</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="page-content">
    <h4>Edit Permission</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $permission->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="feature_id" class="form-label">Feature</label>
            <select name="feature_id" class="form-select" required>
                <option value="">-- Select Feature --</option>
                @foreach($features as $feature)
                    <option value="{{ $feature->id }}" {{ $permission->feature_id == $feature->id ? 'selected' : '' }}>
                        {{ $feature->feature }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="can_create" type="checkbox" value="1" id="create"
                {{ $permission->can_create ? 'checked' : '' }}>
            <label class="form-check-label" for="create">Can Create</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="can_edit" type="checkbox" value="1" id="edit"
                {{ $permission->can_edit ? 'checked' : '' }}>
            <label class="form-check-label" for="edit">Can Edit</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" name="can_delete" type="checkbox" value="1"
                {{ $permission->can_delete ? 'checked' : '' }}>
            <label class="form-check-label" for="delete">Can Delete</label>
        </div>

        <button class="btn btn-primary">Update Permission</button>
    </form>
</div>
</div>

@endsection
