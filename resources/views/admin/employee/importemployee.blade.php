@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    {{-- <h4 class="mb-sm-0">Upload Employee Data</h4> --}}
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                            <li class="breadcrumb-item active">Bulk Import</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Import Employee Data from Excel</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Upload your Excel file containing employee information. The system will process the data and add employees to the database.</p>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-double-line me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <i class="ri-error-warning-line me-2"></i> Please fix the following errors:
                                <ul class="mt-2 mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('import.employees') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="file" id="inputGroupFile" required accept=".xlsx,.xls,.csv">
                                        <label class="input-group-text" for="inputGroupFile">Choose File</label>
                                    </div>
                                    <div class="form-text">Accepted formats: .xlsx, .xls, .csv</div>
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ri-upload-cloud-2-line align-middle me-1"></i> Import Employees
                                </button>
                                <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm ms-1">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Import Guidelines</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-primary fs-24"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fs-15">File Requirements</h5>
                                <p class="text-muted mb-0">Make sure your Excel file has the following columns: Name, Email, Phone, Department, Position, Joining Date.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="ri-file-download-line text-success fs-24"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fs-15">Sample Template</h5>
                                <p class="text-muted mb-0">Download our <a href="#" class="text-decoration-underline">sample template</a> to ensure your data is properly formatted.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-line text-warning fs-24"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fs-15">Important Note</h5>
                                <p class="text-muted mb-0">Duplicate email addresses will be rejected. Maximum allowed file size is 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Form validation
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
        
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>

@endsection