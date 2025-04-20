@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Features</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List of Features</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="page-content">
    <h4>Feature Details</h4>
    <p><strong>Feature:</strong> {{ $feature->feature }}</p>
    <p><strong>Description:</strong> {{ $feature->description }}</p>

    <h5>Permissions:</h5>
    <ul>
        @foreach($feature->userPermissions as $permission)
            <li>{{ $permission->role->role }} - Create: {{ $permission->can_create ? 'Yes' : 'No' }}, Edit: {{ $permission->can_edit ? 'Yes' : 'No' }}, Delete: {{ $permission->can_delete ? 'Yes' : 'No' }}</li>
        @endforeach
    </ul>
</div>
</div>
@endsection
