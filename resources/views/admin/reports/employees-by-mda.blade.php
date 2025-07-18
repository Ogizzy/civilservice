@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bx bx-building-house me-2"></i>Employee Distribution by MDA
            </h4>
            <p class="text-muted mb-0">Filter and view employee details by MDA</p>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-primary me-2">
                <i class="bx bx-export me-1"></i> Export Data
            </button>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-printer me-1"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <i class="bx bx-home-alt"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('reports.by-mda')}}" class="text-decoration-none">Reports</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">MDA Distribution</li>
        </ol>
    </nav>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bx bx-filter-alt me-2"></i>Filter Options
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.by-mda') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="mda_id" class="form-label small text-uppercase fw-medium">Select MDA</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-buildings"></i>
                        </span>
                        <select name="mda_id" id="mda_id" class="form-select" required>
                            <option value="">-- Choose MDA --</option>
                            @foreach ($mdas as $mda)
                                <option value="{{ $mda->id }}" {{ $selectedMdaId == $mda->id ? 'selected' : '' }}>
                                    {{ $mda->mda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="per_page" class="form-label small text-uppercase fw-medium">Items per page</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-list-ul"></i>
                        </span>
                        <select name="per_page" id="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary me-2" type="submit">
                        <i class="bx bx-filter me-1"></i> Apply Filter
                    </button>
                    <a href="{{ route('reports.by-mda') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-reset me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedMdaId && $employees->count())
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-white text-primary p-3 me-3">
                            <i class="bx bx-user-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Total Employees</h6>
                            <h3 class="mb-0">{{ $employees->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($genderStats))
                @foreach($genderStats as $gender => $count)
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm {{ $gender == 'Male' ? 'bg-info' : 'bg-danger' }} text-white">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle bg-white {{ $gender == 'Male' ? 'text-info' : 'text-danger' }} p-3 me-3">
                                    <i class="bx {{ $gender == 'Male' ? 'bx-male' : 'bx-female' }} fs-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $gender }}</h6>
                                    <h3 class="mb-0">{{ $count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-white text-success p-3 me-3">
                            <i class="bx bx-line-chart fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Grade Levels</h6>
                            <h3 class="mb-0">{{ $totalGradeLevels ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Card -->
        @if (!empty($genderStats))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-chart me-2"></i>Gender Distribution
                    </h5>
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
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <!-- Employee Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bx bx-list-ul me-2"></i>Employees in 
                    <span class="fw-bold text-primary">{{ optional($employees->first())->mda->mda ?? 'Selected MDA' }}</span>
                </h5>
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" class="form-control" placeholder="Search employees..." id="searchEmployee">
                    <span class="input-group-text bg-transparent border-start-0">
                        <i class="bx bx-search"></i>
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="employeeTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">
                                    <div class="d-flex align-items-center">
                                        Employee No.
                                        <a href="#" class="text-muted ms-2 sort-link" data-column="id">
                                            <i class="bx bx-sort-alt-2"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div class="d-flex align-items-center">  
                                        Full Name
                                        <a href="#" class="text-muted ms-2 sort-link" data-column="name">
                                            <i class="bx bx-sort-alt-2"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>Gender</th>
                                <th>Rank</th>
                                <th>Pay Group</th>
                                <th class="pe-4">Grade Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $emp->employee_number }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $emp->surname }}, {{ $emp->first_name }} {{ $emp->middle_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $emp->gender == 'Male' ? 'info' : 'secondary' }} bg-opacity-10 text-{{ $emp->gender == 'Male' ? 'info' : 'danger' }} px-2 py-1">
                                            <i class="bx {{ $emp->gender == 'Male' ? 'bx-male' : 'bx-female' }} me-1"></i>
                                            {{ $emp->gender }}
                                        </span>
                                    </td>
                                    <td>{{ $emp->rank }}</td>
                                    <td>{{ $emp->payGroup->paygroup ?? 'N/A' }}</td>
                                    <td class="pe-4">
                                        <span class="badge bg-info bg-opacity-10 text-primary px-2 py-1">
                                            GL {{ $emp->gradeLevel->level ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination Footer -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <span class="text-muted small me-3">
                            Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} 
                            of {{ $employees->total() }} employees
                        </span>
                    </div>
                    
                    <!-- Custom Pagination -->
                    @if ($employees->hasPages())
                        <nav aria-label="Employee pagination">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($employees->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $employees->appends(request()->query())->previousPageUrl() }}">
                                            <i class="bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                    @if ($page == $employees->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $employees->appends(request()->query())->url($page) }}">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($employees->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $employees->appends(request()->query())->nextPageUrl() }}">
                                            <i class="bx bx-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    @elseif($selectedMdaId)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="bx bx-search text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5>No employees found</h5>
                <p class="text-muted">No employees are currently assigned to the selected MDA.</p>
                <a href="{{ route('reports.by-mda') }}" class="btn btn-outline-primary mt-3">
                    <i class="bx bx-reset me-1"></i> Reset Filters
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="bx bx-filter text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5>Select an MDA to view employees</h5>
                <p class="text-muted">Use the filter above to select a MDA</p>
            </div>
        </div>
    @endif
</div>

@if (!empty($genderStats))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart initialization
        let currentChart;
        const labels = {!! json_encode(array_keys($genderStats)) !!};
        const data = {!! json_encode(array_values($genderStats)) !!};
        const backgroundColors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)'
        ];
        const borderColors = [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 99, 132, 1)'
        ];
        
        function initBarChart() {
            const ctx = document.getElementById('genderChart').getContext('2d');
            if (currentChart) {
                currentChart.destroy();
            }
            
            currentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'No. of Employees',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Gender Distribution',
                            font: {
                                size: 16
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
                            title: {
                                display: true,
                                text: 'Gender'
                            }
                        }
                    }
                }
            });
        }
        
        function initPieChart() {
            const ctx = document.getElementById('genderChart').getContext('2d');
            if (currentChart) {
                currentChart.destroy();
            }
            
            currentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
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
                        title: {
                            display: true,
                            text: 'Gender Distribution',
                            font: {
                                size: 16
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
        
        // Initialize bar chart by default
        initBarChart();
        
        // Chart type toggle
        const barChartBtn = document.getElementById('barChartBtn');
        const pieChartBtn = document.getElementById('pieChartBtn');
        
        if (barChartBtn && pieChartBtn) {
            barChartBtn.addEventListener('click', function() {
                barChartBtn.classList.add('active');
                pieChartBtn.classList.remove('active');
                initBarChart();
            });
            
            pieChartBtn.addEventListener('click', function() {
                pieChartBtn.classList.add('active');
                barChartBtn.classList.remove('active');
                initPieChart();
            });
        }
        
        // Search functionality (for current page only)
        const searchInput = document.getElementById('searchEmployee');
        const table = document.getElementById('employeeTable');
        if (searchInput && table) {
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();
                
                for (let i = 0; i < rows.length; i++) {
                    const rowData = rows[i].textContent.toLowerCase();
                    if (rowData.indexOf(searchTerm) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        }
        
        // Table sorting (for current page only)
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
                
                if (column === 'id') {
                    aValue = a.cells[0].textContent.trim();
                    bValue = b.cells[0].textContent.trim();
                } else {
                    aValue = a.cells[1].textContent.trim();
                    bValue = b.cells[1].textContent.trim();
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

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
@endif

@endsection