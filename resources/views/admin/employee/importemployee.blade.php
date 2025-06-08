@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <div class="container-fluid">
        <!-- Enhanced Breadcrumb -->
        <div class="row fade-in">
            <div class="col-12">
                <div class="modern-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                        <li class="breadcrumb-item active">Bulk Import</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Upload Form Section -->
            <div class="col-xl-8 slide-up">
                <div class="enhanced-card">
                    <div class="gradient-header">
                        <h5>
                            <i class="ri-upload-cloud-2-line header-icon"></i>
                            Import Employee Data from Excel
                        </h5>
                    </div>
                    <div class="enhanced-card-body">
                        <p class="text-muted mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                            Upload your Excel file containing employee information. The system will process the data and add employees to the database.
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success modern-alert alert-dismissible fade show" role="alert">
                                <i class="ri-check-double-line me-2" style="font-size: 1.25rem;"></i> 
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    
                        @if($errors->any())
                            <div class="alert alert-danger modern-alert alert-dismissible fade show mb-4" role="alert">
                                <i class="ri-error-warning-line me-2" style="font-size: 1.25rem;"></i> 
                                <strong>Error!</strong> Please fix the following issues:
                                <ul class="mt-2 mb-0" style="padding-left: 1.5rem;">
                                    @foreach($errors->all() as $error)
                                        <li style="margin-bottom: 0.25rem;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('import.employees') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="modern-file-input">
                                        <input type="file" name="file" id="inputGroupFile" required accept=".xlsx,.xls,.csv">
                                        <label for="inputGroupFile" class="file-input-label">
                                            <div>
                                                <i class="ri-upload-cloud-2-line file-input-icon"></i>
                                                <div class="file-input-text">Choose Excel File</div>
                                                <div class="file-input-subtext">or drag and drop your file here</div>
                                                <div class="file-input-subtext mt-2">
                                                    <small>Accepted formats: .xlsx, .xls, .csv (Max: 2MB)</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-primary modern-btn">
                                    <i class="ri-upload-cloud-2-line align-middle me-2"></i> 
                                    Import Employees
                                </button>
                                <a href="{{ route('employees.index') }}" class="btn btn-light modern-btn">
                                    <i class="ri-arrow-left-line align-middle me-2"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Guidelines Section -->
            <div class="col-xl-4 slide-up" style="animation-delay: 0.2s;">
                <div class="enhanced-card">
                    <div class="gradient-header">
                        <h5>
                            <i class="ri-file-list-3-line header-icon"></i>
                            Import Guidelines
                        </h5>
                    </div>
                    <div class="enhanced-card-body">
                       
                        <div class="guidelines-item warning pulse-animation">
                            <div class="guideline-icon warning">
                                <i class="ri-file-download-line"></i>
                            </div>
                            <div class="guideline-content">
                                <h5>Download Template</h5>
                                <p>Click to download <a href="{{ route('download.sample.template') }}" class="download-link">Excel Template</a> to ensure your data is properly formatted.</p>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection