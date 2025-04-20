@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permissions Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List of Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="page-content">
    <h4>Permission Details</h4>

    <p><strong>Role:</strong> {{ $permission->role->role }}</p>
    <p><strong>Feature:</strong> {{ $permission->feature->feature }}</p>
    <p><strong>Can Create:</strong> {{ $permission->can_create ? 'Yes' : 'No' }}</p>
    <p><strong>Can Edit:</strong> {{ $permission->can_edit ? 'Yes' : 'No' }}</p>
    <p><strong>Can Delete:</strong> {{ $permission->can_delete ? 'Yes' : 'No' }}</p>

    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Delete</button>
    </form>
</div>
</div>

@endsection
