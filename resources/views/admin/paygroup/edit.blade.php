@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h4 class="mb-4">Edit Pay Group</h4>

    <form action="{{ route('pay-groups.update', $payGroup->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label for="paygroup">Pay Group Name</label>
            <input type="text" name="paygroup" class="form-control" value="{{ old('paygroup', $payGroup->paygroup) }}" required>
            @error('paygroup') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="paygroup_code">Pay Group Code</label>
            <input type="text" name="paygroup_code" class="form-control" value="{{ old('paygroup_code', $payGroup->paygroup_code) }}" required>
            @error('paygroup_code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pay-groups.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
