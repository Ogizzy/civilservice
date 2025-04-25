@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="card radius-10">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Details</h5>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">← Back</a>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Surname:</strong> {{ $user->surname }}
                </div>
                <div class="col-md-6">
                    <strong>First Name:</strong> {{ $user->first_name }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Other Names:</strong> {{ $user->other_names ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Role:</strong> {{ $user->role->role ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    @php
                        $badge = match($user->status) {
                            'active' => 'success',
                            'suspended' => 'warning',
                            'banned' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst($user->status) }}</span>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                 data-bs-target="#editUserModal{{ $user->id }}"><i class="bx bxs-edit" title="Edit This User"></i></button>
                {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form> --}}
            </div>

        </div>
    </div>
</div>

@include('admin.users.modals.edit', ['user' => $user])

@endsection
