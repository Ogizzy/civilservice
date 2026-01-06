@extends('admin.admin_dashboard')

@section('title', 'Add Commendation')

@section('admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="page-content">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3 fw-bold">
            <i class="fa-solid fa-award text-primary me-1"></i>
            Commendations & Awards
        </div>

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">Add Commendation</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <a href="{{ route('employees.commendations.index', $employee->id) }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-history"></i> History
            </a>
        </div>
    </div>
    <!-- End Breadcrumb -->
<hr>
    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="fa-solid fa-plus-circle text-primary me-1"></i>
                Add Commendation / Award
            </h5>
            <small class="text-muted">
                Employee:
                <span class="fw-bold text-primary">
                    {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}
                </span>
            </small>
        </div>

        <div class="card-body">

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('employees.commendations.store', $employee->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Award Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               name="award"
                               value="{{ old('award') }}"
                               placeholder="e.g. Best Staff of the Year"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Awarding Body <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               name="awarding_body"
                               value="{{ old('awarding_body') }}"
                               placeholder="e.g. Ministry / Organization"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Award Date <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control"
                               name="award_date"
                               value="{{ old('award_date') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Supporting Document
                        </label>
                        <input type="file"
                               class="form-control"
                               name="supporting_document">
                        <small class="text-muted">
                            PDF, DOC, DOCX, JPG, PNG (Max: 10MB)
                        </small>
                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('employees.commendations.index', $employee->id) }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> Save Commendation
                    </button>
                </div>

            </form>

        </div>
    </div>
    <!-- End Card -->

</div>
@endsection
