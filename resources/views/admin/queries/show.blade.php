@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="card radius-10 shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Query/Misconduct Details</h5>
            <a href="{{ route('queries.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </a>
        </div>
        
        <div class="card-body p-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="detail-section mb-4">
                        <h6 class="text-uppercase text-muted mb-3 border-bottom pb-2">Employee Information</h6>
                        <div class="p-3 bg-light rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary-subtle rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-primary fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-5">{{ $queriesMisconduct->employee->surname }}, {{ $queriesMisconduct->employee->first_name }}</p>
                                    <small class="text-muted">Employee Number: {{ $queriesMisconduct->employee->employee_number ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section mb-4">
                        <h6 class="text-uppercase text-muted mb-3 border-bottom pb-2">Query Details</h6>
                        
                        <!-- Improved Query Text Display -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Query Summary:</label>
                            <div class="query-content p-3 bg-light rounded-3" style="max-height: 250px; overflow-y: auto; border-left: 4px solid #4e73df;">
                                <div id="queryText" class="collapse show">
                                    {{ $queriesMisconduct->query }}
                                </div>
                                <div class="text-center mt-2">
                                    <button class="btn btn-sm btn-link toggle-query" type="button" data-bs-toggle="collapse" data-bs-target="#queryText" aria-expanded="true" aria-controls="queryText" onclick="toggleQueryText(this)">
                                        <i class="bi bi-chevron-up me-1"></i><span>Collapse</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="180" class="fw-medium text-dark">Date Issued</td>
                                        <td><span class="badge bg-info text-dark"><i class="bi bi-calendar me-1"></i>{{ $queriesMisconduct->date_issued }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-dark">Issued By</td>
                                        <td>
                                            <span class="d-inline-flex align-items-center">
                                                <i class="bi bi-person-badge me-2 text-primary"></i>
                                                {{ $queriesMisconduct->user->surname ?? 'N/A' }} {{ $queriesMisconduct->user->first_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-dark">Document</td>
                                        <td>
                                            @if($queriesMisconduct->document)
                                                <a href="{{ asset('storage/' . $queriesMisconduct->document->document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-earmark-text me-1"></i>View Document
                                                </a>
                                            @else
                                                <span class="badge bg-light text-dark">No document attached</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-end">
                <a href="{{ route('queries.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </a>
                {{-- <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Print Details
                </button> --}}
            </div>
        </div>
    </div>
</div>

<!-- Add this script to the page for the toggle functionality -->
<script>
    function toggleQueryText(button) {
        const isCollapsed = button.querySelector('span').innerText === 'Expand';
        if (isCollapsed) {
            button.querySelector('i').classList.replace('bi-chevron-down', 'bi-chevron-up');
            button.querySelector('span').innerText = 'Collapse';
        } else {
            button.querySelector('i').classList.replace('bi-chevron-up', 'bi-chevron-down');
            button.querySelector('span').innerText = 'Expand';
        }
    }
</script>
@endsection