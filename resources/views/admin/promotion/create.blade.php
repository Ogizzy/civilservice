@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage Promotions</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Employees Promotions Form</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="container">
        <h5 class="mb-4">New Promotion for: <span style="color: royalblue">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>

        <form action="{{ route('employees.promotions.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="promotion_type">Promotion Type *</label>
                <select name="promotion_type" id="promotion_type" class="form-select @error('promotion_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="level advancement" {{ old('promotion_type') == 'level advancement' ? 'selected' : '' }}>Level Advancement</option>
                    <option value="step advancement" {{ old('promotion_type') == 'step advancement' ? 'selected' : '' }}>Step Advancement</option>
                </select>
                @error('promotion_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="current_level">New Grade Level *</label>
                    <select name="current_level" id="current_level" class="form-select @error('current_level') is-invalid @enderror" required>
                        <option value="">-- Select Grade Level --</option>
                        @foreach($gradeLevels as $level)
                            <option value="{{ $level->id }}" {{ old('current_level') == $level->id ? 'selected' : '' }}>
                                {{ $level->level }}
                            </option>
                        @endforeach
                    </select>
                    @error('current_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="current_step">New Step *</label>
                    <select name="current_step" id="current_step" class="form-select @error('current_step') is-invalid @enderror" required>
                        <option value="">-- Select Step --</option>
                        @foreach($steps as $step)
                            <option value="{{ $step->id }}" {{ old('current_step') == $step->id ? 'selected' : '' }}>
                                {{ $step->step }}
                            </option>
                        @endforeach
                    </select>
                    @error('current_step')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="effective_date">Effective Date *</label>
                <input type="date" name="effective_date" id="effective_date" class="form-control @error('effective_date') is-invalid @enderror" value="{{ old('effective_date') }}" required>
                @error('effective_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="document_file">Upload Promotion Letter *</label>
                <input type="file" name="document_file" id="document_file" class="form-control @error('document_file') is-invalid @enderror" required>
                @error('document_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Max file size: 10MB. Accepted formats: PDF, DOCX, jpeg</small>
            </div>

            <div class="d-flex justify-content-start mt-4">
                <button class="btn btn-primary me-2">Submit</button>
                <a href="{{ route('employees.promotions.index', $employee->id) }}" class="btn btn-secondary">Promotion History</a>
            </div>
        </form>
    </div>
</div>

@endsection
