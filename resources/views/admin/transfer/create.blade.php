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
                    <div class="col-12">
                        <label for="document_file" class="form-label">Upload Transfer Letter (PDF, max 10MB) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="bx bx-upload"></i></span>
                            <input type="file" name="document_file" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="form-text" style="color: red">Please upload the official transfer letter in PDF format</div>
                        @error('document_file') 
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

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
</style>

@endsection