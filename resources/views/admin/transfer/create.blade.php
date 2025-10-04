@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Employee Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Transfers</li>
                    <li class="breadcrumb-item active" aria-current="page">Initiate Transfer</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('employees.transfers.index', $employee->id) }}" class="btn btn-outline-secondary px-5 radius-30">
                <i class="bx bx-history me-1"></i>View History
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="employee-header bg-light-primary p-4 mb-4 rounded">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="mb-1 text-primary">Initiate Transfer For:</h5>
                        <h4 class="mb-0">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</h4>
                        <small>Current MDA: {{$employee->mda->mda ?? 'N/A'}}</small>
                    </div>
                    <div class="employee-id">
                        <span class="badge bg-primary">Employee No: {{ $employee->employee_number }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('employees.transfers.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="row g-3">
                    <!-- Transfer To MDA -->
                    <div class="col-md-6">
                        <label for="current_mda" class="form-label">Transfer To (MDA) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="bx bx-buildings"></i></span>
                            <select name="current_mda" id="current_mda" class="form-select" required>
                                <option value="">Select MDA</option>
                                @foreach($mdas as $mda)
                                    <option value="{{ $mda->id }}" {{ old('current_mda') == $mda->id ? 'selected' : '' }}>
                                        {{ $mda->mda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('current_mda') 
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Effective Date -->
                    <div class="col-md-6">
                        <label for="effective_date" class="form-label">Effective Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="bx bx-calendar"></i></span>
                            <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date') }}" required>
                        </div>
                        @error('effective_date') 
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Upload -->
                    {{-- <div class="col-12">
                        <label for="document_file" class="form-label">Upload Transfer Letter (PDF, max 10MB) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="bx bx-upload"></i></span>
                            <input type="file" name="document_file" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="form-text" style="color: red">Please upload the official transfer letter in PDF format</div>
                        @error('document_file') 
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div> --}}

                         <!-- Document Upload -->
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <i class="fas fa-file-upload me-2"></i> Upload Promotion Letter <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="file-upload-content">
                                    <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                    <h6>Drop files here or click to browse</h6>
                                    <p>Max size: 10MB | Formats: PDF, DOCX, JPEG</p>
                                </div>
                                <input type="file" name="document_file" class="file-input @error('document_file') is-invalid @enderror" id="documentFile" accept=".pdf,.docx,.jpeg,.jpg">
                            </div>
                            <div class="file-preview" id="filePreview" style="display: none;"></div>
                        </div>
                        @error('document_file') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>
                    {{-- End Document Upload --}}

                    <!-- Form Actions -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                                <i class="bx bx-arrow-back me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-paper-plane me-1"></i>Submit Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .employee-header {
        border-left: 4px solid #5a8dee;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .input-group-text {
        border-right: none;
    }
    .form-control, .form-select {
        border-left: none;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: #86b7fe;
    }


    /* File Upload */
    .file-upload-wrapper {
        position: relative;
    }

    .file-upload-area {
        border: 2px dashed #d1ecf1;
        border-radius: var(--border-radius);
        padding: 3rem 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: var(--transition);
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: var(--primary-color);
        background: #e3f2fd;
    }

    .file-upload-area.drag-over {
        border-color: var(--success-color);
        background: #d4edda;
    }

    .file-upload-icon {
        font-size: 3rem;
        color: var(--info-color);
        margin-bottom: 1rem;
    }

    .file-upload-content h6 {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .file-upload-content p {
        color: #6c757d;
        margin: 0;
        font-size: 0.9rem;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: #e8f5e8;
        border-radius: 0%;
        border: 1px solid #c3e6cb;
    }

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("documentFile");
    const fileUploadArea = document.getElementById("fileUploadArea");
    const filePreview = document.getElementById("filePreview");

    // Show selected file name
    fileInput.addEventListener("change", function () {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            filePreview.style.display = "block";
            filePreview.innerHTML = `
                <strong>Selected File:</strong> ${file.name} 
                <br> Size: ${(file.size / 1024 / 1024).toFixed(2)} MB
            `;
        } else {
            filePreview.style.display = "none";
            filePreview.innerHTML = "";
        }
    });

    // Drag and drop highlight
    fileUploadArea.addEventListener("dragover", function (e) {
        e.preventDefault();
        fileUploadArea.classList.add("drag-over");
    });

    fileUploadArea.addEventListener("dragleave", function () {
        fileUploadArea.classList.remove("drag-over");
    });

    fileUploadArea.addEventListener("drop", function (e) {
        e.preventDefault();
        fileUploadArea.classList.remove("drag-over");

        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;

            const file = e.dataTransfer.files[0];
            filePreview.style.display = "block";
            filePreview.innerHTML = `
                <strong>Dropped File:</strong> ${file.name} 
                <br> Size: ${(file.size / 1024 / 1024).toFixed(2)} MB
            `;
        }
    });
});
</script>


@endsection