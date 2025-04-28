@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">
        <div class="container py-4">
            <div class="row">
                <!-- Left column for form fields -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header bg-gradient-primary text-white d-flex align-items-center py-3">
                            <i class="bx bx-user-circle fs-3 me-2"></i>
                            <h4 class="mb-0">My Service Account</h4>
                        </div>

                        <div class="card-body p-4">
                            <form action="{{ route('service-account.update') }}" method="POST" enctype="multipart/form-data" id="accountForm">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Personal Information -->
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                                            <i class="bx bx-id-card me-1"></i> Personal Information
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="surname" class="form-label">Surname <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-user"></i></span>
                                            <input type="text" name="surname" id="surname" class="form-control @error('surname') is-invalid @enderror"
                                                value="{{ old('surname', Auth::user()->surname) }}" required>
                                        </div>
                                        @error('surname')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-user"></i></span>
                                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                                value="{{ old('first_name', Auth::user()->first_name) }}" required>
                                        </div>
                                        @error('first_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-user"></i></span>
                                            <input type="text" name="middle_name" id="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                                                value="{{ old('middle_name', Auth::user()->other_names) }}">
                                        </div>
                                        @error('middle_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="gender" class="form-label">Gender</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-male-female"></i></span>
                                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                                <option value="">Select Gender</option>
                                                <option value="Male"
                                                    {{ old('gender', Auth::user()->employee->gender ?? '') == 'Male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="Female"
                                                    {{ old('gender', Auth::user()->employee->gender ?? '') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                        </div>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="marital_status" class="form-label">Marital Status</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-heart"></i></span>
                                            <select name="marital_status" id="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                                <option value="">Select Marital Status</option>
                                                <option value="Single"
                                                    {{ old('marital_status', Auth::user()->employee->marital_status ?? '') == 'Single' ? 'selected' : '' }}>
                                                    Single</option>
                                                <option value="Married"
                                                    {{ old('marital_status', Auth::user()->employee->marital_status ?? '') == 'Married' ? 'selected' : '' }}>
                                                    Married</option>
                                                <option value="Divorced"
                                                    {{ old('marital_status', Auth::user()->employee->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>
                                                    Divorced</option>
                                                <option value="Widowed"
                                                    {{ old('marital_status', Auth::user()->employee->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>
                                                    Widowed</option>
                                            </select>
                                        </div>
                                        @error('marital_status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="religion" class="form-label">Religion</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-church"></i></span>
                                            <input type="text" name="religion" id="religion" class="form-control @error('religion') is-invalid @enderror"
                                                value="{{ old('religion', Auth::user()->employee->religion ?? '') }}">
                                        </div>
                                        @error('religion')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12 mt-4">
                                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                                            <i class="bx bx-envelope me-1"></i> Contact Information
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="lga" class="form-label">LGA of Origin</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-map"></i></span>
                                            <input type="text" name="lga" id="lga" class="form-control @error('lga') is-invalid @enderror"
                                                value="{{ old('lga', Auth::user()->employee->lga ?? '') }}">
                                        </div>
                                        @error('lga')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-phone"></i></span>
                                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', Auth::user()->employee->phone ?? '') }}">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-envelope"></i></span>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', Auth::user()->email) }}">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="contact_address" class="form-label">Contact Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-home"></i></span>
                                            <textarea name="contact_address" id="contact_address" class="form-control @error('contact_address') is-invalid @enderror" rows="2">{{ old('contact_address', Auth::user()->employee->contact_address ?? '') }}</textarea>
                                        </div>
                                        @error('contact_address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Security Section -->
                                    <div class="col-12 mt-4">
                                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                                            <i class="bx bx-lock me-1"></i> Security
                                        </h5>
                                        <p class="text-muted small">Leave password fields blank to keep your current password</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-lock"></i></span>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bx bx-lock-open"></i></span>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right column for photo and action buttons -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header bg-gradient-info text-white d-flex align-items-center py-3">
                            <i class="bx bx-image fs-3 me-2"></i>
                            <h4 class="mb-0">Profile Photo</h4>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-4">
                                <div class="profile-photo-container mx-auto position-relative" style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; border: 3px solid #f0f0f0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <img id="photo-preview" src="{{ Auth::user()->employee->passport ?? asset('backend/images/avatar.jpg') }}" 
                                         class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" alt="Profile Photo">
                                    <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                         style="background: rgba(0,0,0,0.4); opacity: 0; transition: all 0.3s;">
                                        <i class="bx bx-camera text-white" style="font-size: 36px;"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="passport" class="btn btn-outline-primary">
                                    <i class="bx bx-upload me-1"></i> Upload New Photo
                                </label>
                                <input type="file" name="passport" id="passport" form="accountForm" class="d-none" 
                                       accept="image/jpeg, image/png, image/jpg" onchange="previewImage(this)">
                                <div class="text-muted small mt-2">JPEG, PNG or JPG (Max 2MB)</div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" form="accountForm" class="btn btn-primary py-3">
                                    <i class="bx bx-save me-1"></i> Update Account
                                </button>
                                <a href="{{ route('employee.dashboard') }}" class="btn btn-light py-2">
                                    <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity card -->
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-4">
                        <div class="card-header bg-gradient-success text-white d-flex align-items-center py-3">
                            <i class="bx bx-info-circle fs-3 me-2"></i>
                            <h4 class="mb-0">Account Status</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 rounded-circle bg-success bg-opacity-10 me-3">
                                    <i class="bx bx-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Account Active</h6>
                                    <small class="text-muted">Last login: {{ \Carbon\Carbon::now()->format('M d, Y') }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle bg-primary bg-opacity-10 me-3">
                                    <i class="bx bx-shield text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Security</h6>
                                    <small class="text-muted">Regular password updates recommended</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
{{-- SweetAlert for Success --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 5000
    });
</script>
@endif

{{-- SweetAlert for validation errors --}}
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `
                <ul style="list-style:none;padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>🔴 {{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#d33',
        });
    </script>
@endif


@endsection

