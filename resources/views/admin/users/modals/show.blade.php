@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="card shadow-sm border-0 radius-10">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <i class="bx bx-user-circle fs-4 me-2"></i>
                <h5 class="mb-0">User Details</h5>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary d-flex align-items-center">
                <i class="bx bx-arrow-back me-1"></i> Back to Users
            </a>
        </div>
        
        <div class="card-body p-4">
            <!-- User Profile Header -->
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="avatar avatar-xl bg-light-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                    <span class="fw-bold text-primary fs-4">{{ substr($user->first_name, 0, 1) }}{{ substr($user->surname, 0, 1) }}</span>
                </div>
                <div>
                    <h4 class="mb-0"> {{ $user->surname }} {{ $user->first_name }}</h4>
                    <p class="text-muted mb-0">
                        <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                    </p>
                </div>
                <div class="ms-auto">
                    @php
                        $badge = match($user->status) {
                            'active' => 'success',
                            'suspended' => 'warning',
                            'banned' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2 fs-6">
                        <i class="bx bx-{{ $user->status === 'active' ? 'check-circle' : ($user->status === 'suspended' ? 'pause-circle' : 'x-circle') }} me-1"></i>
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
            
            <!-- User Information Cards -->
            <div class="row g-3">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light-subtle radius-10">
                        <div class="card-body">
                            <h6 class="card-title mb-3 d-flex align-items-center">
                                <i class="bx bx-user-pin me-2 text-primary"></i>Personal Information
                            </h6>
                            <div class="mb-3">
                                <label class="text-muted small">Surname</label>
                                <p class="mb-0 fw-medium">{{ $user->surname }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">First Name</label>
                                <p class="mb-0 fw-medium">{{ $user->first_name }}</p>
                            </div>
                            <div>
                                <label class="text-muted small">Other Names</label>
                                <p class="mb-0 fw-medium">{{ $user->other_names ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Information -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light-subtle radius-10">
                        <div class="card-body">
                            <h6 class="card-title mb-3 d-flex align-items-center">
                                <i class="bx bx-shield-quarter me-2 text-primary"></i>Account Information
                            </h6>
                            <div class="mb-3">
                                <label class="text-muted small">Email Address</label>
                                <p class="mb-0 fw-medium">{{ $user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Role</label>
                                <p class="mb-0 fw-medium">
                                    <span class="badge bg-info text-dark rounded-pill px-3">
                                        <i class="bx bx-id-card me-1"></i>
                                        {{ $user->role->role ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-muted small">Member Since</label>
                                <p class="mb-0 fw-medium">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex mt-4 gap-2">
                <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                    <i class="bx bx-edit me-1"></i> Edit User
                </button>
                                            
                {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger d-flex align-items-center" 
                            onclick="confirmDelete(this.parentElement)">
                        <i class="bx bx-trash me-1"></i> Delete
                    </button>
                </form> --}}
              
            </div>
        </div>
    </div>
</div>

@include('admin.users.modals.edit', ['user' => $user])
@endsection

