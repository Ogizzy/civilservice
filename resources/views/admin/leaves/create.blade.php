{{-- resources/views/leaves/create.blade.php --}}
@extends('admin.admin_dashboard')
@section('admin')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <i class="bx bx-plus me-2"></i> Apply for Leave
                        </h4>
                        <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fadeIn animated bx bx-chevrons-left"></i> Back to List
                        </a>
                    </div>
                </div>
                

                <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <!-- Employee Information -->
                        <div class="card card-info mb-4">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="fadeIn animated bx bx-user-check "></i> Employee Information
                                </h4>
                            </div>
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-info"><i class="fadeIn animated bx bx-bookmark-minus"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Employee Number</span>
                                                <span class="info-box-number">{{ $employee->employee_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-success"><i class="lni lni-user"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Full Name</span>
                                                <span class="info-box-number">{{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-primary"><i class="fadeIn animated bx bx-home-alt"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">MDA</span>
                                                <span class="info-box-number">{{ $employee->mda->mda ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Leave Type Selection -->
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <i class="fadeIn animated bx bx-calendar-check mr-2"></i>Leave Details
                                        </h4>
                                    </div>


                                    <div class="card-body">
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

                                    
                                </div>
                            </div>

                            <!-- Leave Balance Information -->
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <i class="fadeIn animated bx bx-bar-chart-square"></i>Leave Balance ({{ date('Y') }})
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="leave-balance-info">
                                            <div class="no-selection text-center py-3">
                                                <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">Select a leave type to view balance</p>
                                            </div>
                                        </div>
                                        <div id="balance-details" style="display: none;">
                                            <div class="progress-group">
                                                <div class="progress-label">
                                                    <span>Available Days</span>
                                                    <span class="float-right" id="available-days-display">0</span>
                                                </div>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-success" id="available-progress" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div class="balance-stats mt-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <span class="stat-label">Allocated:</span>
                                                            <span class="stat-value" id="allocated-days">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <span class="stat-label">Used:</span>
                                                            <span class="stat-value" id="used-days">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <span class="stat-label">Pending:</span>
                                                            <span class="stat-value" id="pending-days">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <span class="stat-label">Remaining:</span>
                                                            <span class="stat-value" id="remaining-days">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Details Section -->
                        <div class="card card-primary mt-4">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="fadeIn animated bx bx-info-circle mr-2"></i> Additional Information
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="3" class="form-control @error('reason') is-invalid @enderror" 
                                              placeholder="Please provide a detailed reason for your leave application" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_address">Contact Address (While on Leave)</label>
                                            <textarea name="contact_address" id="contact_address" rows="2" class="form-control @error('contact_address') is-invalid @enderror" 
                                                      placeholder="Where can you be reached during your leave?">{{ old('contact_address') }}</textarea>
                                            @error('contact_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_phone">Contact Phone (While on Leave)</label>
                                            <input type="number" name="contact_phone" id="contact_phone" maxlength="11" class="form-control @error('contact_phone') is-invalid @enderror" 
                                                   value="{{ old('contact_phone') }}" placeholder="Phone number during leave">
                                            @error('contact_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergency_contact">Emergency Contact Name</label>
                                            <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                                   value="{{ old('emergency_contact') }}" placeholder="Person to contact in case of emergency">
                                            @error('emergency_contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergency_phone">Emergency Contact Phone</label>
                                            <input type="number" name="emergency_phone" maxlength="11" id="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                                   value="{{ old('emergency_phone') }}" placeholder="Emergency contact phone number">
                                            @error('emergency_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supporting Documents -->
                        <div class="card card-primary mt-4">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="fadeIn animated bx bx-paperclip mr-2"></i> Supporting Documents
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="supporting_document">Upload Supporting Document (Optional)</label>
                                    <div class="custom-file">
                                        <input type="file" name="supporting_document" id="supporting_document" 
                                               class="custom-file-input @error('supporting_document') is-invalid @enderror"
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="supporting_document">Choose file</label>
                                        @error('supporting_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Maximum file size: 5MB. Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                           <i class="fadeIn animated bx bx-paper-plane"></i> Submit Application
                        </button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary ml-2">
                            <i class="fadeIn animated bx bx-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .card-title {
        font-weight: 600;
        color: #495057;
    }
    
    /* Info box styling */
    .info-box {
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    
    .info-box-content {
        padding-left: 0.5rem;
    }
    
    .info-box-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .info-box-number {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    /* Leave balance styling */
    .no-selection {
        padding: 1rem;
        text-align: center;
    }
    
    .no-selection i {
        font-size: 1.5rem;
        color: #adb5bd;
        margin-bottom: 0.5rem;
    }
    
    .progress-group {
        margin-bottom: 1rem;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    
    .balance-stats {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
    }
    
    .stat-item {
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-weight: 600;
        color: #495057;
    }
    
    .stat-value {
        float: right;
        font-weight: 600;
    }
    
    /* Form styling */
    .form-control {
        border-radius: 0.25rem;
    }
    
    .custom-file-label::after {
        content: "Browse";
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.25rem;
        padding: 0.75rem 1.25rem;
    }
    
    /* Button styling */
    .btn {
        border-radius: 0.25rem;
        font-weight: 500;
    }
    
    .btn-primary {
        background-color: #3f80ea;
        border-color: #3f80ea;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-box {
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Original JavaScript code remains unchanged -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysInput = document.getElementById('total_days');
    const daysBreakdownElement = document.getElementById('days_breakdown');
    const leaveTypeSelect = document.getElementById('leave_type_id');
    const submitButton = document.querySelector('button[type="submit"]');

    // Leave balance data this is normally coming from backend)
    const leaveBalances = @json($leaveBalances ?? []);
    
    // Example leave balance structure - actual data from controller
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
        document.getElementById('available-days-display').textContent = balance.available;
        document.getElementById('remaining-days').textContent = balance.available - (document.getElementById('total_days')?.value || 0);
        
        // Calculate progress
        const progressPercent = (balance.available / balance.allocated) * 100;
        document.getElementById('available-progress').style.width = `${progressPercent}%`;
        
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