@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h4>Add New Grade Level</h4>

    <form action="{{ route('grade-levels.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Grade Level Name</label>
            <input type="text" name="level" class="form-control" value="{{ old('level') }}" required>
            @error('level') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('grade-levels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
