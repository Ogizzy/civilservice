@extends('admin.admin_dashboard') 
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">

    <!-- Breadcrumb -->
    <div class="card bg-light border-0 mb-4">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="bx bx-bar-chart me-1"></i> Employee Pay Structure Analysis
                </h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.by-mda') }}">Filter By MDA</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pay Structure</li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm"><i class="bx bx-export me-1"></i> Export</button>
                <button class="btn btn-outline-secondary btn-sm"><i class="bx bx-printer me-1"></i> Print</button>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="bx bx-filter-alt me-1"></i> Filter Employees</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pay Group</label>
                    <select name="paygroup_id" class="form-select">
                        <option value="">All Pay Groups</option>
                        @foreach($payGroups as $pg)
                            <option value="{{ $pg->id }}" {{ request('paygroup_id') == $pg->id ? 'selected' : '' }}>
                                {{ $pg->paygroup }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Grade Level</label>
                    <select name="level_id" class="form-select">
                        <option value="">All Levels</option>
                        @foreach($levels as $l)
                            <option value="{{ $l->id }}" {{ request('level_id') == $l->id ? 'selected' : '' }}>
                               GL {{ $l->level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Step</label>
                    <select name="step_id" class="form-select">
                        <option value="">All Steps</option>
                        @foreach($steps as $s)
                            <option value="{{ $s->id }}" {{ request('step_id') == $s->id ? 'selected' : '' }}>
                               Step {{ $s->step }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100"><i class="bx bx-search-alt me-1"></i> Apply</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bx bx-list-ul me-1"></i> Employee Listing</h5>
                    <span class="badge bg-primary">Total: {{ $employees->total() }} Employees</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Employee</th>
                                    <th>Pay Group</th>
                                    <th>Grade Level</th>
                                    <th>Step</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $emp)
                                    <tr>
                                        <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $emp->surname }} {{ $emp->first_name }}</strong><br>
                                            <small class="text-muted">Employee No: {{ $emp->employee_number }}</small>
                                        </td>
                                        <td><span class="badge bg-success">{{ $emp->payGroup->paygroup ?? 'N/A' }}</span></td>
                                        <td><span class="badge bg-primary">Grade Level {{ $emp->gradeLevel->level ?? 'N/A' }}</span></td>
                                        <td><span class="badge bg-secondary">Step {{ $emp->step->step ?? 'N/A' }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bx bx-folder-open fs-4 d-block mb-2"></i>
                                            No employees found. Adjust Filter Criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center p-3">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
