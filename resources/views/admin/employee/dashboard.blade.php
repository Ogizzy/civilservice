@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <h4>Welcome <span style="color: royalblue">{{ auth()->user()->surname }} {{ auth()->user()->first_name }} {{ auth()->user()->other_names }}</span></h4>
    </div>
    <!--end breadcrumb-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
        <!-- MDA and Paygroup -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Current MDA</p>
                            <h5 class="my-1 text-success">{{ auth()->user()->employee->mda->mda ?? 'N/A' }}</h5>
                        </div>
                        <div class="widgets-icons-2 bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-briefcase-alt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Paygroup -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pay Group</p>
                            <h5 class="my-1 text-primary">{{ auth()->user()->employee->paygroup->paygroup ?? 'N/A' }}</h5>
                        </div>
                        <div class="widgets-icons-2 bg-gradient-blues text-white ms-auto">
                            <i class="lni lni-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Level -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Grade Level</p>
                            <h5 class="my-1 text-warning">GL
                                {{ auth()->user()->employee->gradeLevel->level ?? 'N/A' }} /
                                Step {{ auth()->user()->employee->step->step ?? 'N/A' }}
                            </h5>
                        </div>
                        <div class="widgets-icons-2 bg-gradient-orange text-white ms-auto">
                            <i class="bx bx-layer"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retirement Date -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Retirement Date</p>
                            <h5 class="my-1 text-danger">{{ \Carbon\Carbon::parse(auth()->user()->employee->retirement_date)->format('d M, Y') }}</h5>
                        </div>
                        <div class="widgets-icons-2 bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Quick Links -->
    <div class="row mt-4">
        <div class="col-md-3">
            <a href="{{ route('employees.show', auth()->user()->employee->id) }}" class="btn btn-outline-primary w-100">
                <i class="bx bx-user me-1"></i> View Profile
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('employees.documents.index', auth()->user()->employee->id) }}" class="btn btn-outline-info w-100">
                <i class="bx bx-file me-1"></i> My Documents
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('employees.promotions.index', auth()->user()->employee->id) }}" class="btn btn-outline-success w-100">
                <i class="bx bx-up-arrow-circle me-1"></i> Promotion History
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('employees.transfers.index', auth()->user()->employee->id) }}" class="btn btn-outline-warning w-100">
                <i class="bx bx-transfer me-1"></i> Transfer History
            </a>
        </div>
    </div>

    <!-- Optional Additions -->
    {{-- You can also show: commendation, queries, trainings, pending approvals, messages, etc. --}}
</div>

@endsection
