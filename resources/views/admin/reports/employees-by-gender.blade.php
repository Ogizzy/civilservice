@extends('admin.admin_dashboard')
@section('admin')
<div class="container mt-4">
    <h4>Gender: <strong>{{ ucfirst($gender) }}</strong></h4>
    @include('admin.reports.partials.employee-table')
</div>
@endsection
