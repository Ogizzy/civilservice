
@extends('admin.admin_dashboard')
@section('admin')

@section('title','Edit Unit')
@section('subtitle','Update unit details')

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('units.update', $unit) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select" required>
              <option value="">-- select department --</option>
              @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ old('department_id', $unit->department_id) == $d->id ? 'selected':'' }}>
                  {{ $d->mda?->mda }} / {{ $d->department_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Unit Name</label>
            <input name="unit_name" class="form-control" value="{{ old('unit_name', $unit->unit_name) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Unit Code</label>
            <input name="unit_code" class="form-control" value="{{ old('unit_code', $unit->unit_code) }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Unit Head (Employee)</label>
            <select name="unit_head_id" class="form-select">
              <option value="">-- none --</option>
              @foreach($employees as $e)
                <option value="{{ $e->id }}" {{ old('unit_head_id', $unit->unit_head_id) == $e->id ? 'selected':'' }}>
                  {{ $e->surname }} {{ $e->first_name }}
                </option>
              @endforeach
            </select>
          </div>

          <button class="btn btn-primary">Update Unit</button>
          <a href="{{ route('units.index') }}" class="btn btn-link">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection