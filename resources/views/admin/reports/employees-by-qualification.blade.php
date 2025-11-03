@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-graduation-cap me-1"></i> Employee Qualifications
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Employee Qualification Report</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary">Export</button>
                <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">PDF</a>
                    <a class="dropdown-item" href="#">Excel</a>
                    <a class="dropdown-item" href="#">CSV</a>
                </div>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

   <div class="card radius-10">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h5 class="mb-0">Filter Employees by Qualification</h5>
                <p class="mb-0 text-muted">Analyze employee distribution based on educational qualifications</p>
            </div>
            <div class="ms-auto">
                <span class="badge bg-light text-dark">
                    <i class="bx bx-user me-1"></i> {{ $employees->total() }} Employees
                </span>
            </div>
        </div>
        <hr>

        {{-- Filter Form --}}
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Select Qualification</label>
                <select name="qualification" class="form-select">
                    <option value="">All Qualifications</option>
                    @foreach($qualifications as $q)
                        <option value="{{ $q }}" {{ request('qualification') == $q ? 'selected' : '' }}>
                            {{ $q }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Search Employee or MDA</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Enter name, MDA, or qualification"
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Per Page</label>
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Apply
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Charts and Summary --}}
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h6 class="mb-0">Employee Distribution</h6>
                </div>
                <div class="chart-container-1">
                    <canvas id="qualificationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="mb-3">Qualification Summary</h6>
                <div class="qualification-summary">
                    @foreach($qualifications->take(5) as $q)
                    <div class="progress-wrapper mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">{{ $q }}</span>
                            <span class="text-dark fw-bold">{{ rand(20,90) }}%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-gradient-{{ ['info','danger','success','warning','primary'][$loop->index % 5] }}" 
                                 role="progressbar" style="width: {{ rand(20,90) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="#" class="btn btn-sm btn-outline-primary w-100 mt-2">View All Qualifications</a>
            </div>
        </div>
    </div>
</div>

{{-- Employee Table --}}
<div class="card radius-10 mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Employee Details</h6>
        </div>

        <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search employees...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>

        <div class="table-responsive">
            <table id="qualificationTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Qualification</th>
                        <th>MDA</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                </div>
                                <div>
                                    <h6>{{ $emp->surname }} {{ $emp->first_name }}</h6>
                                    <small class="text-muted">Employee No: {{ $emp->employee_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>                            
                           {{ $emp->qualifications ?? 'N/A' }}
                        </td>
                        <td>{{ $emp->mda->mda ?? 'N/A' }}</td>
                        <td><span class="badge bg-success">Active</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No employees found with selected filters</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('qualificationChart').getContext('2d');

        const labels = {!! json_encode($employees->map(fn($e) => $e->surname . ' ' . $e->first_name)) !!};
        const dataValues = {!! json_encode($employees->map(fn($e) => 1)) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Employees',
                    data: dataValues,
                    backgroundColor: '#5e72e4',
                    borderColor: '#5e72e4',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: context => `${context.parsed.y} employee(s)`
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { ticks: { autoSkip: true, maxRotation: 45, minRotation: 45 } }
                }
            }
        });
    });
</script>

<style>
    .chart-container-1 {
        position: relative;
        height: 300px;
    }
    .qualification-summary .progress {
        border-radius: 10px;
    }
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
</style>

{{-- DataTables Export Buttons --}}

            <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#qualificationTable').DataTable({
                        paging: false, 
                        searching: false, 
                        info: false,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-sm bg-secondary text-white'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm bg-success text-white'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-sm bg-info text-white'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-sm bg-danger text-white'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-sm bg-primary text-white'
                            }
                        ]
                    });

                    // Move buttons to custom div
                    table.buttons().container().appendTo('#exportButtons');
                });
            </script>
@endsection