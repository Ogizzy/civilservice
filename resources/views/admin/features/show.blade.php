@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Features</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Feature Details</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ url()->previous() }}" class="btn btn-primary px-5 radius-30">
                <i class="bx bx-arrow-back me-1"></i>Back
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="feature-header mb-4">
                <h4 class="mb-3 text-primary"><i class="bx bx-star me-2"></i>Feature Details</h4>
                
                <div class="feature-info p-4 mb-4 bg-light-primary rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="d-block text-muted small">Feature Name</strong>
                                <span class="h5">{{ $feature->feature }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="d-block text-muted small">Description</strong>
                                <span>{{ $feature->description ?: 'No description provided' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="permissions-section">
                <h5 class="mb-4 text-primary"><i class="bx bx-lock-alt me-2"></i>Role Permissions</h5>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feature->userPermissions as $permission)
                                <tr>
                                    <td>{{ $permission->role->role }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $permission->can_create ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $permission->can_create ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $permission->can_edit ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $permission->can_edit ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $permission->can_delete ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $permission->can_delete ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No permissions assigned yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .feature-info p {
        margin-bottom: 0.5rem;
    }
    .feature-info strong {
        font-size: 0.85rem;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge {
        min-width: 45px;
        padding: 5px 10px;
    }
</style>

@endsection