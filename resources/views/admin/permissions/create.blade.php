@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permission Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Permission</li>
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
            <h5 class="card-title mb-4"><i class="bx bx-plus-circle me-2"></i>Create New Permission</h5>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('permissions.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="role_id" class="form-label">Select Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <select name="role_id" class="form-select" required>
                            <option value="">-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="feature_id" class="form-label">Select Feature</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-cog"></i></span>
                        <select name="feature_id" class="form-select" required>
                            <option value="">-- Select Feature --</option>
                            @foreach($features as $feature)
                                <option value="{{ $feature->id }}">{{ $feature->feature }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bx bx-shield me-2"></i>Permission Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-check-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="can_create" value="1" id="create">
                                        <label class="form-check-label" for="create">Create Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-check-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="can_edit" value="1" id="edit">
                                        <label class="form-check-label" for="edit">Edit Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-check-lg">
                                        <input class="form-check-input" type="checkbox" name="can_delete" value="1" id="delete">
                                        <label class="form-check-label" for="delete">Delete Access</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary px-5 radius-30">
                            <i class="bx bx-save me-1"></i>Save Permission
                        </button>
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
</style>

@endsection