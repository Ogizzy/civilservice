@extends('admin.admin_dashboard')

@section('title', 'View Query/Misconduct')

@section('admin')
<div class="container-fluid px-4">
    <h1 class="mt-4">View Query/Misconduct Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries & Misconduct</a></li>
        <li class="breadcrumb-item active">View Details</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Query/Misconduct Details
            <div class="float-end">
                <a href="{{ route('queries.edit', $query) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('queries.index') }}" class="btn btn-secondary btn-sm ms-2">
                    <i class="fas fa-list me-1"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Employee Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Employee Number</th>
                            <td>{{ $query->employee->employee_number }}</td>
                        </tr>
                        <tr>
                            <th>Employee Name</th>
                            <td>{{ $query->employee->surname }}, {{ $query->employee->first_name }} {{ $query->employee->middle_name }}</td>
                        </tr>
                        <tr>
                            <th>MDA</th>
                            <td>{{ $query->employee->mda->mda ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Rank</th>
                            <td>{{ $query->employee->rank ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Query Details</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Date Issued</th>
                            <td>{{ $query->date_issued->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Recorded By</th>
                            <td>{{ $query->user->surname ?? '' }} {{ $query->user->first_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Supporting Document</th>
                            <td>
                                @if($query->document)
                                    <a href="{{ $query->document->document }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-file-alt me-1"></i> View Document
                                    </a>
                                @else
                                    <span class="badge bg-secondary">No Document Attached</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Record Date</th>
                            <td>{{ $query->created_at->format('F d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="border-bottom pb-2">Query/Misconduct Content</h5>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($query->query)) !!}
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-between">
                    <form action="{{ route('queries.destroy', $query) }}" method="POST" 
                        onsubmit="return confirm('Are you sure you want to delete this query record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Delete Record
                        </button>
                    </form>
                    
                    <a href="{{ route('employee.queries', $query->employee_id) }}" class="btn btn-info">
                        <i class="fas fa-history me-1"></i> View All Queries for This Employee
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection