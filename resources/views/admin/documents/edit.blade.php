@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!-- Header Section -->
    <div class="container-fluid px-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="fw-bold text-dark mb-0">Edit Document</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" class="text-decoration-none">Employees</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.documents.index', $employee->id) }}" class="text-decoration-none">Documents</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-3 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-4 pb-2 border-bottom">
                            <h5 class="card-title mb-1">Employee Information</h5>
                            <p class="text-muted mb-0 fs-6">
                                <span class="fw-medium">Name:</span> 
                                <span class="badge bg-light text-dark p-2 ms-1 rounded-pill">
                                    {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                                </span>
                            </p>
                        </div>

                        <form action="{{ route('employees.documents.update', [$employee->id, $document->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf 
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="document_type" class="form-label small text-uppercase fw-medium">Document Type <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    id="document_type" 
                                    name="document_type" 
                                    class="form-control form-control-lg bg-light @error('document_type') is-invalid @enderror" 
                                    value="{{ $document->document_type }}" 
                                    placeholder="Enter document type"
                                    required
                                >
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="document_file" class="form-label small text-uppercase fw-medium">Replace File</label>
                                <div class="input-group">
                                    <input 
                                        type="file" 
                                        id="document_file" 
                                        name="document_file" 
                                        class="form-control @error('document_file') is-invalid @enderror"
                                    >
                                    <label class="input-group-text" for="document_file"><i class="bx bx-upload"></i></label>
                                    @error('document_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mt-2 d-flex align-items-center">
                                    <span class="badge bg-info text-white me-2">
                                        <i class="bx bx-file"></i>
                                    </span>
                                    <small class="text-muted">
                                        Current file: <a href="#" class="text-decoration-none">{{ basename($document->document_path) }}</a>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4 pt-2">
                                <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-outline-secondary px-4">
                                    <i class="bx bx-arrow-back me-1"></i> Back to Documents
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bx bx-check-circle me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Document Preview Card (Optional) -->
                <div class="card border-0 rounded-3 shadow-sm mt-4">
                    <div class="card-header bg-light border-0">
                        <h6 class="mb-0"><i class="bx bx-file me-1"></i> Document Preview</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="document-preview p-4 bg-light rounded">
                            <i class="bx bx-file-blank fs-1 text-secondary"></i>
                            <p class="mb-0 mt-2">Preview not available</p>
                            <small class="text-muted">Update document to view changes</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Information (Optional) -->
            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 rounded-3 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h6 class="mb-0"><i class="bx bx-info-circle me-1"></i> Document Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Created On</span>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Last Updated</span>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($document->updated_at)->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">File Type</span>
                                <span class="fw-medium">{{ pathinfo($document->document_path, PATHINFO_EXTENSION) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card border-0 rounded-3 shadow-sm mt-4">
                    <div class="card-header bg-light border-0">
                        <h6 class="mb-0"><i class="bx bx-help-circle me-1"></i> Need Help?</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="small text-muted mb-3">If you're having trouble updating this document, please refer to our documentation or contact support.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm d-block mb-2">
                            <i class="bx bx-book-open me-1"></i> View Documentation
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm d-block">
                            <i class="bx bx-support me-1"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection