@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Document Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Employee Documents</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <h5>Upload Document for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>

    <form action="{{ route('employees.documents.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="document_type" class="form-label">Document Type *</label>
            <input type="text" name="document_type" class="form-control" placeholder="Enter Document Type" required>
            @error('document_type') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="document_file" class="form-label">Upload File (Max 10MB) *</label>
            <input type="file" name="document_file" class="form-control" required>
            @error('document_file') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection