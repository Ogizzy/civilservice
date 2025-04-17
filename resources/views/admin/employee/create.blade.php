@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Create New Employee</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Employee Form</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->


<div class="container">
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Basic Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee_number">Employee Number *</label>
                            <input type="text" class="form-control @error('employee_number') is-invalid @enderror" 
                                   id="employee_number" name="employee_number" value="{{ old('employee_number') }}" placeholder="Enter Employee Number" required>
                            @error('employee_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="surname">Surname *</label>
                            <input type="text" class="form-control @error('surname') is-invalid @enderror" 
                                   id="surname" name="surname" value="{{ old('surname') }}" placeholder="Enter Surname" required>
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter First Name" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                   id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter Middle Name">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" placeholder="Enter e-Mail Address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" maxlength="11" placeholder="Enter Phone Number">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" 
                                   id="dob" name="dob" value="{{ old('dob') }}">
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                                <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marital_status">Marital Status</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single" @selected(old('marital_status') == 'Single')>Single</option>
                                <option value="Married" @selected(old('marital_status') == 'Married')>Married</option>
                                <option value="Divorced" @selected(old('marital_status') == 'Divorced')>Divorced</option>
                                <option value="Widowed" @selected(old('marital_status') == 'Widowed')>Widowed</option>
                            </select>
                            @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Employment Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mda_id">MDA *</label>
                            <select class="form-select @error('mda_id') is-invalid @enderror" id="mda_id" name="mda_id" required>
                                <option value="">Select MDA</option>
                                @foreach($mdas as $mda)
                                    <option value="{{ $mda->id }}" @selected(old('mda_id') == $mda->id)>{{ $mda->mda }}</option>
                                @endforeach
                            </select>
                            @error('mda_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="paygroup_id">Pay Group *</label>
                            <select class="form-select @error('paygroup_id') is-invalid @enderror" id="paygroup_id" name="paygroup_id" required>
                                <option value="">Select Pay Group</option>
                                @foreach($payGroups as $payGroup)
                                    <option value="{{ $payGroup->id }}" @selected(old('paygroup_id') == $payGroup->id)>{{ $payGroup->paygroup }}</option>
                                @endforeach
                            </select>
                            @error('paygroup_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="level_id">Grade Level *</label>
                            <select class="form-select @error('level_id') is-invalid @enderror" id="level_id" name="level_id" required>
                                <option value="">Select Grade Level</option>
                                @foreach($gradeLevels as $level)
                                    <option value="{{ $level->id }}" @selected(old('level_id') == $level->id)>{{ $level->level }}</option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="step_id">Step *</label>
                            <select class="form-select @error('step_id') is-invalid @enderror" id="step_id" name="step_id" required>
                                <option value="">Select Step</option>
                                @foreach($steps as $step)
                                    <option value="{{ $step->id }}" @selected(old('step_id') == $step->id)>{{ $step->step }}</option>
                                @endforeach
                            </select>
                            @error('step_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_appointment_date">First Appointment Date</label>
                            <input type="date" class="form-control @error('first_appointment_date') is-invalid @enderror" 
                                   id="first_appointment_date" name="first_appointment_date" value="{{ old('first_appointment_date') }}">
                            @error('first_appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="confirmation_date">Confirmation Date</label>
                            <input type="date" class="form-control @error('confirmation_date') is-invalid @enderror" 
                                   id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date') }}">
                            @error('confirmation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="present_appointment_date">Present Appointment Date</label>
                            <input type="date" class="form-control @error('present_appointment_date') is-invalid @enderror" 
                                   id="present_appointment_date" name="present_appointment_date" value="{{ old('present_appointment_date') }}">
                            @error('present_appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="retirement_date">Retirement Date</label>
                            <input type="date" class="form-control @error('retirement_date') is-invalid @enderror" 
                                   id="retirement_date" name="retirement_date" value="{{ old('retirement_date') }}">
                            @error('retirement_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rank">Rank</label>
                            <input type="text" class="form-control @error('rank') is-invalid @enderror" 
                                   id="rank" name="rank" value="{{ old('rank') }}" placeholder="Enter Rank">
                            @error('rank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Additional Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <select class="form-select @error('religion') is-invalid @enderror" id="religion" name="religion">
                                <option value="">Select Religion</option>
                                <option value="Christianity" @selected(old('religion') == 'Christianity')>Christianity</option>
                                <option value="Islam" @selected(old('religion') == 'Islam')>Islam</option>
                                <option value="Traditional" @selected(old('religion') == 'Traditional')>Traditional</option>
                                <option value="Other" @selected(old('religion') == 'Other')>Other</option>
                            </select>
                            @error('religion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lga">LGA of Origin</label>
                            <select class="form-select @error('lga') is-invalid @enderror" id="lga" name="lga">
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
                            @error('lga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="qualifications">Qualifications</label>
                            <input type="text" class="form-control @error('qualifications') is-invalid @enderror" 
                                   id="qualifications" name="qualifications" value="{{ old('qualifications') }}" placeholder="Enter Qualification ">
                            @error('qualifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="net_pay">Net Pay (₦)</label>
                            <input type="number" step="0.01" class="form-control @error('net_pay') is-invalid @enderror" 
                                   id="net_pay" name="net_pay" value="{{ old('net_pay') }}" placeholder="Enter Net Pay">
                            @error('net_pay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Choose Password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passport">Passport Photo</label>
                            <input type="file" class="form-control @error('passport') is-invalid @enderror" 
                                   id="passport" name="passport" accept="image/png, image/jpeg" onchange="previewPassport(this)">
                            @error('passport')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Accepted: JPEG, PNG | Max Size: 2MB</small>
                            <div class="mt-2">
                                <img id="passport-preview" src="#" alt="Preview" style="display:none; max-width: 150px; border: 1px solid #ccc; padding: 5px; border-radius: 5px;" />
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Employee</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

<script>
    function previewPassport(input) {
        const file = input.files[0];
        const preview = document.getElementById('passport-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>

@endsection