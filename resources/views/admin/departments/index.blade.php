@extends('admin.admin_dashboard')
@section('admin')

{{-- jQuery (required for DataTables only) --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Departments</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">
                        List of Departments, MDAs and HODs
                    </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                <i class="bx bxs-plus-square"></i> Add Department
            </a>
        </div>
    </div>

    <hr>
    <h6 class="mb-0 text-uppercase">Manage Departments</h6>

    @if (session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-3">
        <div class="card-body">

            {{-- Search --}}
            <div class="d-flex justify-content-between mb-3">
                <form method="GET" action="{{ route('departments.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        value="{{ request('search') }}" placeholder="Search department...">
                    <button class="btn btn-primary">Search</button>
                </form>

                <div id="exportButtons"></div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="rolesTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Department</th>
                            <th>MDA</th>
                            <th>HOD</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $index => $department)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->department_name }}</td>
                                <td>{{ $department->mda?->mda }}</td>
                                <td>
                                    {{ $department->hod?->surname }}
                                    {{ $department->hod?->first_name ?? '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('departments.show', $department->id) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bx bx-list-ul"></i>
                                    </a>

                                    {{-- Assign HOD --}}
                                    <button type="button"
                                        class="btn btn-primary btn-sm assign-hod-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignHodModal"
                                        data-id="{{ $department->id }}"
                                        data-name="{{ $department->department_name }}"
                                        data-hod="{{ $department->hod_id }}">
                                        <i class="bx bx-user">Assign HOD</i>
                                    </button>

                                    <a href="{{ route('departments.edit', $department->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="lni lni-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('departments.destroy', $department->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm delete-btn">
                                            <i class="lni lni-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $departments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- ================= ASSIGN HOD MODAL (SINGLE) ================= --}}
<div class="modal fade" id="assignHodModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Assign HOD – <span id="departmentName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="assignHodForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select HOD</label>
                        <select name="hod_id" id="hodSelect" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm">Assign HOD</button>
                    <button type="button" class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
$(document).ready(function () {

    // DataTable
    let table = $('#rolesTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-sm btn-secondary' },
            { extend: 'excel', className: 'btn btn-sm btn-success' },
            { extend: 'csv', className: 'btn btn-sm btn-info' },
            { extend: 'pdf', className: 'btn btn-sm btn-danger' },
            { extend: 'print', className: 'btn btn-sm btn-primary' }
        ]
    });

    table.buttons().container().appendTo('#exportButtons');

    // Assign HOD click
    $('.assign-hod-btn').on('click', function () {

        let departmentId = $(this).data('id');
        let departmentName = $(this).data('name');
        let currentHod = $(this).data('hod');

        $('#departmentName').text(departmentName);
        $('#assignHodForm').attr(
            'action',
            `/departments/${departmentId}/assign-hod`
        );

        let hodSelect = $('#hodSelect');
        hodSelect.html('<option>Loading...</option>');

        // Fetch employees in department only
        fetch(`/employees/by-department/${departmentId}`)
            .then(res => res.json())
            .then(data => {

                hodSelect.html('<option value="">-- Select Employee --</option>');

                if (data.length === 0) {
                    hodSelect.append(
                        `<option disabled>No employees in this department</option>`
                    );
                    return;
                }

                data.forEach(emp => {
                    hodSelect.append(`
                        <option value="${emp.id}">
                            ${emp.surname} ${emp.first_name} (${emp.employee_number})
                        </option>
                    `);
                });

                if (currentHod) {
                    hodSelect.val(currentHod);
                }
            });
    });

});
</script>



@endsection
