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
                    <li class="breadcrumb-item active" aria-current="page">Create Feature</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="page-content">
    <h4>Create New Feature</h4>
    <form method="POST" action="{{ route('features.store') }}">
        @csrf
        <div class="mb-3">
            <label>Feature Name</label>
            <input type="text" name="feature" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
</div>
@endsection
