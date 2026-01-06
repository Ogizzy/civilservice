@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Employee Posting</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active">Edit Posting</li>
                    </ol>
                </nav>
            </div>
        </div>

        <h6 class="mb-0 text-uppercase">Edit Employee Posting</h6>
        <hr>

        {{-- Employee Summary --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bx bx-user"></i> Employee Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted">Employee No</label>
                        <div class="form-control bg-light">{{ $employee->employee_number }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Full Name</label>
                        <div class="form-control bg-light">
                            {{ $employee->surname }} {{ $employee->first_name }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted">Current MDA</label>
                        <div class="form-control bg-light">
                            {{ $employee->mda->mda ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bx bx-edit"></i> Edit Posting Details
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('employees.posting.update', [$employee, $posting]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- MDA (Required) --}}
                        <div class="col-md-4">
                            <label class="form-label">MDA <span class="text-danger">*</span></label>
                            <select id="mda_id" name="mda_id" class="form-select" required>
                                @foreach ($mdas as $mda)
                                    <option value="{{ $mda->id }}"
                                        {{ $posting->mda_id == $mda->id ? 'selected' : '' }}>
                                        {{ $mda->mda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- To Department --}}
                        <div class="col-md-4">
                            <label class="form-label">To Department <span class="text-danger">*</span></label>
                            <select id="department_id" name="department_id" class="form-select" required>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $posting->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- To Unit --}}
                        <div class="col-md-4">
                            <label class="form-label">To Unit <span class="text-danger">*</span></label>
                            <select id="unit_id" name="unit_id" class="form-select" required>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id', $posting->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- POSTING TYPE --}}
<div class="col-md-4">
    <label class="form-label">Posting Type <span class="text-danger">*</span></label>
    <select name="posting_type" class="form-select" required>
        <option value="">Select Type</option>
        <option value="Temporary" {{ old('posting_type', $posting->posting_type) == 'Temporary' ? 'selected' : '' }}>Temporary</option>
        <option value="Permanent" {{ old('posting_type', $posting->posting_type) == 'Permanent' ? 'selected' : '' }}>Permanent</option>
    </select>
</div>

{{-- START DATE --}}
<div class="col-md-4">
    <label class="form-label">Start Date <span class="text-danger">*</span></label>
    <input type="date" name="posted_at" class="form-control" 
           value="{{ old('posted_at', $posting->posted_at ? $posting->posted_at : '') }}" required>
</div>

{{-- END DATE --}}
<div class="col-md-4">
    <label class="form-label">End Date</label>
    <input type="date" name="ended_at" class="form-control" 
           value="{{ old('ended_at', $posting->ended_at ? $posting->ended_at : '') }}">
</div>


                        {{-- Remarks --}}
                        <div class="col-md-12">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" rows="4" class="form-control" placeholder="Reason for posting...">{{ old('remarks', $posting->remarks) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('employees.posting.index', $employee->id) }}" class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Posting
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const mdaSelect = document.getElementById('mda_id');
    const departmentSelect = document.getElementById('department_id');
    const unitSelect = document.getElementById('unit_id');

    function loadDepartments(mdaId, selectedDept = null) {
        fetch(`/get-departments-by-mda/${mdaId}`)
            .then(res => res.json())
            .then(data => {
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                data.forEach(dept => {
                    departmentSelect.innerHTML +=
                        `<option value="${dept.id}" ${dept.id == selectedDept ? 'selected' : ''}>
                            ${dept.department_name}
                        </option>`;
                });
            });
    }

    function loadUnits(departmentId, selectedUnit = null) {
        fetch(`/get-units-by-department/${departmentId}`)
            .then(res => res.json())
            .then(data => {
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                data.forEach(unit => {
                    unitSelect.innerHTML +=
                        `<option value="${unit.id}" ${unit.id == selectedUnit ? 'selected' : ''}>
                            ${unit.unit_name}
                        </option>`;
                });
            });
    }

    // 🔁 On MDA change
    mdaSelect.addEventListener('change', function () {
        departmentSelect.innerHTML = '<option>Loading...</option>';
        unitSelect.innerHTML = '<option>Select Unit</option>';

        if (!this.value) return;
        loadDepartments(this.value);
    });

    // 🔁 On Department change
    departmentSelect.addEventListener('change', function () {
        unitSelect.innerHTML = '<option>Loading...</option>';

        if (!this.value) return;
        loadUnits(this.value);
    });

    // ✅ Auto-load on EDIT page
    const currentMda = "{{ $posting->mda_id }}";
    const currentDept = "{{ $posting->department_id }}";
    const currentUnit = "{{ $posting->unit_id }}";

    if (currentMda) {
        loadDepartments(currentMda, currentDept);
    }

    if (currentDept) {
        loadUnits(currentDept, currentUnit);
    }
});
</script>


@endsection
