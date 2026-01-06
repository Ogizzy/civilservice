@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Employee Postings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Posting History</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Manage Postings</h6>
    <hr>

    {{-- ================= EMPLOYEE SUMMARY ================= --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light fw-semibold">
            <i class="bx bx-user me-1"></i> Employee Information
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label text-muted">Employee No</label>
                    <div class="form-control bg-light">{{ $employee->employee_number }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Full Name</label>
                    <div class="form-control bg-light">{{ $employee->surname }} {{ $employee->first_name }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Current MDA</label>
                    <div class="form-control bg-light">{{ $employee->mda->mda ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Current Department</label>
                    <div class="form-control bg-light">{{ $employee->department->department_name ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Current Unit</label>
                    <div class="form-control bg-light">{{ $employee->unit->unit_name ?? '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= POSTING FORM ================= --}}
    <form method="POST" action="{{ route('employees.posting.store', $employee->id) }}">
        @csrf

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bx bx-transfer me-1"></i> New Posting Details
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- NEW MDA --}}
                    <div class="col-md-4">
                        <label class="form-label">New MDA <span class="text-danger">*</span></label>
                        <select id="mda_id" name="mda_id" class="form-select" required>
                            <option value="">Select MDA</option>
                            @foreach($mdas as $mda)
                                <option value="{{ $mda->id }}">{{ $mda->mda }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NEW DEPARTMENT --}}
                    <div class="col-md-4">
                        <label class="form-label">New Department <span class="text-danger">*</span></label>
                        <select id="department_id" name="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    {{-- NEW UNIT --}}
                    <div class="col-md-4">
                        <label class="form-label">New Unit <span class="text-danger">*</span></label>
                        <select id="unit_id" name="unit_id" class="form-select" required>
                            <option value="">Select Unit</option>
                        </select>
                    </div>

                    {{-- POSTING TYPE --}}
                    <div class="col-md-4">
                        <label class="form-label">Posting Type <span class="text-danger">*</span></label>
                        <select name="posting_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Temporary">Temporary</option>
                            <option value="Permanent">Permanent</option>
                        </select>
                    </div>

                    {{-- START DATE --}}
                    <div class="col-md-4">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="posted_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    {{-- END DATE --}}
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="ended_at" class="form-control">
                    </div>

                    {{-- REASON --}}
                    <div class="col-md-12">
                        <label class="form-label">Posting Reason</label>
                        <textarea name="remarks" class="form-control" rows="3"
                            placeholder="Administrative posting, service need, restructuring, etc."></textarea>
                    </div>

                </div>
            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary sm" type="submit">
                    <i class="bx bx-check-circle me-1"></i> Post Employee
                </button>
                <a href="{{ route('employees.posting.index', $employee->id) }}" class="btn btn-secondary sm">
                   <i class="fadeIn animated bx bx-transfer"></i> Posting History
                </a>
            </div>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const mdaSelect = document.getElementById('mda_id');
    const departmentSelect = document.getElementById('department_id');
    const unitSelect = document.getElementById('unit_id');

    mdaSelect.addEventListener('change', function () {
        departmentSelect.innerHTML = '<option>Loading...</option>';
        unitSelect.innerHTML = '<option>Select Unit</option>';

        if (!this.value) return;

        fetch(`/get-departments-by-mda/${this.value}`)
            .then(res => res.json())
            .then(data => {
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                data.forEach(dept => {
                    departmentSelect.innerHTML += `<option value="${dept.id}">${dept.department_name}</option>`;
                });
            });
    });

    departmentSelect.addEventListener('change', function () {
        unitSelect.innerHTML = '<option>Loading...</option>';

        if (!this.value) return;

        fetch(`/get-units-by-department/${this.value}`)
            .then(res => res.json())
            .then(data => {
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                data.forEach(unit => {
                    unitSelect.innerHTML += `<option value="${unit.id}">${unit.unit_name}</option>`;
                });
            });
    });

});
</script>


@endsection

