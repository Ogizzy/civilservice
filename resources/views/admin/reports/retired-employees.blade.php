@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!-- Enhanced Breadcrumb -->
    <div class="card bg-gradient-light mb-4 border-0 rounded-3">
        <div class="card-body py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <h4 class="fw-bold text-primary mb-1">
                        <i class="bx bx-user-check me-1"></i> Retiree Management
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;" class="text-decoration-none">
                                    <i class="bx bx-home-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('reports.by-mda')}}" class="text-decoration-none">MDA Management</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Retired Employees</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="bx bx-printer me-1"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Summary Cards -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card radius-10 bg-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-light-primary me-3">
                                    <i class="bx bx-user-check text-info fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">Total Retirees</h6>
                                    <h4 class="mb-0 text-white">{{ $employees->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card radius-10 bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-light-primary me-3">
                                    <i class="bx bx-calendar text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">Current Year Retirements</h6>
                                    <h4 class="mb-0 text-white">{{ $employees->count() }} </h4><sm class="mb-0 text-white">Employees</sm>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card radius-10 bg-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-light-primary me-3">
                                    <i class="bx bx-building-house text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">MDAs Affected</h6>
                                    <h4 class="mb-0 text-white">{{ $employees->pluck('mda.mda')->unique()->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-filter-alt me-1"></i> Filter Retirees by Date Range
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label text-muted small">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-calendar"></i>
                                </span>
                                <input 
                                    type="date" 
                                    id="start_date" 
                                    name="start_date" 
                                    class="form-control" 
                                    value="{{ request('start_date') }}"
                                    placeholder="Select start date"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label text-muted small">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-calendar-check"></i>
                                </span>
                                <input 
                                    type="date" 
                                    id="end_date" 
                                    name="end_date" 
                                    class="form-control" 
                                    value="{{ request('end_date') }}"
                                    placeholder="Select end date"
                                >
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-50 me-2">
                                <i class="bx bx-filter me-1"></i> Apply Filter
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-50">
                                <i class="bx bx-reset me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

       
        <!-- Data Table Area -->
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-list-ul me-1"></i> Retiree Listing
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bx bx-sort me-1"></i> Sort by Name</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bx bx-sort-up me-1"></i> Sort by Date</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bx bx-download me-1"></i> Download List</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" width="5%">S/N</th>
                                    <th width="35%">Employee</th>
                                    <th width="25%">Retirement Date</th>
                                    <th width="25%">MDA</th>
                                    <th class="text-end pe-3" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $emp)
                                <tr>
                                    <td class="ps-3">{{ ($employees->currentPage() - 1) * $employees->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- <div class="avatar avatar-sm me-2 bg-light-danger rounded-circle">
                                                <span class="avatar-initial">{{ substr($emp->first_name, 0, 1) }}</span>
                                            </div> --}}
                                            <div>
                                                <span class="fw-medium">{{ $emp->surname }} {{ $emp->first_name }}</span>
                                                <small class="d-block text-muted">Employee No: {{ $emp->employee_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-light-danger text-danger me-2">
                                                <i class="bx bx-calendar"></i>
                                            </span>
                                            {{ $emp->retirement_date->format('d M, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                    {{ $emp->mda->mda ?? 'N/A' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-folder-open text-muted mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="text-muted">No retirees found</h6>
                                            <p class="small text-muted">Try adjusting your date filters</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $employees->links('pagination::bootstrap-5') }}
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
        const ctx = document.getElementById('retiredChart').getContext('2d');
        
        // Color gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(231, 76, 60, 0.8)');
        gradient.addColorStop(1, 'rgba(231, 76, 60, 0.2)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($employees->pluck('surname')) !!},
                datasets: [{
                    label: 'Retired Employees',
                    data: {!! json_encode($employees->map(fn($e) => 1)) !!},
                    backgroundColor: gradient,
                    borderColor: '#e74c3c',
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
                                return 'Retired';
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