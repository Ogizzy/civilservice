@extends('admin.admin_dashboard')

@section('title', 'Queries & Misconduct')

@section('admin')
<div class="container-fluid px-4">
    <h1 class="mt-4">Queries & Misconduct</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Queries & Misconduct</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Queries & Misconduct Records
            <a href="{{ route('queries.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="queriesTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Query Summary</th>
                            <th>Date Issued</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $query)
                            <tr>
                                <td>
                                    {{ $query->employee->employee_number }} - 
                                    {{ $query->employee->surname }}, {{ $query->employee->first_name }}
                                </td>
                                <td>{{ Str::limit($query->query, 50) }}</td>
                                <td>{{ $query->date_issued->format('M d, Y') }}</td>
                                <td>
                                    @if($query->document)
                                        <a href="{{ $query->document->document }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file-alt"></i> View
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">No Document</span>
                                    @endif
                                </td>
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
                                <td colspan="5" class="text-center">No query or misconduct records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $queries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#queriesTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false
        });
    });
</script>
@endsection