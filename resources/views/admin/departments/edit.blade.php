@extends('admin.admin_dashboard')
@section('admin')


@section('title','Edit Department')
@section('subtitle','Update Department details')

<div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Modify Departments</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('departments.index') }}" class="btn btn-primary" title="View Departments">
                        <i class="fadeIn animated bx bx-list-ul"></i> View Departments
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <h6 class="mb-0 text-uppercase">Edit Department</h6>

        <hr>


<div class="row">
  <div class="col-xl-9 mx-auto">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('departments.update', $department) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label">MDA</label>
            <select name="mda_id" class="form-select" required>
              <option value="">-- select MDA --</option>
              @foreach($mdas as $m)
                <option value="{{ $m->id }}" {{ old('mda_id', $department->mda_id) == $m->id ? 'selected':'' }}>{{ $m->mda }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Department Name</label>
            <input name="department_name" class="form-control" value="{{ old('department_name', $department->department_name) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Department Code</label>
            <input name="department_code" class="form-control" value="{{ old('department_code', $department->department_code) }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Head of Department (HOD)</label>
            <select name="hod_id" class="form-select">
              <option value="">-- Select HOD --</option>
              @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('hod_id', $department->hod_id) == $employee->id ? 'selected':'' }}>
                  {{ $employee->surname }} {{ $employee->first_name }}
                </option>
              @endforeach
            </select>
          </div>

          <button class="btn btn-primary btn-sm">Update Department</button>
          <a href="{{ route('departments.index') }}" class="btn btn-danger btn-sm">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
