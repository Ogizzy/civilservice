@extends('admin.admin_dashboard')
@section('admin')

@section('title', 'Create Unit')
@section('subtitle', 'Add a unit and assign unit head')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Units</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List of Units</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('units.index') }}" class="btn btn-primary" title="View Units">
                    <i class="fadeIn animated bx bx-list-ul"></i> View Units
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Add New Unit</h6>
    <hr>

    <div class="row">
        <div class="col-xl-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('units.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- select department --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->mda?->mda }} / {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit Name</label>
                            <input name="unit_name" class="form-control" placeholder="Unit Name" value="{{ old('unit_name') }}" required>
                            @error('unit_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit Code</label>
                            <input name="unit_code" class="form-control" placeholder="Unit Code" value="{{ old('unit_code') }}">
                            @error('unit_code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit Head </label>
                            <select name="unit_head_id" class="form-select">
                                <option value="">-- Select Unit Head --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('unit_head_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->surname }} {{ $employee->first_name }} — {{ $employee->employee_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_head_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-sm">Create Unit</button>
                        <a href="{{ route('units.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
