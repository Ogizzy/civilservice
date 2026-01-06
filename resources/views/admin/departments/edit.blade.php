@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    {{-- Breadcrumb --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Departments</div>

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('departments.index') }}">Departments</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Department</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <a href="{{ route('departments.index') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-list-ul"></i> View Departments
            </a>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">Edit Department</h6>
    <hr>

    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">
                    <i class="bx bx-edit me-1"></i> Department Information
                </div>

                <div class="card-body">
                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- MDA --}}
                            <div class="col-md-12">
                                <label class="form-label">
                                    MDA <span class="text-danger">*</span>
                                </label>
                                <select name="mda_id" class="form-select" required>
                                    <option value="">-- Select MDA --</option>
                                    @foreach ($mdas as $mda)
                                        <option value="{{ $mda->id }}"
                                            {{ old('mda_id', $department->mda_id) == $mda->id ? 'selected' : '' }}>
                                            {{ $mda->mda }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mda_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Department Name --}}
                            <div class="col-md-8">
                                <label class="form-label">
                                    Department Name <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="department_name"
                                    class="form-control"
                                    value="{{ old('department_name', $department->department_name) }}"
                                    required
                                >
                                @error('department_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Department Code --}}
                            <div class="col-md-4">
                                <label class="form-label">Department Code</label>
                                <input
                                    type="text"
                                    name="department_code"
                                    class="form-control"
                                    value="{{ old('department_code', $department->department_code) }}"
                                >
                                @error('department_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-save"></i> Update Department
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
