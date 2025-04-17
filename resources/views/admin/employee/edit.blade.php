@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Employee Details</h4>
                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            <!-- Passport -->
                            <div class="col-md-3 text-center">
                                <div class="mb-4">
                                    @if($employee->passport)
                                        <img src="{{ asset('storage/' . $employee->passport) }}" 
                                             class="img-thumbnail rounded-circle"
                                             style="width: 200px; height: 200px; object-fit: cover;" 
                                             alt="Passport">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded-circle"
                                             style="width: 200px; height: 200px;">
                                            <i class="fas fa-user fa-5x text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="passport">Upload New Passport</label>
                                    <input type="file" name="passport" class="form-control">
                                </div>
                            </div>

                            <!-- Form fields -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="detail-title">Personal Information</h5>
                                        <div class="mb-3">
                                            <label>Surname</label>
                                            <input type="text" name="surname" class="form-control" value="{{ old('surname', $employee->surname) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>First Name</label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $employee->middle_name) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" value="{{ old('dob', $employee->dob) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Marital Status</label>
                                            <input type="text" name="marital_status" class="form-control" value="{{ old('marital_status', $employee->marital_status) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Religion</label>
                                            <input type="text" name="religion" class="form-control" value="{{ old('religion', $employee->religion) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>LGA of Origin</label>
                                            <input type="text" name="lga" class="form-control" value="{{ old('lga', $employee->lga) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="detail-title">Contact & Work Information</h5>
                                        <div class="mb-3">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Employee Number</label>
                                            <input type="text" name="employee_number" class="form-control" value="{{ $employee->employee_number }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label>Rank</label>
                                            <input type="text" name="rank" class="form-control" value="{{ old('rank', $employee->rank) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Net Pay</label>
                                            <input type="number" step="0.01" name="net_pay" class="form-control" value="{{ old('net_pay', $employee->net_pay) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Qualifications</label>
                                            <textarea name="qualifications" class="form-control">{{ old('qualifications', $employee->qualifications) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="detail-title">Service Dates</h5>
                                        <div class="mb-3">
                                            <label>First Appointment</label>
                                            <input type="date" name="first_appointment_date" class="form-control" value="{{ old('first_appointment_date', $employee->first_appointment_date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Confirmation Date</label>
                                            <input type="date" name="confirmation_date" class="form-control" value="{{ old('confirmation_date', $employee->confirmation_date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Present Appointment</label>
                                            <input type="date" name="present_appointment_date" class="form-control" value="{{ old('present_appointment_date', $employee->present_appointment_date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Retirement Date</label>
                                            <input type="date" name="retirement_date" class="form-control" value="{{ old('retirement_date', $employee->retirement_date) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
