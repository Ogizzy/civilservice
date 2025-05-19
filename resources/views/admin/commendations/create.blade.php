@extends('admin.admin_dashboard')

@section('title', 'Add Commendation')

@section('admin')

<div class="page-content">

<div class="container-fluid px-4">
    <h4 class="mt-4">Add New Commendation / Award</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('commendations.index') }}">Commendations & Awards</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="lni lni-trophy me-1"></i>
            New Commendation/Award Information
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
            
            <form action="{{ route('commendations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_number }} - {{ $employee->surname }}, {{ $employee->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="award" class="form-label">Award Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="award" name="award" value="{{ old('award') }}" placeholder="Enter Award Title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="awarding_body" class="form-label">Awarding Body <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="awarding_body" name="awarding_body" value="{{ old('awarding_body') }}" placeholder="Enter Award Body" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="award_date" class="form-label">Award Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="award_date" name="award_date" value="{{ old('award_date') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="supporting_document" class="form-label">Supporting Document</label>
                        <input type="file" class="form-control" id="supporting_document" name="supporting_document">
                        <div class="form-text" style="color: red">Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)</div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Save Commendation
                        </button>
                        <a href="{{ route('commendations.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-1"></i> History
                        </a>
                    </div>
                </div>
            </form>
        </div>
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