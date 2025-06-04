@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bx bx-map-pin me-2"></i>Employee Distribution by LGA
            </h4>
            <p class="text-muted mb-0">Analysis of employee distribution across Local Government Areas</p>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-primary me-2">
                <i class="bx bx-export me-1"></i> Export Report
            </button>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-printer me-1"></i> Print
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bx bx-filter-alt me-2"></i>Filter Options</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="mda_id" class="form-label small text-uppercase fw-medium">MDA</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-building"></i>
                        </span>
                        <select name="mda_id" id="mda_id" class="form-select">
                            <option value="">All MDAs</option>
                            @foreach($mdas as $mda)
                                <option value="{{ $mda->id }}" {{ request('mda_id') == $mda->id ? 'selected' : '' }}>
                                    {{ $mda->mda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label small text-uppercase fw-medium">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-user"></i>
                        </span>
                        <select name="gender" id="gender" class="form-select">
                            <option value="">All Genders</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender }}" {{ request('gender') == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-medium">Actions</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bx bx-search me-1"></i> Apply Filters
                        </button>
                        <a href="" class="btn btn-outline-secondary">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary p-3 me-3">
                        <i class="bx bx-map fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Total LGAs</h6>
                        <h3 class="mb-0">{{ $lgaCounts->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-white text-success p-3 me-3">
                        <i class="bx bx-user-plus fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Total Employees</h6>
                        <h3 class="mb-0">{{ $lgaCounts->sum('total') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-white text-info p-3 me-3">
                        <i class="bx bx-stats fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Average per LGA</h6>
                        <h3 class="mb-0">{{ round($lgaCounts->sum('total') / ($lgaCounts->count() ?: 1)) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-white text-warning p-3 me-3">
                        <i class="bx bx-chart fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Top LGA</h6>
                        <h3 class="mb-0">{{ $lgaCounts->max('total') ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-bar-chart-alt-2 me-2"></i>LGA Distribution Chart</h5>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" id="barChartBtn">
                    <i class="bx bx-bar-chart-alt-2"></i>
                </button>
                <button type="button" class="btn btn-outline-primary" id="pieChartBtn">
                    <i class="bx bx-pie-chart-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:350px;">
                <canvas id="lgaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <!-- Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bx bx-table me-2"></i>LGA Employee Distribution</h5>
        <div class="input-group input-group-sm" style="width: 200px;">
            <input type="text" class="form-control" placeholder="Search LGA..." id="searchLga">
            <span class="input-group-text bg-transparent border-start-0">
                <i class="bx bx-search"></i>
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($lgaCounts->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="lgaTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">
                                <div class="d-flex align-items-center">
                                    LGA
                                    <a href="#" class="text-muted ms-2 sort-link" data-column="lga">
                                        <i class="bx bx-sort-alt-2"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end">
                                    Total Employees
                                    <a href="#" class="text-muted ms-2 sort-link" data-column="total">
                                        <i class="bx bx-sort-alt-2"></i>
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lgaCounts as $item)
                            <tr>
                                <td class="ps-4 fw-medium">
                                    @if($item->lga)
                                        <i class="bx bx-map-pin text-primary me-2"></i>{{ $item->lga }}
                                    @else
                                        <span class="badge bg-light text-secondary">
                                            <i class="bx bx-error-circle me-1"></i>Not Specified
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px; max-width: 150px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                style="width: {{ ($item->total / max($lgaCounts->max('total'), 1)) * 100 }}%" 
                                                aria-valuenow="{{ $item->total }}" aria-valuemin="0" 
                                                aria-valuemax="{{ $lgaCounts->max('total') }}"></div>
                                        </div>
                                        <span class="fw-bold">{{ $item->total }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $lgaCounts->links() }} <!-- Pagination Links -->
        @else
            <div class="alert alert-info m-4">
                <div class="d-flex align-items-center">
                    <i class="bx bx-info-circle fs-4 me-2"></i>
                    <div>
                        <h6 class="mb-0">No Data Available</h6>
                        <p class="mb-0">No employee data found for the selected filters.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                @if(request('mda_id') || request('gender'))
                    Filtered results: {{ $lgaCounts->count() }} LGAs
                @else
                    Showing all {{ $lgaCounts->count() }} Local Government Areas
                @endif
            </span>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="bx bx-download me-1"></i> Download CSV
                </a>
                <a href="#" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-chart me-1"></i> Detailed Analysis
                </a>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize chart
    let currentChart;
    const labels = {!! json_encode($lgaCounts->pluck('lga')) !!};
    const data = {!! json_encode($lgaCounts->pluck('total')) !!};
    const backgroundColor = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(199, 199, 199, 0.7)'
    ];
    
    // Get repeated colors for all data points
    const allBackgroundColors = labels.map((_, i) => backgroundColor[i % backgroundColor.length]);
    
    function initBarChart() {
        const ctx = document.getElementById('lgaChart').getContext('2d');
        if (currentChart) {
            currentChart.destroy();
        }
        
        currentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Employees',
                    data: data,
                    backgroundColor: allBackgroundColors,
                    borderColor: allBackgroundColors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
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
                            title: function(tooltipItems) {
                                return tooltipItems[0].label || 'Not Specified';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Number of Employees'
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 45
                        },
                        title: {
                            display: true,
                            text: 'Local Government Areas'
                        }
                    }
                }
            }
        });
    }
    
    function initPieChart() {
        const ctx = document.getElementById('lgaChart').getContext('2d');
        if (currentChart) {
            currentChart.destroy();
        }
        
        currentChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: allBackgroundColors,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || 'Not Specified';
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Table search functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize bar chart by default
        initBarChart();
        
        // Chart type toggle
        document.getElementById('barChartBtn').addEventListener('click', function() {
            document.getElementById('barChartBtn').classList.add('active');
            document.getElementById('pieChartBtn').classList.remove('active');
            initBarChart();
        });
        
        document.getElementById('pieChartBtn').addEventListener('click', function() {
            document.getElementById('pieChartBtn').classList.add('active');
            document.getElementById('barChartBtn').classList.remove('active');
            initPieChart();
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchLga');
        const table = document.getElementById('lgaTable');
        if (searchInput && table) {
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();
                
                for (let i = 0; i < rows.length; i++) {
                    const lgaCell = rows[i].getElementsByTagName('td')[0];
                    if (lgaCell) {
                        const lgaText = lgaCell.textContent || lgaCell.innerText;
                        if (lgaText.toLowerCase().indexOf(searchTerm) > -1) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            });
        }
        
        // Table sorting
        const sortLinks = document.querySelectorAll('.sort-link');
        sortLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const column = this.getAttribute('data-column');
                sortTable(column);
            });
        });
        
        function sortTable(column) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Toggle sort direction
            const currentDir = tbody.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
            tbody.setAttribute('data-sort-dir', currentDir);
            
            // Sort rows
            rows.sort((a, b) => {
                let aValue, bValue;
                
                if (column === 'lga') {
                    aValue = a.cells[0].textContent.trim();
                    bValue = b.cells[0].textContent.trim();
                } else {
                    aValue = parseInt(a.cells[1].textContent.trim());
                    bValue = parseInt(b.cells[1].textContent.trim());
                }
                
                if (currentDir === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            // Reorder table
            rows.forEach(row => tbody.appendChild(row));
        }
    });
</script>

@endsection