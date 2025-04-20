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
                    <li class="breadcrumb-item active" aria-current="page">Edit Features</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
<div class="page-content">
    <h4>Edit Feature</h4>
    <form method="POST" action="{{ route('features.update', $feature->id) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Feature Name</label>
            <input type="text" name="feature" value="{{ $feature->feature }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required>{{ $feature->description }}</textarea>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
</div>
@endsection
