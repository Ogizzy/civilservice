@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">

   <!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bx bx-user me-2"></i>Employee List by Rank
        </h4>
        <p class="text-muted mb-0">Filter and view employee details</p>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-primary me-2">
            <i class="bx bx-export me-1"></i> Export
        </button>
        <button class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-printer me-1"></i> Print
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="rank" class="form-label">Filter by Rank</label>
                <select name="rank" id="rank" class="form-select">
                    <option value="">All Ranks</option>
                    @foreach($ranks as $r)
                        <option value="{{ $r }}" {{ $r == $rank ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="search" class="form-label">Search Employee or MDA</label>
                <input type="text" name="search" id="search" class="form-control" 
                    placeholder="Enter name or MDA..." value="{{ request('search') }}">
            </div>

            <div class="col-md-2">
                <label for="per_page" class="form-label">Show</label>
                <select name="per_page" id="per_page" class="form-select">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-filter me-1"></i> Apply
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h6 class="text-muted small">Total Employees</h6>
            <h3 class="fw-bold">{{ $employees->total() }}</h3>
        </div>
        <div>
            <h6 class="text-muted small">Current Filter</h6>
            <h4 class="fw-bold">{{ $rank ?: 'All Ranks' }}</h4>
        </div>
    </div>
</div>

<!-- Employee Table -->
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Employee Details</h5>

        <!-- Export/Print Buttons -->
        <div>
            <button class="btn btn-sm btn-outline-primary me-2">
                <i class="bx bx-export me-1"></i> Export
            </button>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-printer me-1"></i> Print
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="rankTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Employee Name</th>
                        <th>MDA</th>
                        <th>Rank</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $key => $emp)
                        <tr>
                            <td>{{ $employees->firstItem() + $key }}</td>
                            <td>{{ $emp->surname }} {{ $emp->first_name }}</td>
                            <td>{{ $emp->mda->mda ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ $emp->rank ?? 'N/A' }}</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


</div>

<!-- DataTable Scripts -->

            <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#rankTable').DataTable({
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
