@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">MDAS</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">MDA Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-landmark mr-2"></i> MDA Details
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('mdas.edit', $mda->id) }}" class="btn btn-light btn-sm d-flex align-items-center mr-2">
                    <i class="fas fa-edit mr-1 text-primary"></i> Edit
                </a>
                <a href="{{ route('mdas.index') }}" class="btn btn-light btn-sm d-flex align-items-center">
                    <i class="fas fa-arrow-left mr-1 text-danger"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <p><strong>MDA Name:</strong> {{ $mda->mda }}</p>
            <p><strong>MDA Code:</strong> {{ $mda->mda_code }}</p>

            <hr>
            <h5>Associated Employees</h5>
            @if($mda->employees->isEmpty())
                <p>No employees associated with this MDA.</p>
            @else
                <ul class="list-group">
                    @foreach($mda->employees as $employee)
                        <li class="list-group-item">{{ $employee->name ?? 'Unnamed Employee' }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
