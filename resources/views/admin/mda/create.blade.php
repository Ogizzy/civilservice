@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h3 class="mb-4">Create New MDA</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some issues with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mdas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="mda">MDA Name</label>
            <input type="text" name="mda" class="form-control" value="{{ old('mda') }}" required>
        </div>

        <div class="form-group">
            <label for="mda_code">MDA Code</label>
            <input type="text" name="mda_code" class="form-control" value="{{ old('mda_code') }}" required>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Save
        </button>
        <a href="{{ route('mdas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </form>
</div>
@endsection
