@extends('admin.admin_dashboard')
@section('admin')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="page-content">

<div class="container-fluid px-4">
    <h4 class="mt-4">Add New Query/Misconduct</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.queries.index', $employee->id) }}">Queries & Misconduct</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fadeIn animated bx bx-error"></i>
            New Query/Misconduct Information
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
            
            <form action="{{ route('employees.queries.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- No need to select employee since it's passed in URL --}}
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                  <div>
                    <h5 class="card-title mb-0 fw-bold">Query/Miscounduct</h5>
                    <p class="text-muted mb-0 small">For: 
                        <span class="fw-bold text-primary">
                            {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                        </span>
                    </p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_issued" class="form-label">Date Issued <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_issued" name="date_issued" value="{{ old('date_issued') }}" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="query_title" class="form-label">Query/Misconduct Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="query_title" name="query_title" value="{{ old('query_title') }}" placeholder="e.g., Late Arrival, Unauthorized Absence, Policy Violation" required>
                    </div>
                </div>
                               
                 <div class="card">
                    <div class="card-body">
                        <label for="query" class="form-label">Query/Misconduct Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="editor" name="query" rows="5" placeholder="Provide detailed description of the query or misconduct..." required>{{ old('query') }}</textarea>
                    </div>
                </div> 
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supporting_document" class="form-label">Supporting Document</label>
                        <input type="file" class="form-control" id="supporting_document" name="supporting_document">
                        <div class="form-text" style="color: red">Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)</div>
                    </div>
                </div>
               
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="lni lni-save"></i> Submit Query Record
                        </button>
                        <a href="{{ route('employees.queries.index', $employee->id) }}" class="btn btn-secondary ms-2">
                            <i class="bx bx-history"></i> Query History
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<script>
		var quill = new Quill('#editor', {
		  theme: 'snow'
		});
	  </script>
@endsection
