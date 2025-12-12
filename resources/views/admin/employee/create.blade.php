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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none"><i class="bx bx-home-alt"></i></a></li>
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
                                                <option value="{{ $level->id }}" @selected(old('level_id') == $level->id)>Grade Level {{ $level->level }}</option>
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
                                                <option value="{{ $step->id }}" @selected(old('step_id') == $step->id)>Step {{ $step->step }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('step_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                 <div class="col-md-4">
                                    <label for="mda_id" class="form-label text-dark fw-medium">MDA <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-building text-primary"></i></span>
                                        <select id="mda_id" name="mda_id" class="form-select" required>
                                    <option value="">Select MDA</option>
                                    @foreach($mdas as $mda)
                                        <option value="{{ $mda->id }}" 
                                            @selected(old('mda_id', $employee->mda_id ?? '') == $mda->id)>
                                            {{ $mda->mda }}
                                        </option>
                                    @endforeach
                                </select>
                                    </div>
                                    @error('mda_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="department_id" class="form-label text-dark fw-medium">Department <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-ladder text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0" id="department_id" name="department_id" required>
    <option value="">Select Department</option>
</select>

                                    </div>
                                    @error('department_id')
                                        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label for="unit_id" class="form-label text-dark fw-medium">Unit <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-ladder text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('unit_id') is-invalid @enderror"  name="unit_id" id="unitSelect" required>
                                            <option value="">-- select unit --</option>
                                            {{-- @foreach($units as $unit) --}}
                                                {{-- <option value="{{ $unit->id }}" @selected(old('unit_id') == $unit->id)>{{ $unit->unit_name }}</option> --}}
                                            {{-- @endforeach --}}
                                        </select>
                                    </div>
                                    @error('unit_id')
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

                    <!-- Additional Information Card -->
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
            <!-- Religion -->
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

<!-- STATE OF ORIGIN -->
<div class="col-md-4">
    <label for="state_id" class="form-label text-dark fw-medium">State of Origin</label>
    <div class="input-group input-group-seamless">
        <span class="input-group-text bg-light border-end-0">
            <i class="bx bx-map-pin text-primary"></i>
        </span>
        <select name="state_id" id="state_id" class="form-select border-start-0 ps-0 @error('state_id') is-invalid @enderror" required>
            <option value="">-- Select State --</option>
            @foreach ($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state }}</option>
            @endforeach
        </select>
    </div>
    @error('state_id')
        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
    @enderror
</div>

<!-- LGA OF ORIGIN -->
<div class="col-md-4">
    <label for="lga" class="form-label text-dark fw-medium">LGA of Origin</label>
    <div class="input-group input-group-seamless">
        <span class="input-group-text bg-light border-end-0">
            <i class="bx bx-map text-primary"></i>
        </span>
        <select name="lga" id="lga" class="form-select border-start-0 ps-0 @error('lga') is-invalid @enderror" required>
            <option value="">-- Select LGA --</option>
        </select>
    </div>
    @error('lga')
        <div class="invalid-feedback d-block small mt-1"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
    @enderror
</div>


            <!-- Benue LGA of Origin -->
            {{-- <div class="col-md-4">
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
            </div> --}}

            <!-- Qualifications -->
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

            <!-- Net Pay -->
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

            <!-- Password -->
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

            <!-- Confirm Password -->
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
                                    <span>Contact Office of Head of Service for help</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    const stateIdToName = {
@foreach ($states as $state)
    "{{ $state->id }}": "{{ $state->state }}",
@endforeach
};

const stateLGA = {
   "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East", "Ukwa West", "Umuahia North", "Umuahia South", "Umunneochi"],
    "Adamawa": ["Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Guyuk", "Hong", "Jada", "Lamurde", "Madagali", "Maiha", "Mayo-Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"],
    "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno", "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo", "Mkpat-Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron", "Oruk Anam", "Udung-Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"],
    "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"],
    "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Warji", "Zaki"],
    "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"],
    "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala", "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo", "Tarka", "Ukum", "Ushongo", "Vandeikya"],
    "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala", "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"],
    "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakurr", "Yala"],
    "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West", "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South", "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"],
    "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"],
    "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba-Okha", "Oredo", "Orhionmwon", "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"],
    "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin", "Ido-Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"],
    "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo-Etiti", "Igbo-Eze North", "Igbo-Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River", "Udenu", "Udi", "Uzo-Uwani"],
    "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],
    "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu/Deba"],
    "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre", "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Onuimo", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"],
    "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel", "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kaugama", "Kazaure", "Kiri Kasama", "Kiyawa", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"],
    "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"],
    "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu", "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale", "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura", "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"],
    "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja", "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita", "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi", "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"],
    "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai", "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu/Danko", "Yauri", "Zuru"],
    "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu", "Kabba/Bunu", "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"],
    "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"],
    "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],
    "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Egon", "Obi", "Toto", "Wamba"],
    "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Moya", "Paikoro", "Rafi", "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"],
    "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu", "Yewa North", "Yewa South"],
    "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo", "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"],
    "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo", "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun", "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"],
    "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"],
    "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang North", "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"],
    "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny", "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor", "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"],
    "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa", "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"],
    "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi", "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"],
    "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"],
    "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau", "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Tsafe", "Zurmi"]
};

document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('state_id');
    const lgaSelect = document.getElementById('lga');

    stateSelect.addEventListener('change', function () {
        const selectedStateId = this.value;
        const selectedStateName = stateIdToName[selectedStateId];
        lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';

        if (stateLGA[selectedStateName]) {
            stateLGA[selectedStateName].forEach(function (lga) {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                lgaSelect.appendChild(option);
            });
        }
    });

    // For retaining old values after validation error
    const oldStateId = "{{ old('state_id') }}";
    const oldLga = "{{ old('lga') }}";
    if (oldStateId && stateIdToName[oldStateId]) {
        const stateName = stateIdToName[oldStateId];
        if (stateLGA[stateName]) {
            stateLGA[stateName].forEach(function (lga) {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                if (oldLga === lga) option.selected = true;
                lgaSelect.appendChild(option);
            });
        }
    }
});
</script>


{{--  Dynamic Unit Loading Based on Department Selection --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const mdaSelect = document.getElementById('mda_id');
    const departmentSelect = document.getElementById('department_id');
    const unitSelect = document.getElementById('unitSelect');

    const selectedDepartmentId = "{{ old('department_id', $employee->department_id ?? '') }}";
    const selectedUnitId = "{{ old('unit_id', $employee->unit_id ?? '') }}";

    // Load departments on edit page
    if (mdaSelect.value) {
        loadDepartments(mdaSelect.value, true);
    }

    // When MDA changes
    mdaSelect.addEventListener('change', function () {
        loadDepartments(this.value, false);
    });

    // When department changes
    departmentSelect.addEventListener('change', function () {
        loadUnits(this.value, false);
    });

    function loadDepartments(mdaId, isEditMode) {
        departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
        unitSelect.innerHTML = '<option value="">Select Unit</option>';

        if (!mdaId) {
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            return;
        }

        fetch(`/get-departments-by-mda/${mdaId}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {
            departmentSelect.innerHTML = '<option value="">Select Department</option>';

            data.forEach(dept => {
                const opt = document.createElement('option');
                opt.value = dept.id;
                opt.textContent = dept.department_name;

                if (isEditMode && selectedDepartmentId == dept.id) {
                    opt.selected = true;
                    loadUnits(dept.id, true);
                }

                departmentSelect.appendChild(opt);
            });
        });
    }

    function loadUnits(departmentId, isEditMode) {
        unitSelect.innerHTML = '<option value="">Loading units...</option>';

        if (!departmentId) {
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            return;
        }

        fetch(`/get-units-by-department/${departmentId}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {
            unitSelect.innerHTML = '<option value="">Select Unit</option>';

            data.forEach(unit => {
                const opt = document.createElement('option');
                opt.value = unit.id;
                opt.textContent = unit.unit_name;

                if (isEditMode && selectedUnitId == unit.id) {
                    opt.selected = true;
                }

                unitSelect.appendChild(opt);
            });
        });
    }
});
</script>


@endsection