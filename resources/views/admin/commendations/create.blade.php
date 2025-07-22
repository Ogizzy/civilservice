@extends('admin.admin_dashboard')

@section('title', 'Add Commendation')

@section('admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="page-content">
<div class="container-fluid px-4">

    <h4 class="mt-4">Add New Commendation / Award</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('employees.index') }}">Commendations & Awards</a>
        </li>
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

            <form action="{{ route('employees.commendations.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <h5 class="card-title mb-0 fw-bold">Commendation/Award</h5>
                    <p class="text-muted mb-0 small">For: 
                        <span class="fw-bold text-primary">
                            {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                        </span>
                    </p>
                </div>

                <div class="row mb-3 mt-2">
                    <div class="col-md-6">
                        <label for="award" class="form-label">Award Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="award" name="award" value="{{ old('award') }}" placeholder="Enter Award Title" required>
                    </div>

                    <div class="col-md-6">
                        <label for="awarding_body" class="form-label">Awarding Body <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="awarding_body" name="awarding_body" value="{{ old('awarding_body') }}" placeholder="Enter Awarding Body" required>
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
                        <div class="form-text text-danger">
                            Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Save Commendation
                        </button>
                        <a href="{{ route('employees.commendations.index', $employee->id) }}" class="btn btn-secondary ms-2">
                            <i class="bx bx-history"></i> History
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
