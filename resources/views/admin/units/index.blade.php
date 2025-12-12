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
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">List of Units</li>
                        </ol>
                    </nav>
                </div>
               
            </div>
                <h6 class="mb-0 text-uppercase">Manage units</h6>

            <hr>




<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5 radius-30" placeholder="Search Unit"> <span
                    class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
            </div>
            <div class="ms-auto"><a href="{{ route('units.create') }}"
                    class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Create Unit</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
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
         <div class="mt-3">
        {{ $units->links('pagination::bootstrap-5') }}
      </div>
    </div>
</div>
</div>
 
@endsection