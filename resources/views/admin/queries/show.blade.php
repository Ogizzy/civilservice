@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="card shadow-lg border-0 overflow-hidden">
        <!-- Header -->
        <div class="card-header bg-gradient-primary p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-3">
                        <i class="bi bi-file-text text-primary fs-4"></i>
                    </div>
                    <h4 class="text-white mb-0 fw-bold">Query/Misconduct Details</h4>
                </div>
                <a href="{{ route('queries.index') }}" class="btn btn-light rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Employee Information Section -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="detail-section">
                        <h5 class="text-uppercase fw-bold mb-3 text-primary border-bottom pb-2">
                            <i class="bi bi-person-badge me-2"></i>Employee Information
                        </h5>
                        
                        <div class="p-4 bg-light rounded-4 border-start border-5 border-primary">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle bg-primary text-white p-3 me-4 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="bi bi-person-fill fs-1"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $queriesMisconduct->employee->surname }}, {{ $queriesMisconduct->employee->first_name }}</h4>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-credit-card me-2"></i>
                                        <span>Employee Number: <span class="fw-medium">{{ $queriesMisconduct->employee->employee_number ?? 'N/A' }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Query Details Section -->
            <div class="detail-section mb-4">
                <h5 class="text-uppercase fw-bold mb-3 text-primary border-bottom pb-2">
                    <i class="bi bi-file-text me-2"></i>Query Details
                </h5>
                
                <!-- Query Text Display -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold text-dark fs-5">Query Summary</label>
                        <button class="btn btn-sm btn-outline-primary rounded-pill toggle-query" type="button" data-bs-toggle="collapse" data-bs-target="#queryText" aria-expanded="true" aria-controls="queryText" onclick="toggleQueryText(this)">
                            <i class="bi bi-chevron-up me-1"></i><span>Collapse</span>
                        </button>
                    </div>
                    <div class="query-content p-4 bg-light rounded-4 shadow-sm" style="max-height: 300px; overflow-y: auto; border-left: 5px solid #4e73df;">
                        <div id="queryText" class="collapse show">
                            <p class="mb-0 lh-lg">{{ $queriesMisconduct->query }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Query Details Table -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-info-subtle p-3 me-3">
                                        <i class="bi bi-calendar-event text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Date Issued</h6>
                                        <h5 class="fw-bold mb-0">{{ \Carbon\Carbon::parse($queriesMisconduct->date_issued)->format('M d, Y') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-success-subtle p-3 me-3">
                                        <i class="bi bi-person text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Issued By</h6>
                                        <h5 class="fw-bold mb-0">{{ $queriesMisconduct->user->surname ?? 'N/A' }} {{ $queriesMisconduct->user->first_name ?? 'N/A' }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-warning-subtle p-3 me-3">
                                        <i class="bi bi-file-earmark-text text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Document</h6>
                                        @if($queriesMisconduct->document)
                                            <a href="{{ asset('storage/' . $queriesMisconduct->document->document) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill mt-1">
                                                <i class="bi bi-file-earmark-text me-1"></i>View Document
                                            </a>
                                        @else
                                            <span class="badge bg-light text-dark rounded-pill fs-6 px-3">No document attached</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="card-footer bg-light p-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('queries.index') }}" class="btn btn-light border rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Custom styling for page -->
<style>
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .rounded-4 {
        border-radius: 0.75rem;
    }
    
    .query-content {
        font-size: 1.05rem;
    }
    
    @media print {
        .card-header, .card-footer, .toggle-query {
            display: none;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .page-content {
            padding: 0 !important;
        }
    }
</style>

<!-- Toggle functionality script -->
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