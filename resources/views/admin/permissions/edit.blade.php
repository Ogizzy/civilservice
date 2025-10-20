@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permission Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Permission</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary px-5 radius-30">
                <i class="bx bx-arrow-back "></i>Back
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 text-primary">
                    <i class="bx bx-edit-alt me-2"></i>Edit Permission
                </h4>
                <span class="badge bg-primary">Permission ID: {{ $permission->id }}</span>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Role Selection -->
                    <div class="col-md-6">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                            <select name="role_id" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $permission->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Feature Selection -->
                    <div class="col-md-6">
                        <label for="feature_id" class="form-label">Feature <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-cog"></i></span>
                            <select name="feature_id" class="form-select" required>
                                <option value="">-- Select Feature --</option>
                                @foreach($features as $feature)
                                    <option value="{{ $feature->id }}" {{ $permission->feature_id == $feature->id ? 'selected' : '' }}>
                                        {{ $feature->feature }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Permission Toggles -->
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bx bx-shield me-2"></i>Permission Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch form-check-lg mb-3">
                                            <input class="form-check-input" type="checkbox" name="can_create" 
                                                   value="1" id="create" {{ $permission->can_create ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create">Create Access</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch form-check-lg mb-3">
                                            <input class="form-check-input" type="checkbox" name="can_edit" 
                                                   value="1" id="edit" {{ $permission->can_edit ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit">Edit Access</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch form-check-lg">
                                            <input class="form-check-input" type="checkbox" name="can_delete" 
                                                   value="1" id="delete" {{ $permission->can_delete ? 'checked' : '' }}>
                                            <label class="form-check-label" for="delete">Delete Access</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bx bx-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Update Permission
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }
    .card-header {
        border-radius: 0.25rem 0.25rem 0 0 !important;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    .form-check-input:checked {
        background-color: #5a8dee;
        border-color: #5a8dee;
    }
</style>

@endsection