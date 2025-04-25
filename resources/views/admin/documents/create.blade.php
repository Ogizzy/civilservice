@extends('admin.admin_dashboard')
@section('admin')

<!-- Add modern frameworks -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<div class="page-content">
    <!--Enhanced breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3 fw-semibold">
            <i class="fas fa-folder-open me-2 text-primary"></i>Document Management
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page">Employee Documents</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-outline-primary rounded-pill shadow-sm">
                <i class="fas fa-file-alt me-1"></i> View Documents
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="container-fluid px-4">
        <div class="card border-0 shadow-sm rounded-lg animate__animated animate__fadeIn animate__faster">
            <div class="card-header bg-light border-bottom-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="fas fa-file-upload fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Upload New Document</h5>
                        <p class="text-muted mb-0 small">For: <span class="fw-bold text-primary">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-info bg-info bg-opacity-10 border-start border-info border-4 mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle text-info fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-0">Please upload employee documents in supported formats (PDF, DOCX, JPEG). Maximum file size is 10MB.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('employees.documents.store', $employee->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="document_type" class="form-label fw-semibold">
                            <i class="fas fa-tag me-1 text-secondary"></i>
                            Document Type <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="document_type" class="form-control form-control-lg shadow-sm @error('document_type') is-invalid @enderror" 
                               placeholder="Enter Document Type (e.g., Certificate, Contract, CV)" required>
                        @error('document_type') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="document_file" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1 text-secondary"></i>
                            Upload File <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg file-upload">
                            <input type="file" name="document_file" id="document_file" 
                                   class="form-control shadow-sm @error('document_file') is-invalid @enderror" required>
                            <span class="input-group-text bg-light"><i class="fas fa-paperclip"></i></span>
                        </div>
                        @error('document_file') 
                            <div class="text-danger small mt-1">{{ $message }}</div> 
                        @enderror
                        <div class="form-text mt-1">
                            <i class="fas fa-info-circle me-1"></i> Maximum file size: 10MB. Supported formats: PDF, DOCX, JPEG.
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Upload Document
                        </button>
                        <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-light btn-lg px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    /* Form styling */
    .form-control, .input-group-text {
        border-radius: 0.5rem;
        padding: 0.7rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group-text {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    /* Button styling */
    .btn {
        transition: all 0.2s ease-in-out;
        font-weight: 500;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #224abe 0%, #1a3891 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);
    }
    
    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Feature icon styling */
    .feature-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* File upload styling */
    .file-upload {
        position: relative;
    }
    
    .file-upload input[type="file"] {
        color: #444;
        transition: all 0.3s ease;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
    }
</style>

@endsection