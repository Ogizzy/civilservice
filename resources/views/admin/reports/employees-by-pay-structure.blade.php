@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <!-- Enhanced Breadcrumb -->
    <div class="card bg-light mb-4 border-0">
        <div class="card-body py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <div class="mb-2 mb-sm-0">
                    <h4 class="fw-bold text-primary mb-1">
                        <i class="bx bx-filter-alt me-1"></i> Employee Pay Structure Analysis
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;" class="text-decoration-none">
                                    <i class="bx bx-home-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('reports.by-mda')}}" class="text-decoration-none">Filter By MDA</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Pay Structure</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="bx bx-printer me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Filter Panel -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-search-alt me-1"></i> Filter Employees
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="paygroup_id" class="form-label text-muted small">Pay Group</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-money"></i>
                                </span>
                                <select id="paygroup_id" name="paygroup_id" class="form-select">
                                    <option value="">All Pay Groups</option>
                                    @foreach($payGroups as $pg)
                                        <option value="{{ $pg->id }}" {{ request('paygroup_id') == $pg->id ? 'selected' : '' }}>
                                            {{ $pg->paygroup }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="level_id" class="form-label text-muted small">Grade Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-trending-up"></i>
                                </span>
                                <select id="level_id" name="level_id" class="form-select">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $l)
                                        <option value="{{ $l->id }}" {{ request('level_id') == $l->id ? 'selected' : '' }}>
                                            {{ $l->level }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="step_id" class="form-label text-muted small">Step</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-walk"></i>
                                </span>
                                <select id="step_id" name="step_id" class="form-select">
                                    <option value="">All Steps</option>
                                    @foreach($steps as $s)
                                        <option value="{{ $s->id }}" {{ request('step_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->step }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bx bx-filter me-1"></i> Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Chart Area -->
        <div class="col-12 col-xl-5 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Distribution Visualization
                    </h5>
                    <div>
                        <span class="badge bg-info rounded-pill">
                            {{ $employees->count() }} Employees
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height:350px;">
                        <canvas id="payChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-muted mb-1 small">Pay Groups</h6>
                            <h5 class="mb-0">{{ $payGroups->count() }}</h5>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted mb-1 small">Grade Levels</h6>
                            <h5 class="mb-0">{{ $levels->count() }}</h5>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted mb-1 small">Steps</h6>
                            <h5 class="mb-0">{{ $steps->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Area -->
        <div class="col-12 col-xl-7 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-list-ul me-1"></i> Employee Listing
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bx bx-sort me-1"></i> Sort by Name</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bx bx-sort-up me-1"></i> Sort by Level</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bx bx-download me-1"></i> Download List</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">S/N</th>
                                    <th>Employee</th>
                                    <th>Pay Group</th>
                                    <th>Grade Level</th>
                                    <th>Step</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $emp)
                                <tr>
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- <div class="avatar avatar-sm me-2 bg-light-primary rounded-circle">
                                                <span class="avatar-initial">{{ substr($emp->first_name, 0, 1) }}</span>
                                            </div> --}}
                                            <div>
                                                <span class="fw-medium">{{ $emp->surname }} {{ $emp->first_name }}</span>
                                                <small class="d-block text-muted">Emp. No: {{ $emp->employee_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-success text-success">
                                            {{ $emp->payGroup->paygroup ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-info text-info">
                                            {{ $emp->gradeLevel->level ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-warning text-warning">
                                            {{ $emp->step->step ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                <i class="bx bx-show"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-folder-open text-muted mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="text-muted">No employees found</h6>
                                            <p class="small text-muted">Try adjusting your filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Showing {{ $employees->count() }} employees</small>
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#"><i class="bx bx-chevron-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="bx bx-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('payChart').getContext('2d');
        
        // Color gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 165, 0, 0.8)');
        gradient.addColorStop(1, 'rgba(255, 165, 0, 0.2)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($employees->pluck('surname')) !!},
                datasets: [{
                    label: 'Employee Pay Distribution',
                    data: {!! json_encode($employees->map(fn($e) => 1)) !!},
                    backgroundColor: gradient,
                    borderColor: '#f39c12',
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 18,
                    maxBarThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        cornerRadius: 6,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        callbacks: {
                            title: function(context) {
                                return context[0].label || 'Employee';  
                            },
                            label: function(context) {
                                return 'Pay Structure Details';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@endsection