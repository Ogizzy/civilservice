@extends('admin.admin_dashboard')
@section('admin')

<div class="container-fluid px-4">
    <h1 class="mt-4">Queries & Misconduct Records for {{ $employee->surname }}, {{ $employee->first_name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries & Misconduct</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->employee_number }}</a></li>
        <li class="breadcrumb-item active">Query Records</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Employee Query History
            <a href="{{ route('queries.create', ['employee_id' => $employee]) }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Add New Query
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Employee Information</h5>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th style="width: 30%">Employee Number</th>
                                <td>{{ $employee->employee_number }}</td>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name }}</td>
                            </tr>
                            <tr>
                                <th>Current MDA</th>
                                <td>{{ $employee->mda->mda ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Current Rank</th>
                                <td>{{ $employee->rank ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <h5 class="border-bottom pb-2 mt-4">Query History</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="employeeQueriesTable">
                    <thead>
                        <tr>
                            <th>Date Issued</th>
                            <th>Query Summary</th>
                            <th>Document</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $query)
                            <tr>
                                <td>{{ $query->date_issued->format('M d, Y') }}</td>
                                <td>{{ Str::limit($query->query, 50) }}</td>
                                <td>
                                    @if($query->document)
                                        <a href="{{ $query->document->document }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file-alt"></i> View
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">No Document</span>
                                    @endif
                                </td>
                                <td>{{ $query->user->surname ?? '' }} {{ $query->user->first_name ?? '' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('queries.show', $query) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('queries.edit', $query) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('queries.destroy', $query) }}" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this query record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No query or misconduct records found for this employee.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $queries->links() }}
            </div>
            
            <div class="mt-4">
                <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                    <i class="fas fa-user me-1"></i> Back to Employee Profile
                </a>
                <a href="{{ route('queries.index') }}" class="btn btn-primary ms-2">
                    <i class="fas fa-list me-1"></i> All Queries
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#employeeQueriesTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false
        });
    });
</script>
@endsection