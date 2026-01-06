@extends('admin.admin_dashboard')
@section('admin')

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Employee Postings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Posting History</li>
                    </ol>
                </nav>
            </div>
             <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{ route('employees.posting.create', $employee->id) }}" class="btn btn-primary btn-sm">
                            <i class="bx bxs-plus-square"></i> Iniatiate New Posting
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i> Back to Employees
                        </a>
                    </div>
                </div>
        </div>
        <!--end breadcrumb-->

        <h6 class="mb-0 text-uppercase">Manage Postings</h6>
        <hr>

        {{-- ================= EMPLOYEE SUMMARY ================= --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bx bx-user me-1"></i> Employee Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted">Employee No</label>
                        <div class="form-control bg-light">{{ $employee->employee_number }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Full Name</label>
                        <div class="form-control bg-light">
                            {{ $employee->surname }} {{ $employee->first_name }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Current MDA</label>
                        <div class="form-control bg-light">{{ $employee->mda->mda ?? '—' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Current Department</label>
                        <div class="form-control bg-light">{{ $employee->department->department_name ?? '—' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Current Unit</label>
                        <div class="form-control bg-light">{{ $employee->unit->unit_name ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= POSTINGS TABLE ================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bx bx-list-ul me-1"></i> Posting History
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>MDA</th>
                                <th>From Department</th>
                                <th>From Unit</th>
                                <th>To Department</th>
                                <th>To Unit</th>
                                <th>Posting Type</th>
                                <th>Posted By</th>
                                <th>Posted At</th>
                                <th>Ended At</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postings as $posting)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $posting->mda->mda ?? '—' }}</td>
                                    <td>{{ $posting->fromDepartment->department_name ?? '—' }}</td>
                                    <td>{{ $posting->fromUnit->unit_name ?? '—' }}</td>
                                    <td>{{ $posting->toDepartment->department_name ?? '—' }}</td>
                                    <td>{{ $posting->toUnit->unit_name ?? '—' }}</td>
                                    <td>{{ $posting->posting_type ?? '—' }}</td>
                                    <td>{{ $posting->postedBy->role->role ?? '_' }}</td>
                                    <td>{{ $posting->posted_at ? \Carbon\Carbon::parse($posting->posted_at)->format('d M, Y') : '—' }}
                                    </td>
                                    <td>{{ $posting->ended_at ? \Carbon\Carbon::parse($posting->ended_at)->format('d M, Y') : '—' }}
                                    </td>

                                    <td>
                                        @if ($posting->remarks)
                                            {{ \Str::limit($posting->remarks, 5) }}

                                            @if (strlen($posting->remarks) > 10)
                                                <button class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="modal"
                                                    data-bs-target="#remarksModal{{ $posting->id }}">
                                                    Read more
                                                </button>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('employees.posting.edit', [$employee, $posting]) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('employees.posting.destroy', [$employee, $posting]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger delete-btn" type="submit">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No postings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach ($postings as $posting)
        <div class="modal fade" id="remarksModal{{ $posting->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Reseason for Posting
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-0">
                            {{ $posting->remarks }}
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
