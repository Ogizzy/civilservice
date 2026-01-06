@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    {{-- Breadcrumb --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">MDA Heads</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Manage MDA Heads</li>
                </ol>
            </nav>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">Manage MDA Heads</h6>
    <hr>

    {{-- Card --}}
    <div class="card">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text"
                           class="form-control ps-5 radius-30"
                           placeholder="Search MDA..."
                           id="unitSearch">
                    <span class="position-absolute top-50 translate-middle-y ms-3">
                        <i class="bx bx-search"></i>
                    </span>
                </div>

                <div class="ms-auto">
                    <a href="{{ route('mdas.index') }}"
                       class="btn btn-primary radius-30 mt-2 mt-lg-0">
                        <i class="bx bxs-building"></i> Manage MDAs
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>MDA</th>
                            <th>Current MDA Head</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mdas as $mda)
                            <tr>
                                <td>{{ $mda->mda }}</td>
                                <td>
                                    @if($mda->head)
                                        <span class="badge bg-success">
                                            {{ $mda->head->surname }}
                                            {{ $mda->head->first_name }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Not Assigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-primary assign-btn"
                                            data-id="{{ $mda->id }}"
                                            data-name="{{ $mda->mda }}"
                                            data-current="{{ $mda->head?->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignMdaHeadModal">
                                        Assign / Change
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No MDAs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $mdas->links('pagination::bootstrap-5') }}
                {{ $mdaHeads->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

{{-- ===================== MODAL ===================== --}}
<div class="modal fade" id="assignMdaHeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" style="color: white;">Assign MDA Head</h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="assignMdaHeadForm">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">MDA Name</label>
                        <input type="text"
                               class="form-control"
                               id="mdaName"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select MDA Head</label>
                        <select name="user_id"
                                id="mdaHeadSelect"
                                class="form-select"
                                required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->surname }} {{ $user->first_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Only users with MDA Head role can be assigned
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-primary btn-sm">
                        Save Assignment
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ===================== SCRIPT ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            const mdaId = this.dataset.id;
            const mdaName = this.dataset.name;
            const currentHead = this.dataset.current;

            document.getElementById('mdaName').value = mdaName;

            document.getElementById('assignMdaHeadForm')
                .action = `/mda/mda-heads/${mdaId}`;

            const select = document.getElementById('mdaHeadSelect');
            select.value = currentHead ?? '';
        });
    });

});
</script>

@endsection
