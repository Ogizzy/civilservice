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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Details of Promotions</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h6></h6>
        <a href="{{ route('employees.promotions.index', $employee->id) }}" class="btn btn-sm btn-secondary">
            <i class="fadeIn animated bx bx-left-arrow-alt"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Employee:</strong> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</p>
            <p><strong>From:</strong> {{ $promotion->previousGradeLevel->level ?? 'N/A' }} - Step {{ $promotion->previousStep->step ?? 'N/A' }}</p>
            <p><strong>To:</strong> {{ $promotion->currentGradeLevel->level ?? 'N/A' }} - Step {{ $promotion->currentStep->step ?? 'N/A' }}</p>
            <p><strong>Promotion Type:</strong> {{ ucfirst($promotion->promotion_type) }}</p>
            <p><strong>Effective Date:</strong> {{ $promotion->effective_date->format('d M, Y') }}</p>
            <p><strong>Uploaded By:</strong> {{ $promotion->user->surname ?? 'N/A' }} {{ $promotion->user->first_name ?? 'N/A' }}</p>
            <hr>
            <p><strong>Document:</strong></p>
            @if($promotion->document)
                <a href="{{ $promotion->document->document }}" target="_blank" class="btn btn-outline-primary">
                    <i class="lni lni-eye"></i> View Promotion Letter
                </a>
            @else
                <p class="text-muted">No document found.</p>
            @endif
        </div>
    </div>
</div>
</div>

@endsection
