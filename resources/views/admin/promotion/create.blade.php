@extends('admin.admin_dashboard')
@section('admin')

<!-- Add modern frameworks -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<div class="page-content">
    <!-- Enhanced Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3 fw-semibold">
            <i class="fas fa-award me-2"></i>Manage Promotions
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page">
                        Employee Promotion Form
                    </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('employees.promotions.index', $employee->id) }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-history me-1"></i> Promotion History
            </a>
        </div>
    </div>
    <!-- End Breadcrumb -->
<hr>
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-sm rounded-lg animate__animated animate__fadeIn animate__faster">
            <div class="card-header bg-light py-3 d-flex align-items-center">
                <i class="fas fa-user-plus text-primary me-2 fa-lg"></i>
                <h5 class="mb-0 fw-bold">New Promotion for Employee</h5>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-light border-start border-primary border-4 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-3 fa-lg"></i>
                        <div>
                            <h6 class="mb-1">Employee Details</h6>
                            <p class="mb-0 fw-bold text-primary">
                                {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                            </p>
                            <p class="text-muted">
                            Employee current: <b>Grade Level: {{ $employee->level_id }} | Step: {{ $employee->step_id }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('employees.promotions.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="promotion_type" class="form-label fw-semibold">
                            <i class="fas fa-tags me-1 text-secondary"></i>
                            Promotion Type <span class="text-danger">*</span>
                        </label>
                        <select name="promotion_type" id="promotion_type" class="form-select shadow-sm @error('promotion_type') is-invalid @enderror" required>
                            <option value="">-- Select Promotion Type --</option>
                            <option value="level advancement" {{ old('promotion_type') == 'level advancement' ? 'selected' : '' }}>
                                Level Advancement
                            </option>
                            <option value="step advancement" {{ old('promotion_type') == 'step advancement' ? 'selected' : '' }}>
                                Step Advancement
                            </option>
                        </select>
                        @error('promotion_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="current_level" class="form-label fw-semibold">
                                <i class="fas fa-level-up-alt me-1 text-secondary"></i>
                                New Grade Level <span class="text-danger">*</span>
                            </label>
                            <select name="current_level" id="current_level" class="form-select shadow-sm @error('current_level') is-invalid @enderror" required>
                                <option value="">-- Select Grade Level --</option>
                                @foreach($gradeLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('current_level') == $level->id ? 'selected' : '' }}>
                                        Grade Level {{ $level->level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('current_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="current_step" class="form-label fw-semibold">
                                <i class="fas fa-stairs me-1 text-secondary"></i>
                                New Step <span class="text-danger">*</span>
                            </label>
                            <select name="current_step" id="current_step" class="form-select shadow-sm @error('current_step') is-invalid @enderror" required>
                                <option value="">-- Select Step --</option>
                                @foreach($steps as $step)
                                    <option value="{{ $step->id }}" {{ old('current_step') == $step->id ? 'selected' : '' }}>
                                       Step {{ $step->step }}
                                    </option>
                                @endforeach
                            </select>
                            @error('current_step')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="effective_date" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt me-1 text-secondary"></i>
                            Effective Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control shadow-sm @error('effective_date') is-invalid @enderror" value="{{ old('effective_date') }}" required>
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="document_file" class="form-label fw-semibold">
                            <i class="fas fa-file-upload me-1 text-secondary"></i>
                            Upload Promotion Letter <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="file" name="document_file" id="document_file" class="form-control shadow-sm @error('document_file') is-invalid @enderror" required>
                            <span class="input-group-text bg-light"><i class="fas fa-paperclip"></i></span>
                        </div>
                        @error('document_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-1">
                            <i class="fas fa-info-circle me-1"></i> Max file size: 10MB. Accepted formats: PDF, DOCX, JPEG
                        </div>
                    </div>

                

                    <div class="d-flex justify-content-start gap-2 mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                            <i class="fas fa-check-circle me-1"></i> Submit Promotion
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-light px-4 py-2 rounded-pill">
                            <i class="fas fa-times-circle me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern form styling */
    .form-select, .form-control {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    /* Card styling */
    .card {
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
    }
    
    /* Button styling */
    .btn {
        transition: all 0.2s ease-in-out;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Input group styling */
    .input-group-text {
        border-top-right-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }
    
    /* Page animations */
    .page-content {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection