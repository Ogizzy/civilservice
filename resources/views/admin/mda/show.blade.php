@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3 fw-semibold">MDAS</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
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
    <!--end breadcrumb-->

    <div class="container-fluid">
        <div class="row">
            <!-- MDA Information Card -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white p-2 me-3">
                                <i class="bx bx-building-house fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">Ministry Profile</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-6 fw-bold text-primary">{{ $mda->mda_code }}</div>
                            <h4 class="mt-2">{{ $mda->mda }}</h4>
                            <div class="badge bg-light text-dark px-3 py-2 mt-2">
                                <i class="bx bx-check-shield me-1"></i>Official Ministry
                            </div>
                        </div>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted small">Ministry Code</div>
                                    <div class="fw-medium">{{ $mda->mda_code }}</div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted small">Created</div>
                                    <div class="fw-medium">{{ $mda->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted small">Last Updated</div>
                                    <div class="fw-medium">{{ $mda->updated_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('mdas.show', $mda->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center flex-grow-1">
                                <i class="bx bx-show me-2"></i> View Details
                            </a>
                            <a href="{{ route('mdas.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center flex-grow-1">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Employees Card -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                    <i class="bx bx-user-circle fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0">Ministry Employees</h5>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ $mda->employees->count() }} {{ Str::plural('Employee', $mda->employees->count()) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        @if($mda->employees->isEmpty())
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
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4">S/N</th>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Employee No.</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mda->employees as $index => $employee)
                                            <tr>
                                                <td class="ps-4">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                            <span class="text-primary">
                                                                {{ substr($employee->first_name ?? 'A', 0, 1) }}{{ substr($employee->surname ?? 'N', 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $employee->surname ?? 'N/A' }}, {{ $employee->first_name ?? 'N/A' }}</h6>
                                                            <small class="text-muted">{{ $employee->middle_name ?? 'N/A' }}</small>
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
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('employees.show', $employee->id) }}">
                                                                    <i class="bx bx-show me-2"></i> View Details
                                                                </a>
                                                            </li>
                                                            {{-- <li>
                                                                <a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">
                                                                    <i class="bx bx-edit me-2"></i> Edit
                                                                </a>
                                                            </li> --}}
                                                            {{-- <li><hr class="dropdown-divider"></li> --}}
                                                            
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    
                    @if(!$mda->employees->isEmpty())
                        <div class="card-footer bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">
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
    </div>
</div>
@endsection