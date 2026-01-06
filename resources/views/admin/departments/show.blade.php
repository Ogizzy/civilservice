@extends('admin.admin_dashboard')
@section('admin')

@section('title', $department->department_name)
@section('subtitle', 'Department details & units')


			<div class="page-content">
<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Department: </div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">{{ $department->department_name }}</li>
							</ol>
						</nav>
					</div>
					
				</div>
				<!--end breadcrumb-->
        <hr>
<div class="row">
    <div class="card-body">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-1">{{ $department->department_name }}</h5>
                <div class="small-muted mb-2"><strong>MDA:</strong> {{ $department->mda?->mda }}</div>
                <div><strong>HOD:</strong> {{ $department->hod?->surname }} {{ $department->hod?->first_name }}</div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Units in {{ $department->department_name }} Department</h6>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5 radius-30" placeholder="Search Unit"> <span
                    class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
            </div>
            <div class="ms-auto"><a href="{{ route('units.create') }}?department_id={{ $department->id }}"
                    class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Create Unit</a>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary radius-30 mt-2 mt-lg-0"><i class="bx bx-arrow-to-left"></i>Go Back</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>S/N</th>
                        <th>Unit</th>
                        <th>Unit Head</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($department->units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->unit_name }}</td>
                            <td>{{ $unit->unitHead?->surname }} {{ $unit->unitHead?->first_name }}</td>
                            <td>
                                <div class="d-flex order-actions">
                                    <a href="{{ route('units.edit', $unit) }}" class=""><i
                                            class='bx bxs-edit'></i></a>

                                    <form action="{{ route('units.destroy', $unit) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf @method('DELETE')
                                        <button class="ms-3 delete-btn"                                     ><i
                                       class='bx bxs-trash'></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No units for this department.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

@endsection
