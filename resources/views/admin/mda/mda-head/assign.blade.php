@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    {{-- Breadcrumb --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">MDA Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('mda-heads.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Assign MDA Head</li>
                </ol>
            </nav>
        </div>
    </div>

    <hr>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        Assign MDA Head
                    </h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">MDA Name</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $mda->mda }}"
                               readonly>
                    </div>

                    <form method="POST" action="{{ route('mda-heads.update', $mda->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Select MDA Head
                            </label>

                            <select name="user_id"
                                    class="form-select"
                                    required>
                                <option value="">-- Select User --</option>

                                @forelse($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $user->mda_id == $mda->id ? 'selected' : '' }}>
                                        {{ $user->surname }} {{ $user->first_name }}
                                    </option>
                                @empty
                                    <option disabled>No eligible users found</option>
                                @endforelse
                            </select>

                            <small class="text-muted">
                                Only users with MDA Head–level roles can be assigned.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mda-heads.index') }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Back
                            </a>

                            <button class="btn btn-primary btn-sm">
                                <i class="bx bx-save"></i> Save Assignment
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection
