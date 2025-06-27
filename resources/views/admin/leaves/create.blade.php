{{-- resources/views/leaves/create.blade.php --}}
@extends('admin.admin_dashboard')
@section('admin')

{{-- resources/views/leaves/create.blade.php --}}

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Apply for Leave</h3>
                    <div class="card-tools">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Employee Information --}}
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Employee Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Employee Number</label>
                                            <input type="text" value="{{ $employee->employee_number }}" class="form-control" readonly disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" value="{{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name }}" class="form-control" readonly disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>MDA</label>
                                            <input type="text" value="{{ $employee->mda->mda ?? 'N/A' }}" class="form-control" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Leave Balance Information --}}
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Leave Balance ({{ date('Y') }})</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="leave-balance-info">
                                            <p class="text-muted">Select a leave type to view balance</p>
                                        </div>
                                        <div id="balance-details" style="display: none;">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Allocated</span>
                                                            <span class="info-box-number" id="allocated-days">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Available</span>
                                                            <span class="info-box-number" id="available-days">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Used</span>
                                                            <span class="info-box-number" id="used-days">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-box bg-secondary">
                                                        <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="pending-days">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Leave Application Form --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} ({{ $type->max_days_per_year }} days/year)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Total Days</label>
                                    <input type="text" id="total_days" name="total_days" class="form-control" readonly>
                                    <small class="form-text text-muted" id="days_breakdown"></small>
                                    <div id="balance_validation" style="display: none;" class="mt-2">
                                        <div class="alert alert-warning" id="balance_warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="warning_message"></span>
                                        </div>
                                        <div class="alert alert-danger" id="balance_error" style="display: none;">
                                            <i class="fas fa-times-circle"></i>
                                            <span id="error_message"></span>
                                        </div>
                                        <div class="alert alert-success" id="balance_success" style="display: none;">
                                            <i class="fas fa-check-circle"></i>
                                            <span id="success_message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" 
                                              placeholder="Enter reason for leave" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_address">Contact Address (While on Leave)</label>
                                    <textarea name="contact_address" id="contact_address" rows="2" class="form-control @error('contact_address') is-invalid @enderror" 
                                              placeholder="Enter your contact address while on leave">{{ old('contact_address') }}</textarea>
                                    @error('contact_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone (While on Leave)</label>
                                    <input type="tel" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           value="{{ old('contact_phone') }}" placeholder="Enter your contact phone number">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        
                        <hr>

                        {{-- Emergency Contact Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Emergency Contact Information</h5>
                                <div class="form-group">
                                    <label for="emergency_contact">Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                           value="{{ old('emergency_contact') }}" placeholder="Enter emergency contact name">
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Contact Phone</label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                           value="{{ old('emergency_phone') }}" placeholder="Enter emergency contact phone number">
                                    @error('emergency_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Supporting Document</h5>
                                <div class="form-group">
                                    <label for="supporting_document">Supporting Document (Optional)</label>
                                    <input type="file" name="supporting_document" id="supporting_document" 
                                           class="form-control-file @error('supporting_document') is-invalid @enderror"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">
                                        Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum size: 5MB
                                    </small>
                                    @error('supporting_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit Leave Application
                                </button>
                                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Auto Day Calculation and Leave Balance Checking --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysInput = document.getElementById('total_days');
    const daysBreakdownElement = document.getElementById('days_breakdown');
    const leaveTypeSelect = document.getElementById('leave_type_id');
    const submitButton = document.querySelector('button[type="submit"]');

    // Leave balance data (this would normally come from your backend)
    const leaveBalances = @json($leaveBalances ?? []);
    
    // Example leave balance structure - replace with actual data from your controller
    // const leaveBalances = {
    //     '1': { // leave_type_id
    //         allocated: 21,
    //         used: 5,
    //         pending: 2,
    //         available: 14
    //     },
    //     '2': {
    //         allocated: 7,
    //         used: 0,
    //         pending: 0,
    //         available: 7
    //     }
    // };

    
    // Function to display leave balance
    function displayLeaveBalance(leaveTypeId) {
        const balanceInfo = document.getElementById('leave-balance-info');
        const balanceDetails = document.getElementById('balance-details');
        
        if (!leaveTypeId || !leaveBalances[leaveTypeId]) {
            balanceInfo.style.display = 'block';
            balanceDetails.style.display = 'none';
            return;
        }

        const balance = leaveBalances[leaveTypeId];
        
        // Update balance display
        document.getElementById('allocated-days').textContent = balance.allocated;
        document.getElementById('used-days').textContent = balance.used;
        document.getElementById('pending-days').textContent = balance.pending;
        document.getElementById('available-days').textContent = balance.available;
        
        // Show balance details
        balanceInfo.style.display = 'none';
        balanceDetails.style.display = 'block';
        
        // Re-validate current request
        validateLeaveBalance();
    }

    // Function to validate leave balance
    function validateLeaveBalance() {
        const leaveTypeId = leaveTypeSelect.value;
        const totalDays = parseInt(totalDaysInput.value) || 0;
        
        const validationDiv = document.getElementById('balance_validation');
        const warningAlert = document.getElementById('balance_warning');
        const errorAlert = document.getElementById('balance_error');
        const successAlert = document.getElementById('balance_success');
        
        // Hide all alerts initially
        warningAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        successAlert.style.display = 'none';
        validationDiv.style.display = 'none';
        
        if (!leaveTypeId || !totalDays || !leaveBalances[leaveTypeId]) {
            submitButton.disabled = false;
            return;
        }
        
        const balance = leaveBalances[leaveTypeId];
        const availableDays = balance.available;
        
        validationDiv.style.display = 'block';
        
        if (totalDays > availableDays) {
            // Insufficient balance
            errorAlert.style.display = 'block';
            document.getElementById('error_message').textContent = 
                `Insufficient leave balance! You requested ${totalDays} days but only have ${availableDays} days available.`;
            submitButton.disabled = true;
        } else if (totalDays === availableDays) {
            // Using all available days
            warningAlert.style.display = 'block';
            document.getElementById('warning_message').textContent = 
                `You are using all your available leave days (${availableDays} days).`;
            submitButton.disabled = false;
        } else if (availableDays - totalDays <= 2) {
            // Low balance warning
            warningAlert.style.display = 'block';
            document.getElementById('warning_message').textContent = 
                `After this leave, you will have ${availableDays - totalDays} days remaining.`;
            submitButton.disabled = false;
        } else {
            // Sufficient balance
            successAlert.style.display = 'block';
            document.getElementById('success_message').textContent = 
                `Leave request valid. You will have ${availableDays - totalDays} days remaining after this leave.`;
            submitButton.disabled = false;
        }
    }

    // Function to calculate total days
    function calculateTotalDays() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            // Check if end date is before start date
            if (end < start) {
                totalDaysInput.value = '';
                daysBreakdownElement.textContent = 'End date cannot be before start date';
                daysBreakdownElement.style.color = 'red';
                document.getElementById('balance_validation').style.display = 'none';
                return;
            }

            // Calculate total days (inclusive)
            const timeDiff = end.getTime() - start.getTime();
            const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

            // Calculate working days (excluding weekends)
            let workingDays = 0;
            let currentDate = new Date(start);
            
            while (currentDate <= end) {
                const dayOfWeek = currentDate.getDay();
                // 0 = Sunday, 6 = Saturday
                if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                    workingDays++;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Display results
            totalDaysInput.value = totalDays;
            
            if (totalDays === workingDays) {
                daysBreakdownElement.textContent = `${totalDays} total days (no weekends included)`;
            } else {
                daysBreakdownElement.textContent = `${totalDays} total days (${workingDays} working days, ${totalDays - workingDays} weekend days)`;
            }
            daysBreakdownElement.style.color = '#6c757d';

            // Validate leave balance after calculating days
            validateLeaveBalance();

        } else {
            totalDaysInput.value = '';
            daysBreakdownElement.textContent = '';
            document.getElementById('balance_validation').style.display = 'none';
        }
    }

    // Function to update end date minimum value
    function updateEndDateMin() {
        const startDate = startDateInput.value;
        if (startDate) {
            endDateInput.min = startDate;
            // If end date is before start date, clear it
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = '';
            }
        }
        calculateTotalDays();
    }

    // Event listeners
    leaveTypeSelect.addEventListener('change', function() {
        displayLeaveBalance(this.value);
    });

    startDateInput.addEventListener('change', function() {
        updateEndDateMin();
    });

    endDateInput.addEventListener('change', function() {
        calculateTotalDays();
    });

    // Initialize on page load
    if (leaveTypeSelect.value) {
        displayLeaveBalance(leaveTypeSelect.value);
    }
    
    if (startDateInput.value || endDateInput.value) {
        updateEndDateMin();
    }
});
</script>

@endsection