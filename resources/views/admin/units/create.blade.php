@extends('admin.admin_dashboard')
@section('admin')

@section('title', 'Create Unit')
@section('subtitle', 'Add a unit')

<div class="page-content">
    <!-- breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Units</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">Add Unit</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('units.index') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-list-ul"></i> View Units
            </a>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">Add New Unit</h6>
    <hr>

    <div class="row">
        <div class="col-xl-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('units.store') }}" method="POST">
                        @csrf

                        {{-- Select MDA --}}
                        <div class="mb-3">
                            <label class="form-label">MDA</label>
                            <select name="mda_id" id="mda_id" class="form-select" required>
                                <option value="">-- select MDA --</option>
                                @foreach ($mdas as $mda)
                                    <option value="{{ $mda->id }}" {{ old('mda_id') == $mda->id ? 'selected' : '' }}>
                                        {{ $mda->mda }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mda_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Select Department --}}
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="">-- select MDA first --</option>
                            </select>
                            @error('department_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Unit Name --}}
                        <div class="mb-3">
                            <label class="form-label">Unit Name</label>
                            <input name="unit_name" class="form-control" placeholder="Unit Name" value="{{ old('unit_name') }}" required>
                            @error('unit_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Unit Code --}}
                        <div class="mb-3">
                            <label class="form-label">Unit Code</label>
                            <input name="unit_code" class="form-control" placeholder="Unit Code" value="{{ old('unit_code') }}">
                            @error('unit_code')
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

{{-- JS for cascading dropdowns --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mdaSelect = document.getElementById('mda_id');
    const departmentSelect = document.getElementById('department_id');

    mdaSelect.addEventListener('change', function () {
        const mdaId = this.value;
        departmentSelect.innerHTML = '<option>Loading...</option>';

        if (!mdaId) {
            departmentSelect.innerHTML = '<option value="">-- select MDA first --</option>';
            return;
        }

        fetch(`/mdas/${mdaId}/departments`)
            .then(res => res.json())
            .then(data => {
                departmentSelect.innerHTML = '<option value="">-- select department --</option>';
                data.forEach(dep => {
                    departmentSelect.innerHTML += `<option value="${dep.id}">${dep.department_name}</option>`;
                });
            });
    });

    // preload old selections
    @if(old('mda_id'))
        mdaSelect.value = "{{ old('mda_id') }}";
        mdaSelect.dispatchEvent(new Event('change'));

        setTimeout(() => {
            @if(old('department_id'))
                departmentSelect.value = "{{ old('department_id') }}";
            @endif
        }, 500);
    @endif
});
</script>

@endsection
