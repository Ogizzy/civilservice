@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-5 radius-30">
                <i class="bx bx-arrow-back me-1"></i>Back
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 text-primary">
                    <i class="bx bx-edit-alt me-2"></i>Edit Role: <strong>{{ $role->role }}</strong>
                </h4>
                <span class="badge bg-primary">Role ID: {{ $role->id }}</span>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-rename"></i></span>
                            <input type="text" name="role" id="role" class="form-control" 
                                   value="{{ old('role', $role->role) }}" required>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="permissions-section mt-5">
                    <h5 class="mb-4 text-primary">
                        <i class="bx bx-shield-alt-2 me-2"></i>Role Permissions
                    </h5>
                    
                    <div class="row g-3">
                        @foreach($features as $feature)
                            @php
                                $permission = $role->userPermissions->firstWhere('feature_id', $feature->id);
                            @endphp
                            <div class="col-md-4">
                                <div class="card h-100 border-primary">
                                    <div class="card-header bg-light-primary py-3">
                                        <h6 class="mb-0">
                                            <i class="bx bx-cog me-2"></i>{{ $feature->feature }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="permissions[{{ $loop->index }}][feature_id]" value="{{ $feature->id }}">
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="permissions[{{ $loop->index }}][can_create]" value="1" 
                                                   class="form-check-input" id="create_{{ $feature->id }}"
                                                   {{ $permission && $permission->can_create ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create_{{ $feature->id }}">Create Access</label>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="permissions[{{ $loop->index }}][can_edit]" value="1" 
                                                   class="form-check-input" id="edit_{{ $feature->id }}"
                                                   {{ $permission && $permission->can_edit ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_{{ $feature->id }}">Edit Access</label>
                                        </div>
                                        
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="permissions[{{ $loop->index }}][can_delete]" value="1" 
                                                   class="form-check-input" id="delete_{{ $feature->id }}"
                                                   {{ $permission && $permission->can_delete ? 'checked' : '' }}>
                                            <label class="form-check-label" for="delete_{{ $feature->id }}">Delete Access</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bx bx-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save me-1"></i>Update Role
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    .form-switch .form-check-input {
        width: 2.5rem;
        margin-right: 0.5rem;
    }
    .form-switch .form-check-input:checked {
        background-color: #5a8dee;
        border-color: #5a8dee;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
</style>

@endsection