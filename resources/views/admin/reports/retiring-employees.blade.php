@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!-- Enhanced Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3">
            <h5 class="fw-semibold mb-0">Retirement Management</h5>
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt text-primary"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-muted">Retirement</span> List
                    </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary">Export</button>
                <button type="button" class="btn btn-outline-secondary">Print</button>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
       <hr>


<!-- Statistics -->
         <div class="row row-cols-1 row-cols-md-2 row-cols-xl-12 g-4 mb-4">
					<div class="col">
						<div class="card radius-10 bg-warning">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
									
										<h4 class="my-1 text-dark">Employees Retiring Soon</h4>
                                        	<p class="mb-0 text-dark">Overview of upcoming retirements</p>
									</div>
									<div class="text-dark ms-auto font-35"><i class="bx bx-user-check"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					
                    <div class="col">
						<div class="card radius-10 bg-danger">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h4 class="mb-0 text-white">Total Retirements</h4>
										<h3 class="my-1 text-white">{{ $employees->total() }}</h3>
									</div>
									<div class="text-white ms-auto font-35"><i class="fadeIn animated bx bx-layer"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bx bx-filter-alt"></i> Filter Options</h5>
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary me-2">
                            <i class="bx bx-search"></i> Apply Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            <i class="bx bx-reset"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visualization Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Retirement Timeline</h5>
                </div>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="retiringChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">

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
                    <table id="retiringTable" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>S/N</th>
                                <th>Employee Name</th>
                                <th>Retirement Date</th>
                                {{-- <th>Days Remaining</th> --}}
                                <th>MDA</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $emp)
                            <tr>
                                <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $emp->surname }} {{ $emp->first_name }}</h6>
                                            <small class="text-red">Employee No: {{ $emp->employee_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block">{{ $emp->retirement_date->format('d M, Y') }}</span>
                                    <small class="text-muted">{{ $emp->retirement_date->diffForHumans() }}</small>
                                </td>
                                {{-- <td>
                                    <span class="badge bg-{{ $emp->retirement_date->diffInDays(now()) < 30 ? 'danger' : 'warning' }}">
                                        {{ $emp->retirement_date->diffInDays(now()) }} Days
                                    </span>
                                </td> --}}
                                <td>{{ $emp->mda->mda ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-sm btn-outline-primary" title="View Profile">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  
                </div>
                
                @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator && $employees->total() > $employees->perPage())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            {{-- Previous Page Link --}}
                            @if($employees->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $employees->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                @if($page == $employees->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if($employees->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $employees->nextPageUrl() }}" rel="next">&raquo;</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let retirementChart;

    function initChart() {
        const ctx = document.getElementById('retiringChart').getContext('2d');
        const labels = {!! json_encode($employees->pluck('surname')) !!};
        const retirementDates = {!! json_encode($employees->pluck('retirement_date')) !!};
        
        // Calculate days remaining for each employee
        const daysRemaining = retirementDates.map(date => {
            const retirementDate = new Date(date);
            const today = new Date();
            return Math.floor((retirementDate - today) / (1000 * 60 * 60 * 24));
        });
        
        // Generate background colors based on days remaining
        const backgroundColors = daysRemaining.map(days => {
            return days < 30 ? '#ff6b6b' : '#feca57';
        });

        retirementChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Days Until Retirement',
                    data: daysRemaining,
                    backgroundColor: backgroundColors,
                    borderColor: '#fff',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} days remaining`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Days Remaining'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Employees'
                        }
                    }
                }
            }
        });
    }

    function changeChartType(type) {
        if (retirementChart) {
            retirementChart.config.type = type;
            retirementChart.update();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initChart();
    });
</script>


  <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#retiringTable').DataTable({
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