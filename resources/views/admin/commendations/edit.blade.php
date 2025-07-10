@extends('admin.admin_dashboard')

@section('title', 'Edit Commendation')

@section('admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="page-content">
    <div class="container-fluid px-4">
        <h4 class="mt-4">Edit Commendation / Award</h4>
        
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('employees.commendations.index', $employee->id) }}">Commendations & Awards</a>
            </li>
            <li class="breadcrumb-item active">Edit Commendation</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Edit Commendation/Award Information
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

                <div class="mb-3 p-3 bg-light rounded">
                    <h5 class="mb-2">Commendation/Award</h5>
                    <p class="text-muted mb-0">
                        For: <span class="fw-bold text-primary">
                            {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                        </span>
                    </p>
                </div>

                <form action="{{ route('employees.commendations.update', [$employee->id, $commendation->id]) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="award" class="form-label">Award Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="award" 
                                   name="award" 
                                   value="{{ old('award', $commendation->award) }}" 
                                   placeholder="Enter Award Title" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label for="awarding_body" class="form-label">Awarding Body <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="awarding_body" 
                                   name="awarding_body" 
                                   value="{{ old('awarding_body', $commendation->awarding_body) }}" 
                                   placeholder="Enter Awarding Body" 
                                   required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="award_date" class="form-label">Award Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="award_date" 
                                   name="award_date" 
                                   value="{{ old('award_date', $commendation->award_date->format('Y-m-d')) }}" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label for="supporting_document" class="form-label">Replace Supporting Document (optional)</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="supporting_document" 
                                   name="supporting_document"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)
                            </div>
                            @if($commendation->document)
                                <a href="{{ asset('storage/' . $commendation->document->document) }}"   
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-eye me-1"></i>
                                    View Current Document
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update Commendation
                            </button>
                            <a href="{{ route('employees.commendations.index', $employee->id) }}" 
                               class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i> Cancel
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
    // Initialize Select2 if present
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Select an option",
            allowClear: true
        });
    }
    
    // Basic file validation
    $('#supporting_document').on('change', function() {
        const file = this.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (file && file.size > maxSize) {
            alert('File size must be less than 10MB');
            this.value = '';
        }
    });
});
</script>
@endsection