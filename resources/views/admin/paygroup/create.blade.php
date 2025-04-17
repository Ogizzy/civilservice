@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h4>Create New Pay Group</h4>

    <form action="{{ route('pay-groups.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Pay Group Name</label>
            <input type="text" name="paygroup" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Pay Group Code</label>
            <input type="text" name="paygroup_code" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('pay-groups.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
