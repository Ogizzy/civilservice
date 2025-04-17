@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h4 class="mb-4">Edit Step</h4>

    <form action="{{ route('steps.update', $step->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Step Name</label>
            <input type="text" name="step" class="form-control" value="{{ old('step', $step->step) }}" required>
            @error('step') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('steps.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
