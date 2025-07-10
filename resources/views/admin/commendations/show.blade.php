@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="card radius-10">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Commendation/Award Details</h5>
            <div class="btn-group">
                <a href="{{ route('employees.commendations.edit', ['employee' => $employee, 'commendation' => $commendationAward]) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                    <i class="bx bx-trash"></i> Delete
                </button>
                <a href="{{ route('employees.commendations.index', $employee->id) }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Employee</th>
                                <td>{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</td>
                            </tr>

                            <tr>
                                <th>Employee Number</th>
                                <td>{{ $employee->employee_number }}</td>
                            </tr>

                            @if(isset($employee->department))
                            <tr>
                                <th>Department</th>
                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            <tr>
                                <th>Award Title</th>
                                <td>
                                    <span class="badge bg-success fs-6">{{ $commendationAward->award }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Awarding Body</th>
                                <td>{{ $commendationAward->awarding_body }}</td>
                            </tr>

                            <tr>
                                <th>Award Date</th>
                                <td>
                                    <i class="bx bx-calendar me-2"></i>
                                    {{ \Carbon\Carbon::parse($commendationAward->award_date)->format('F d, Y') }}
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($commendationAward->award_date)->diffForHumans() }})</small>
                                </td>
                            </tr>

                            @if(isset($commendationAward->description))
                            <tr>
                                <th>Description</th>
                                <td>{{ $commendationAward->description ?? 'No description provided.' }}</td>
                            </tr>
                            @endif

                            <tr>
                                <th>Document</th>
                                <td>
                                    @if ($commendationAward->document)
                                        <div class="d-flex align-items-center">
                                            <a href="{{ asset('storage/' . $commendationAward->document->document) }}" target="_blank" class="btn btn-info btn-sm me-2">
                                                <i class="bx bx-file"></i> View Document
                                            </a>
                                            <a href="{{ asset('storage/' . $commendationAward->document->document) }}" download class="btn btn-outline-primary btn-sm">
                                                <i class="bx bx-download"></i> Download
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            File: {{ basename($commendationAward->document->document) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            <i class="bx bx-file-blank me-1"></i>
                                            No document uploaded.
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Created By</th>
                                <td>
                                    <i class="bx bx-user me-2"></i>
                                    {{ $commendationAward->user->surname ?? 'N/A' }} {{ $commendationAward->user->first_name ?? '' }}
                                </td>
                            </tr>

                            @if(isset($commendationAward->created_at))
                            <tr>
                                <th>Created Date</th>
                                <td>
                                    <i class="bx bx-time me-2"></i>
                                    {{ $commendationAward->created_at->format('F d, Y - g:i A') }}
                                </td>
                            </tr>
                            @endif

                            @if(isset($commendationAward->updated_at) && $commendationAward->updated_at != $commendationAward->created_at)
                            <tr>
                                <th>Last Updated</th>
                                <td>
                                    <i class="bx bx-edit me-2"></i>
                                    {{ $commendationAward->updated_at->format('F d, Y - g:i A') }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('employees.commendations.create', $employee->id) }}" class="btn btn-outline-primary">
                                    <i class="bx bx-plus"></i> Add New Commendation
                                </a>
                                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-info">
                                    <i class="bx bx-user"></i> View Employee Profile
                                </a>
                                @if ($commendationAward->document)
                                <button type="button" class="btn btn-outline-success" onclick="printDocument()">
                                    <i class="bx bx-printer"></i> Print Document
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Employee Summary Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Employee Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($employee->passport)
                                    <img src="{{ asset('storage/' . $employee->passport) }}" alt="Employee Photo" class="rounded-circle" width="80" height="80">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="bx bx-user" style="font-size: 2rem; color: #6c757d;"></i>
                                    </div>
                                @endif
                            </div>
                            <h6 class="text-center">{{ $employee->surname }} {{ $employee->first_name }}</h6>
                            <p class="text-center text-muted small">Employee Number: {{ $employee->employee_number }}</p>
                            <hr>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this commendation? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ route('employees.commendations.destroy', ['employee' => $employee, 'commendation' => $commendationAward]) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function printDocument() {
    @if ($commendationAward->document)
        window.open('{{ asset('storage/' . $commendationAward->document->document) }}', '_blank');
    @endif
}
</script>

@endsection