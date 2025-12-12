@php
    $isEdit = isset($unit);
    $action = $isEdit ? route('units.update', $unit) : route('units.store');
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
        <label for="department_id" class="form-label">Department</label>
        <select name="department_id" id="department_id" class="form-control" required>
            <option value="">-- Select Department --</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ old('department_id', $unit->department_id ?? request('department_id') ?? '') == $d->id ? 'selected' : '' }}>
                    {{ $d->mda->mda ?? '' }} / {{ $d->department_name }}
                </option>
            @endforeach
        </select>
        @error('department_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="mb-3">
        <label for="unit_name" class="form-label">Unit Name</label>
        <input name="unit_name" id="unit_name" class="form-control" required value="{{ old('unit_name', $unit->unit_name ?? '') }}">
        @error('unit_name')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="mb-3">
        <label for="unit_code" class="form-label">Code (optional)</label>
        <input name="unit_code" id="unit_code" class="form-control" value="{{ old('unit_code', $unit->unit_code ?? '') }}">
        @error('unit_code')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="mb-3">
        <label for="unit_head_id" class="form-label">Unit Head (User)</label>
        <select name="unit_head_id" id="unit_head_id" class="form-control">
            <option value="">-- None --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('unit_head_id', $unit->unit_head_id ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->surname }} {{ $u->first_name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancel</a>
</form>
