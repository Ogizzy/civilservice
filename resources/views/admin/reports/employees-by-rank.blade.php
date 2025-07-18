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
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="rank" class="form-label">Filter by Rank</label>
                    <select name="rank" id="rank" class="form-select">
                        <option value="">All Ranks</option>
                        @foreach($ranks as $r)
                            <option value="{{ $r }}" {{ $r == $rank ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter me-1"></i> Apply Filter
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
                <h3 class="fw-bold">{{ $employees->count() }}</h3>
            </div>
            <div>
                <h6 class="text-muted small">Current Filter</h6>
                <h4 class="fw-bold">{{ $rank ?: 'All Ranks' }}</h4>
            </div>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Employee Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
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
                                <td>{{ $loop->iteration }}</td>
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
        </div>
    </div>

</div>

<!-- DataTable Scripts -->
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
@endsection
