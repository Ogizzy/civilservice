@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Details of Promotions</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details of Promotions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Promotion Details</h4>
                        <p class="text-muted mb-0">Review promotion information and documentation</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.promotions.index', $employee->id) }}" class="btn btn-secondary">
                            <i class="fadeIn animated bx bx-left-arrow-alt me-1"></i> Back to Promotions
                        </a>
                        @if($promotion->document)
                            <a href="{{ asset('storage/' . $promotion->document->document) }}" target="_blank" class="btn btn-primary">
                                <i class="lni lni-download me-1"></i> Download Document
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Employee Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-user me-2"></i>Employee Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <p class="fw-semibold mb-0">{{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Employee Number</label>
                                    <p class="fw-semibold mb-0">{{ $employee->employee_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promotion Details Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-trending-up me-2"></i>Promotion Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted">Previous Position</label>
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-light text-dark me-2">
                                            Grade Leve {{ $promotion->previousLevel->level ?? 'N/A' }}
                                        </div>
                                        <span class="text-muted">Step {{ $promotion->previousStep->step ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted">Current Position</label>
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-success text-white me-2">
                                            Grade Level {{ $promotion->currentLevel->level ?? 'N/A' }}
                                        </div>
                                        <span class="text-muted">Step {{ $promotion->currentStep->step ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Promotion Type</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge bg-primary">{{ ucfirst($promotion->promotion_type) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Effective Date</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="bx bx-calendar me-1"></i>
                                        {{ $promotion->effective_date->format('d M, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Promoted By</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="bx bx-user-check me-1"></i>
                                        {{ $promotion->user->surname ?? 'N/A' }} {{ $promotion->user->first_name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date Promoted</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="bx bx-time me-1"></i>
                                        {{ $promotion->created_at->format('d M, Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Section -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-file me-2"></i>Supporting Documents</h6>
                    </div>
                    <div class="card-body">
                        @if($promotion->document)
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="file-icon me-3">
                                        <i class="bx bx-file-blank text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Promotion Letter</h6>
                                        <p class="text-muted mb-0">Click to view promotion document</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ asset('storage/' . $promotion->document->document) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="lni lni-eye me-1"></i> View
                                    </a>
                                    <a href="{{ asset('storage/' . $promotion->document->document) }}" download class="btn btn-outline-secondary btn-sm">
                                        <i class="lni lni-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-file-blank text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No supporting documents available for this promotion.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-cog me-2"></i>Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-user me-1"></i> View Employee Profile
                            </a>
                            <a href="{{ route('employees.promotions.index', $employee->id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-list-ul me-1"></i> All Promotions
                            </a>
                            @if($promotion->document)
                                <a href="{{ asset('storage/' . $promotion->document->document) }}" target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="bx bx-printer me-1"></i> Print Document
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Promotion Timeline -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-time me-2"></i>Promotion Timeline</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Promotion Effective</h6>
                                    <p class="text-muted mb-0">{{ $promotion->effective_date->format('d M, Y') }}</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Promotion Recorded</h6>
                                    <p class="text-muted mb-0">{{ $promotion->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -37px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e0e0e0;
}

.timeline-content {
    padding-left: 10px;
}

.file-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-header {
    border-bottom: 1px solid #e0e0e0;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-2 {
        justify-content: center;
    }
}
</style>

@endsection