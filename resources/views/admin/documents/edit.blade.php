@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage Document</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Document</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->


<div class="container">
    <h5>Edit Document for: <span style="color: royalblue">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>

    <form action="{{ route('employees.documents.update', [$employee->id, $document->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group mt-3">
            <label for="document_type">Document Type *</label>
            <input type="text" name="document_type" class="form-control" value="{{ $document->document_type }}" required>
            @error('document_type') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mt-3">
            <label for="document_file">Replace File (optional)</label>
            <input type="file" name="document_file" class="form-control">
            @error('document_file') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

@endsection
