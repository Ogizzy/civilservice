@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage Transfers</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create Transfers</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <h5>Initiate Transfer for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }} </span></h5>

    <form action="{{ route('employees.transfers.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mt-3">
            <label for="current_mda">Transfer To (MDA) *</label>
            <select name="current_mda" id="current_mda" class="form-control" required>
                <option value="">Select MDA</option>
                @foreach($mdas as $mda)
                    <option value="{{ $mda->id }}">{{ $mda->mda }}</option>
                @endforeach
            </select>
            @error('current_mda') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mt-3">
            <label for="effective_date">Effective Date *</label>
            <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date') }}" required>
            @error('effective_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mt-3">
            <label for="document_file">Upload Transfer Letter (PDF, max 10MB) *</label>
            <input type="file" name="document_file" class="form-control" required>
            @error('document_file') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <button class="btn btn-primary" type="submit">Submit Transfer</button>
            <a href="{{ route('employees.transfers.index', $employee->id) }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</div>

@endsection
