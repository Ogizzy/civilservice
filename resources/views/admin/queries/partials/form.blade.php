<div class="page-content">
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header">
                <i class="fadeIn animated bx bx-edit"></i>
                Query/Misconduct Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select select2" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (old('employee_id', $queryData->employee_id ?? '') == $employee->id) ? 'selected' : '' }}>
                                        {{ $employee->employee_number }} - {{ $employee->surname }}, {{ $employee->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_issued" class="form-label">Date Issued <span class="text-danger">*</span></label>
                            <input type="date" name="date_issued" id="date_issued" class="form-control" 
                                   value="{{ old('date_issued', $queryData->date_issued ?? '') }}" required>
                            @error('date_issued')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="query_title" class="form-label">Query/Misconduct Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="query_title" name="query_title"
                                   value="{{ old('query_title', $queriesMisconduct->query_title ?? '') }}" 
                                   placeholder="e.g., Late Arrival, Unauthorized Absence, Policy Violation" required>
                            @error('query_title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="query" class="form-label">Query/Misconduct Details <span class="text-danger">*</span></label>
                            <textarea name="query" id="query" class="form-control" rows="5" 
                                      placeholder="Provide detailed description of the query or misconduct..." required>{{ old('query', $queryData->query ?? '') }}</textarea>
                            @error('query')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supporting_document" class="form-label">Supporting Document</label>
                            <input type="file" name="supporting_document" id="supporting_document" class="form-control">
                            <div class="form-text text-muted">
                                <small><i class="bx bx-info-circle"></i> Upload PDF, DOC, DOCX, JPG, JPEG, or PNG files (max 10MB)</small>
                            </div>
                            @error('supporting_document')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            
                            @if(isset($queryData->supporting_document) && $queryData->supporting_document)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="bx bx-file"></i> Current file: 
                                        <a href="{{ asset('storage/' . $queryData->supporting_document) }}" target="_blank" class="text-primary">
                                            {{ basename($queryData->supporting_document) }}
                                        </a>
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 for employee dropdown
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Select an employee",
            allowClear: true,
            width: '100%'
        });
    });
</script>