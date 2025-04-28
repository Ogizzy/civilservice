@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Header with improved styling -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center bg-white rounded-lg p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-2 me-3 text-white">
                            <i class="bx bx-user-plus fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Create New Employee</h4>
                    </div>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" class="text-decoration-none">Employees</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="employee-form">
            @csrf

            <div class="row">
                <!-- Left column for form -->
                <div class="col-lg-8">
                    <!-- Basic Information Card with improved styling -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-primary text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-user-circle fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Basic Information</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="employee_number" class="form-label text-dark fw-medium">Employee Number <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-id-card text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('employee_number') is-invalid @enderror" 
                                               id="employee_number" name="employee_number" value="{{ old('employee_number') }}" placeholder="Enter employee ID" required>
                                    </div>
                                    @error('employee_number')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="surname" class="form-label text-dark fw-medium">Surname <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-user text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('surname') is-invalid @enderror" 
                                               id="surname" name="surname" value="{{ old('surname') }}" placeholder="Enter surname" required>
                                    </div>
                                    @error('surname')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label text-dark fw-medium">First Name <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-user text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                    </div>
                                    @error('first_name')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="middle_name" class="form-label text-dark fw-medium">Middle Name</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-user text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('middle_name') is-invalid @enderror" 
                                               id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                    </div>
                                    @error('middle_name')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="email" class="form-label text-dark fw-medium">Email</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-envelope text-primary"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="phone" class="form-label text-dark fw-medium">Phone Number</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-phone text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number" maxlength="11">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="dob" class="form-label text-dark fw-medium">Date of Birth</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-calendar text-primary"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 @error('dob') is-invalid @enderror" 
                                               id="dob" name="dob" value="{{ old('dob') }}">
                                    </div>
                                    @error('dob')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="gender" class="form-label text-dark fw-medium">Gender</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-male-female text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                                            <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                                        </select>
                                    </div>
                                    @error('gender')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="marital_status" class="form-label text-dark fw-medium">Marital Status</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-heart text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                            <option value="">Select Status</option>
                                            <option value="Single" @selected(old('marital_status') == 'Single')>Single</option>
                                            <option value="Married" @selected(old('marital_status') == 'Married')>Married</option>
                                            <option value="Divorced" @selected(old('marital_status') == 'Divorced')>Divorced</option>
                                            <option value="Widowed" @selected(old('marital_status') == 'Widowed')>Widowed</option>
                                        </select>
                                    </div>
                                    @error('marital_status')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details Card with improved styling -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-primary text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-briefcase fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Employment Details</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="mda_id" class="form-label text-dark fw-medium">MDA <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-building text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('mda_id') is-invalid @enderror" id="mda_id" name="mda_id" required>
                                            <option value="">Select MDA</option>
                                            @foreach($mdas as $mda)
                                                <option value="{{ $mda->id }}" @selected(old('mda_id') == $mda->id)>{{ $mda->mda }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('mda_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="paygroup_id" class="form-label text-dark fw-medium">Pay Group <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-group text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('paygroup_id') is-invalid @enderror" id="paygroup_id" name="paygroup_id" required>
                                            <option value="">Select Pay Group</option>
                                            @foreach($payGroups as $payGroup)
                                                <option value="{{ $payGroup->id }}" @selected(old('paygroup_id') == $payGroup->id)>{{ $payGroup->paygroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('paygroup_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="level_id" class="form-label text-dark fw-medium">Grade Level <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-trending-up text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('level_id') is-invalid @enderror" id="level_id" name="level_id" required>
                                            <option value="">Select Grade Level</option>
                                            @foreach($gradeLevels as $level)
                                                <option value="{{ $level->id }}" @selected(old('level_id') == $level->id)>{{ $level->level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('level_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="step_id" class="form-label text-dark fw-medium">Step <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-ladder text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('step_id') is-invalid @enderror" id="step_id" name="step_id" required>
                                            <option value="">Select Step</option>
                                            @foreach($steps as $step)
                                                <option value="{{ $step->id }}" @selected(old('step_id') == $step->id)>{{ $step->step }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('step_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="first_appointment_date" class="form-label text-dark fw-medium">First Appointment Date</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-calendar-check text-primary"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 @error('first_appointment_date') is-invalid @enderror" 
                                               id="first_appointment_date" name="first_appointment_date" value="{{ old('first_appointment_date') }}">
                                    </div>
                                    @error('first_appointment_date')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="confirmation_date" class="form-label text-dark fw-medium">Confirmation Date</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-check-circle text-primary"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 @error('confirmation_date') is-invalid @enderror" 
                                               id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date') }}">
                                    </div>
                                    @error('confirmation_date')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="present_appointment_date" class="form-label text-dark fw-medium">Present Appointment Date</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-calendar-star text-primary"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 @error('present_appointment_date') is-invalid @enderror" 
                                               id="present_appointment_date" name="present_appointment_date" value="{{ old('present_appointment_date') }}">
                                    </div>
                                    @error('present_appointment_date')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- <div class="col-md-4">
                                    <label for="retirement_date" class="form-label text-dark fw-medium">Retirement Date</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-calendar-x text-primary"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 @error('retirement_date') is-invalid @enderror" 
                                               id="retirement_date" name="retirement_date" value="{{ old('retirement_date') }}">
                                    </div>
                                    @error('retirement_date')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                
                                <div class="col-md-4">
                                    <label for="rank" class="form-label text-dark fw-medium">Rank</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-medal text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('rank') is-invalid @enderror" 
                                               id="rank" name="rank" value="{{ old('rank') }}" placeholder="Enter rank">
                                    </div>
                                    @error('rank')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Card with improved styling -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-primary text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-info-circle fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Additional Information</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="religion" class="form-label text-dark fw-medium">Religion</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-church text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('religion') is-invalid @enderror" id="religion" name="religion">
                                            <option value="">Select Religion</option>
                                            <option value="Christianity" @selected(old('religion') == 'Christianity')>Christianity</option>
                                            <option value="Islam" @selected(old('religion') == 'Islam')>Islam</option>
                                        </select>
                                    </div>
                                    @error('religion')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="lga" class="form-label text-dark fw-medium">LGA of Origin</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-map text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('lga') is-invalid @enderror" id="lga" name="lga">
                                            <option value="">-- Select LGA --</option>
                                            @php
                                                $lgas = [
                                                    'Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West',
                                                    'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo',
                                                    'Ohimini', 'Oju', 'Okpokwu', 'Otukpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'
                                                ];
                                            @endphp
                                            @foreach($lgas as $lga)
                                                <option value="{{ $lga }}" {{ old('lga') == $lga ? 'selected' : '' }}>{{ $lga }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('lga')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="qualifications" class="form-label text-dark fw-medium">Qualifications</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-certification text-primary"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('qualifications') is-invalid @enderror" 
                                               id="qualifications" name="qualifications" value="{{ old('qualifications') }}" placeholder="e.g. BSc, HND, SSCE">
                                    </div>
                                    @error('qualifications')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="net_pay" class="form-label text-dark fw-medium">Net Pay (₦)</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-money text-primary"></i></span>
                                        <input type="number" step="0.01" class="form-control border-start-0 ps-0 @error('net_pay') is-invalid @enderror" 
                                               id="net_pay" name="net_pay" value="{{ old('net_pay') }}" placeholder="0.00">
                                    </div>
                                    @error('net_pay')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="password" class="form-label text-dark fw-medium">Password</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock text-primary"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="••••••••">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="password_confirmation" class="form-label text-dark fw-medium">Confirm Password</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock-open text-primary"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0" 
                                               id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons with improved styling -->
                    <div class="d-flex gap-2 mb-4">
                        <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center">
                            <i class="bx bx-save me-2"></i> Create Employee
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-warning px-4 py-2 d-flex align-items-center">
                            <i class="bx bx-x me-2"></i> Cancel
                        </a>
                    </div>
                </div>
                
                <!-- Right column for photo and summary with improved styling -->
                <div class="col-lg-4">
                    <!-- Photo Upload Card with improved styling -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-primary text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-image fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Passport Photo</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="passport-container mb-3 mx-auto position-relative" style="width: 200px; height: 200px; border-radius: 12px; overflow: hidden; background-color: #f8f9fa; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 2px dashed #c9cfd6; transition: all 0.3s ease;">
                                <img id="passport-preview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                <div id="upload-placeholder" class="d-flex flex-column align-items-center justify-content-center text-center p-3" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;">
                                    <div class="rounded-circle bg-light p-3 mb-3">
                                        <i class="bx bx-camera text-primary" style="font-size: 36px;"></i>
                                    </div>
                                    <p class="text-muted mb-0">Click to upload passport photo</p>
                                    <span class="badge bg-light text-primary mt-2">3:4 ratio recommended</span>
                                </div>
                            </div>
                            
                            <label for="passport" class="btn btn-outline-primary mb-2 px-4 d-inline-flex align-items-center">
                                <i class="bx bx-upload me-2"></i> Choose File
                            </label>
                            <input type="file" class="form-control d-none @error('passport') is-invalid @enderror" 
                                   id="passport" name="passport" accept="image/png, image/jpeg" onchange="previewPassport(this)">
                            <div class="small text-muted mt-2 p-2 bg-light rounded">
                                <i class="bx bx-info-circle me-1"></i> Accepted: JPEG, PNG | Max Size: 2MB
                            </div>
                            @error('passport')
                                <div class="invalid-feedback d-block mt-2 bg-danger bg-opacity-10 text-danger p-2 rounded">
                                    <i class="bx bx-error-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                  
                    
                    <!-- New card: Progress Tracker -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-success text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-list-check fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Form Completion</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark fw-medium">Basic Information</span>
                                    <span class="badge bg-success" id="basic-info-badge">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" id="basic-info-progress" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark fw-medium">Employment Details</span>
                                    <span class="badge bg-success" id="employment-badge">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" id="employment-progress" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark fw-medium">Additional Information</span>
                                    <span class="badge bg-success" id="additional-badge">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" id="additional-progress" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark fw-bold">Overall Completion</span>
                                    <span class="badge bg-primary" id="overall-badge">0%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" id="overall-progress" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                      <!-- Quick Tips Card with improved styling -->
                      <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header position-relative p-0">
                            <div class="bg-gradient-info text-white py-3 px-4 rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                        <i class="bx bx-bulb fs-4 text-white"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-semibold">Quick Tips</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center px-0 py-3 border-bottom">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bx bx-check-circle text-primary"></i>
                                    </div>
                                    <span>Fields marked with <span class="text-danger">*</span> are mandatory</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center px-0 py-3 border-bottom">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bx bx-lock text-primary"></i>
                                    </div>
                                    <span>Password should be at least 8 characters</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center px-0 py-3 border-bottom">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bx bx-camera text-primary"></i>
                                    </div>
                                    <span>Use a professional passport photograph</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center px-0 py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bx bx-help-circle text-primary"></i>
                                    </div>
                                    <span>Contact Office of Head of Service for help with grade levels</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>


@endsection