<div class="page-content">
<div class="mb-3">
    <label>Employee</label>
    <select name="employee_id" class="form-control" required>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}" {{ (old('employee_id', $queryData->employee_id ?? '') == $employee->id) ? 'selected' : '' }}>
                {{ $employee->employee_number }} - {{ $employee->surname }} {{ $employee->first_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Date Issued</label>
    <input type="date" name="date_issued" class="form-control" value="{{ old('date_issued', $queryData->date_issued ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Query Description</label>
    <textarea name="query" class="form-control" rows="5">{{ old('query', $queryData->query ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Supporting Document (Optional)</label>
    <input type="file" name="supporting_document" class="form-control">
</div>
</div>

