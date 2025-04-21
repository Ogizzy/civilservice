@extends('admin.admin_dashboard')

@section('title', 'Edit Query/Misconduct')

@section('admin')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Query/Misconduct</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries & Misconduct</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Edit Query/Misconduct Information
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('queries.update', $query) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $query->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_number }} - {{ $employee->surname }}, {{ $employee->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date_issued" class="form-label">Date Issued <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_issued" name="date_issued" 
                            value="{{ old('date_issued', $query->date_issued->format('Y-m-d')) }}" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="query" class="form-label">Query/Misconduct Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="query" name="query" rows="5" required>{{ old('query', $query->query) }}</textarea>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supporting_document" class="form-label">Supporting Document</label>
                        <input type="file" class="form-control" id="supporting_document" name="supporting_document">
                        <div class="form-text">Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)</div>
                        
                        @if($query->document)
                            <div class="mt-2">
                                <span class="text-primary">Current Document:</span>
                                <a href="{{ $query->document->document }}" target="_blank" class="ms-2">
                                    <i class="fas fa-file-alt"></i> View Document
                                </a>
                                <div class="form-text">Uploading a new document will replace the current one.</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Query Record
                        </button>
                        <a href="{{ route('queries.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection