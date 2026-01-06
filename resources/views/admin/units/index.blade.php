@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Units</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">List of Units</li>
                </ol>
            </nav>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">Manage Units</h6>
    <hr>

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 radius-30" placeholder="Search Unit" id="unitSearch">
                    <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('units.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                        <i class="bx bxs-plus-square"></i>Create Unit
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table mb-0" id="unitsTable">
                    <thead class="table-light">
                        <tr>
                            <th>S/N</th>
                            <th>Unit</th>
                            <th>Department</th>
                            <th>Unit Head</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->unit_name }}</td>
                                <td>{{ $unit->department?->department_name }}</td>
                                <td>{{ $unit->unitHead?->surname }} {{ $unit->unitHead?->first_name ?? '—' }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('units.edit', $unit) }}"><i class='bx bxs-edit'></i></a>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary ms-2 assign-unit-head-btn"
                                                data-id="{{ $unit->id }}"
                                                data-name="{{ $unit->unit_name }}">
                                            <i class="bx bx-user"> Assign Unit Head</i>
                                        </button>
                                        <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline-block ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-btn"><i class='bx bxs-trash'></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No units found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $units->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Assign Unit Head Modal --}}
<div class="modal fade" id="assignUnitHeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Assign Unit Head – <span id="unitName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="assignUnitHeadForm">
                @csrf
                <div class="modal-body">
                    <input type="text" id="unitHeadSearch" class="form-control mb-2" placeholder="Search employee...">
                    <select id="unitHeadSelect" name="unit_head_id" class="form-select" size="6" required></select>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Assign Head</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const modalEl = document.getElementById('assignUnitHeadModal');
    const bsModal = new bootstrap.Modal(modalEl);

    // Assign Unit Head button click
    $('.assign-unit-head-btn').on('click', function () {
        let unitId = $(this).data('id');
        let unitName = $(this).data('name');

        $('#unitName').text(unitName);
        $('#assignUnitHeadForm').attr('action', `/units/${unitId}/assign-head`);

        let select = $('#unitHeadSelect');
        select.html('<option>Loading...</option>');

        // Fetch employees belonging to unit's department
        fetch(`/units/${unitId}/employees`)
            .then(res => res.json())
            .then(data => {
                select.empty();
                if(data.length === 0){
                    select.append('<option disabled>No employees in this department</option>');
                    return;
                }
                data.forEach(emp => {
                    select.append(`<option value="${emp.id}">${emp.surname} ${emp.first_name} (${emp.employee_number})</option>`);
                });
            })
            .catch(err => {
                select.empty();
                select.append('<option disabled>Error loading employees</option>');
                console.error(err);
            });

        bsModal.show();
    });

    // Live search in modal
    $('#unitHeadSearch').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $('#unitHeadSelect option').each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(value));
        });
    });

    // Submit Assign Unit Head form via AJAX
    $('#assignUnitHeadForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);

        $.post(form.attr('action'), form.serialize())
            .done(res => {
                bsModal.hide();
                toastr.success(res.message);

                // Update table row without reload
                let unitId = form.attr('action').split('/')[2];
                let selectedOption = $('#unitHeadSelect option:selected').text();
                $(`#unitsTable tbody tr`).filter(function () {
                    return $(this).find('td:first').text() == unitId;
                }).find('td').eq(3).text(selectedOption);
            })
            .fail(err => {
                toastr.error(err.responseJSON?.message || 'Assignment failed');
            });
    });

    // Filter units table
    $('#unitSearch').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $('#unitsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().includes(value));
        });
    });
});
</script>

@endsection
