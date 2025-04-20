@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h2>Edit Role: {{ $role->role }}</h2>

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="role">Role Name</label>
            <input type="text" name="role" id="role" class="form-control" value="{{ old('role', $role->role) }}" required>
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
@endsection
