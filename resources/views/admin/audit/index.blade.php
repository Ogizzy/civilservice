@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <h4 class="mb-4"><i class="bx bx-history"></i> Audit Trail</h4>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>User</label>
            <select name="user_id" class="form-select">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->surname }} {{ $user->first_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Model</label>
            <select name="auditable_type" class="form-select">
                <option value="">All Models</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ request('auditable_type') == $model ? 'selected' : '' }}>
                        {{ class_basename($model) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>From</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label>To</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        </div>
    </form>

    <!-- Audit Table -->
    <div class="card">
        <div class="card-body">
            @if ($audits->count())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>S/N</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Model</th>
                                <th>Changes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($audit->user)->surname }} {{ optional($audit->user)->first_name }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($audit->event) }}</span></td>
                                    <td>{{ class_basename($audit->auditable_type) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAudit{{ $audit->id }}">
                                            View
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalAudit{{ $audit->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Audit Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body row">
                                                        <div class="col-md-6">
                                                            <h6 class="text-danger">Old Values</h6>
                                                            <ul>
                                                                @forelse($audit->old_values as $key => $val)
                                                                    <li><strong>{{ $key }}:</strong> {{ $val }}</li>
                                                                @empty
                                                                    <li><em>No old values</em></li>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-success">New Values</h6>
                                                            <ul>
                                                                @forelse($audit->new_values as $key => $val)
                                                                    <li><strong>{{ $key }}:</strong> {{ $val }}</li>
                                                                @empty
                                                                    <li><em>No new values</em></li>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $audit->created_at->format('d M, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $audits->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-warning">No audit records found.</div>
            @endif
        </div>
    </div>
</div>

@endsection
