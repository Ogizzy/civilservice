@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Roles</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <h4>Create New Role</h4>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="role">Role Name</label>
            <input type="text" name="role" id="role" class="form-control" value="{{ old('role') }}" required>
        </div>

        <h4>Assign Permissions</h4>
        @foreach($features as $feature)
        <div class="card mb-2 p-2">
            <strong>{{ $feature->name }}</strong>
            <input type="hidden" name="permissions[{{ $loop->index }}][feature_id]" value="{{ $feature->id }}">
            <div class="form-check">
                <input type="checkbox" name="permissions[{{ $loop->index }}][can_create]" value="1" class="form-check-input">
                <label class="form-check-label">Can Create</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="permissions[{{ $loop->index }}][can_edit]" value="1" class="form-check-input">
                <label class="form-check-label">Can Edit</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="permissions[{{ $loop->index }}][can_delete]" value="1" class="form-check-input">
                <label class="form-check-label">Can Delete</label>
            </div>
        </div>
        @endforeach

        <button class="btn btn-success mt-3">Save Role</button>
    </form>
</div>
</div>
@endsection
