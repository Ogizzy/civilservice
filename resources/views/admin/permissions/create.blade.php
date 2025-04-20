@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Mange Permission</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New Permision</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->


<div class="page-content">
    <h4>Create New Permission</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf

        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->role }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="feature_id" class="form-label">Feature</label>
            <select name="feature_id" class="form-select" required>
                <option value="">-- Select Feature --</option>
                @foreach($features as $feature)
                    <option value="{{ $feature->id }}">{{ $feature->feature }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="can_create" type="checkbox" value="1" id="create">
            <label class="form-check-label" for="create">Can Create</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="can_edit" type="checkbox" value="1" id="edit">
            <label class="form-check-label" for="edit">Can Edit</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" name="can_delete" type="checkbox" value="1">
            <label class="form-check-label" for="delete">Can Delete</label>
        </div>

        <button class="btn btn-primary">Save Permission</button>
    </form>
</div>
</div>

@endsection
