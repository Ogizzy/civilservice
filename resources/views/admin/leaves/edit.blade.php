@extends('admin.admin_dashboard')
@section('admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Leave Application</h3>
                    <div class="card-tools">
                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Details
                        </a>
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Back to List
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('leaves.update', $leave) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Application Status Alert --}}
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Application Status:</strong> {{ ucfirst($leave->status) }}
                            @if($leave->status !== 'pending')
                                <br><small>Note: Only pending applications can be edited.</small>
                            @endif
                        </div>

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
                                            <input type="text" value="{{ $employee->employee_number }}" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" value="{{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name }}" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>MDA</label>
                                            <input type="text" value="{{ $employee->mda->mda ?? 'N/A' }}" class="form-control" readonly>
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
                                            @if(isset($leaveBalances[$leave->leave_type_id]))
                                                @php $balance = $leaveBalances[$leave->leave_type_id] @endphp
                                                <div class="row">
                                                    <div class="col-4 text-center">
                                                        <h5 class="text-primary">{{ $balance->entitled_days }}</h5>
                                                        <small>Entitled</small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <h5 class="text-warning">{{ $balance->used_days }}</h5>
                                                        <small>Used</small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <h5 class="text-success">{{ $balance->remaining_days + $leave->total_days }}</h5>
                                                        <small>Available*</small>
                                                    </div>
                                                </div>
                                                <small class="text-muted">*Including current application days</small>
                                            @else
                                                <p class="text-muted">Select a leave type to view balance</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Original Application Details --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5>Original Application Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Leave Type:</strong><br>
                                                {{ $leave->leaveType->name }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Start Date:</strong><br>
                                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>End Date:</strong><br>
                                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Total Days:</strong><br>
                                                {{ $leave->total_days }} days
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
                                    <select name="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}" {{ (old('leave_type_id', $leave->leave_type_id) == $type->id) ? 'selected' : '' }}>
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
                                           value="{{ old('start_date', $leave->start_date) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $leave->end_date) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Total Days</label>
                                    <input type="text" id="total_days" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" 
                                              placeholder="Enter reason for leave" required>{{ old('reason', $leave->reason) }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_address">Contact Address (While on Leave)</label>
                                    <textarea name="contact_address" id="contact_address" rows="2" class="form-control @error('contact_address') is-invalid @enderror" 
                                              placeholder="Enter your contact address while on leave">{{ old('contact_address', $leave->contact_address) }}</textarea>
                                    @error('contact_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone (While on Leave)</label>
                                    <input type="tel" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           value="{{ old('contact_phone', $leave->contact_phone) }}" placeholder="Enter your contact phone number">
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
                                           value="{{ old('emergency_contact', $leave->emergency_contact) }}" placeholder="Enter emergency contact name">
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Contact Phone</label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                           value="{{ old('emergency_phone', $leave->emergency_phone) }}" placeholder="Enter emergency contact phone number">
                                    @error('emergency_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Supporting Document</h5>
                                
                                @if($leave->supporting_document_url)
                                    <div class="alert alert-info">
                                        <i class="fas fa-file"></i>
                                        <strong>Current Document:</strong> {{ $leave->supporting_document_name }}
                                        <br><small>Upload a new file to replace the current document</small>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="supporting_document">Supporting Document 
                                        @if(!$leave->supporting_document_url)(Optional)@else(Replace Current)@endif
                                    </label>
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
                                    <i class="fas fa-save"></i> Update Leave Application
                                </button>
                                <a href="{{ route('leaves.show', $leave) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                
                                @if($leave->status === 'pending')
                                    <div class="float-right">
                                        <form method="POST" action="{{ route('leaves.cancel', $leave) }}" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to cancel this leave application?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-ban"></i> Cancel Application
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Calculate total days when dates change
    $('#start_date, #end_date').on('change', function() {
        calculateTotalDays();
    });

    // Load leave balance when leave type changes
    $('#leave_type_id').on('change', function() {
        var leaveTypeId = $(this).val();
        if (leaveTypeId) {
            loadLeaveBalance(leaveTypeId);
        } else {
            $('#leave-balance-info').html('<p class="text-muted">Select a leave type to view balance</p>');
        }
    });

    // Function to calculate total days
    function calculateTotalDays() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);
            
            if (end >= start) {
                var timeDiff = end.getTime() - start.getTime();
                var dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include both start and end dates
                $('#total_days').val(dayDiff + ' days');
            } else {
                $('#total_days').val('');
            }
        } else {
            $('#total_days').val('');
        }
    }

    // Function to load leave balance via AJAX
    function loadLeaveBalance(leaveTypeId) {
        $.ajax({
            url: "{{ url('/leaves/balance') }}",
            method: 'GET',
            data: { leave_type_id: leaveTypeId },
            success: function(response) {
                // Add current application days back to available balance for editing
                var currentAppDays = {{ $leave->total_days }};
                var availableDays = response.remaining_days + currentAppDays;
                
                var html = '<div class="row">';
                html += '<div class="col-4 text-center">';
                html += '<h5 class="text-primary">' + response.entitled_days + '</h5>';
                html += '<small>Entitled</small>';
                html += '</div>';
                html += '<div class="col-4 text-center">';
                html += '<h5 class="text-warning">' + response.used_days + '</h5>';
                html += '<small>Used</small>';
                html += '</div>';
                html += '<div class="col-4 text-center">';
                html += '<h5 class="text-success">' + availableDays + '</h5>';
                html += '<small>Available*</small>';
                html += '</div>';
                html += '</div>';
                html += '<small class="text-muted">*Including current application days</small>';
                
                $('#leave-balance-info').html(html);
            },
            error: function() {
                $('#leave-balance-info').html('<p class="text-danger">Error loading leave balance</p>');
            }
        });
    }

    // Set minimum end date when start date changes
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });

    // Calculate total days on page load
    calculateTotalDays();

    // Set initial minimum end date
    if ($('#start_date').val()) {
        $('#end_date').attr('min', $('#start_date').val());
    }
});
</script>



<style>
.card-header h4, .card-header h5 {
    margin: 0;
    font-size: 1.1rem;
}

#leave-balance-info h5 {
    font-size: 1.5rem;
    margin-bottom: 0;
}

#leave-balance-info small {
    color: #6c757d;
    font-weight: 500;
}

.form-group label {
    font-weight: 600;
}

.text-danger {
    font-weight: 500;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.btn {
    border-radius: 4px;
}

.bg-light {
    background-color: #f8f9fa!important;
}

.alert {
    border-radius: 4px;
}
</style>

@endsection
