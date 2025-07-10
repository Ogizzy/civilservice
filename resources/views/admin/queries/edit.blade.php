
@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
<div class="container">
    <h3>Edit Query</h3>
    <form action="{{ route('employees.queries.update', [$employee->id, $queriesMisconduct->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

        @include('admin.queries.partials.form', ['queryData' => $queriesMisconduct])
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</div>
@endsection
