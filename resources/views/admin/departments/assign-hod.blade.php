@extends('admin.admin_dashboard')
@section('admin')

@section('title', 'Assign HOD')
@section('subtitle', 'Assign Head of Department')

<div class="page-content">
    <div class="col-xl-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Assign HOD for: {{ $department->department_name }}</h6>
                <form action="{{ route('departments.assignHod', $department) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Select HOD</label>
                        <select name="hod_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $department->hod_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->surname }} {{ $employee->first_name }} ({{ $employee->employee_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('hod_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-sm">Assign HOD</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
