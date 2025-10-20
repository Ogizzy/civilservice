@extends('admin.admin_dashboard')

@section('admin')
<div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3 fw-semibold">MDAs</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('mdas.index') }}">Ministries</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <hr>

    <div class="container-fluid">
        <!-- Ministry Profile Banner -->
        <div class="card bg-primary text-white border-0 mb-4 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-white text-primary rounded-circle p-3 shadow-sm">
                        <i class="fadeIn animated bx bx-home"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 text-white">{{ $mda->mda }}</h3>
                        <div class="d-flex gap-3 small">
                            <span><strong>Code:</strong> {{ $mda->mda_code }}</span>
                            <span><strong>Created:</strong> {{ $mda->created_at->format('M d, Y') }}</span>
                            <span><strong>Updated:</strong> {{ $mda->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mt-md-0 d-flex gap-2">
                    
                    <a href="{{ route('mdas.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Employees Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                            <i class="fadeIn animated bx bx-user-check fs-3 text-white"></i>
                        </div>
                        <h5 class="mb-0">Ministry Employees</h5>
                    </div>
                    <span class="badge bg-primary round">
                        {{ $mda->employees->count() }} {{ Str::plural('Employee', $mda->employees->count()) }}
                    </span>
                </div>
            </div>

            @if($employees->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                <div class="rounded-circle bg-light p-4 mb-3">
                    <i class="bx bx-user-x fs-1 text-secondary"></i>
                </div>
                <h5 class="text-secondary">No Employees Found</h5>
                <p class="text-muted">There are no employees currently associated with this ministry.</p>
                <a href="{{ route('employees.create')}}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bx bx-user-plus me-1"></i> Add Employee
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">S/N</th>
                            <th>Employee</th>
                            <th>Employee No.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $index => $employee)
                            <tr>
                                <td class="ps-4">{{ ($employees->currentPage() - 1) * $employees->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $employee->surname }}, {{ $employee->first_name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $employee->employee_number ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('employees.show', $employee->id) }}">
                                                    <i class="bx bx-show me-2"></i> View Details
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        
            <div class="p-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        @endif
        
            @if(!$mda->employees->isEmpty())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('employees.create')}}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-user-plus me-1"></i> Add Employee
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-download"></i> Export List
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
