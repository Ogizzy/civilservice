@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h4 class="mb-4">Add New Step</h4>
    <form action="{{ route('steps.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Step Name</label>
            <input type="text" name="step" class="form-control" value="{{ old('step') }}" required>
            @error('step') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
        <a href="{{ route('steps.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
    </form>
</div>
@endsection
