@extends('admin.admin_dashboard')
@section('admin')

    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center shadow-sm rounded-top px-4 py-3">
                            <h4 class="mb-0 font-weight-bold">
                                <i class="fas fa-user-circle mr-2"></i> Employee Details
                            </h4>

                            <div class="d-flex gap-2">
                                <a href="{{ route('employees.edit', $employee->id) }}"
                                    class="btn btn-light btn-sm d-flex align-items-center shadow-sm border-0">
                                    <i class="fas fa-edit mr-1 text-primary"></i> Edit
                                </a>
                                <a href="{{ route('employees.index') }}"
                                    class="btn btn-light btn-sm d-flex align-items-center shadow-sm border-0">
                                    <i class="fas fa-chevron-left mr-1 text-danger"></i>
                                    Back
                                </a>
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="row">
                                <!-- Employee Photo and Basic Info -->
                                <div class="col-md-3 text-center">
                                    <div class="mb-3">
                                        @if ($employee->passport)
                                            <img src="{{ asset('storage/' . $employee->passport) }}"
                                                class="img-thumbnail rounded-circle"
                                                style="width: 200px; height: 200px; object-fit: cover;"
                                                alt="Employee Photo">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle"
                                                style="width: 200px; height: 200px;">
                                                <i class="fas fa-user fa-5x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="mb-1">{{ $employee->surname }} {{ $employee->first_name }}</h4>
                                    <p class="text-muted"><b>Employee:</b> {{ $employee->employee_number }}</p>
                                    <span class="badge badge-primary">{{ $employee->mda->mda ?? 'N/A' }}</span>
                                </div>

                                <!-- Detailed Information -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <!-- Personal Info -->
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Personal Information</h5>
                                            @php
                                                $dob = $employee->dob ? $employee->dob->format('d M, Y') : 'N/A';
                                            @endphp
                                            <p><strong>Full Name:</strong> {{ $employee->surname }}
                                                {{ $employee->first_name }} {{ $employee->middle_name ?? '' }}</p>
                                            <p><strong>Gender:</strong> {{ $employee->gender ?? 'N/A' }}</p>
                                            <p><strong>Date of Birth:</strong> {{ $dob }}</p>
                                            <p><strong>Marital Status:</strong> {{ $employee->marital_status ?? 'N/A' }}
                                            </p>
                                            <p><strong>Religion:</strong> {{ $employee->religion ?? 'N/A' }}</p>
                                            <p><strong>LGA of Origin:</strong> {{ $employee->lga ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Contact Info -->
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Contact Information</h5>
                                            <p><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</p>
                                            <p><strong>Phone:</strong> {{ $employee->phone ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <!-- Employment Info -->
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Employment Details</h5>
                                            <p><strong>MDA:</strong> {{ $employee->mda->mda ?? 'N/A' }}</p>
                                            <p><strong>Employee Number:</strong> {{ $employee->employee_number }}</p>
                                            <p><strong>Pay Group:</strong> {{ $employee->paygroup->paygroup ?? 'N/A' }}</p>
                                            <p><strong>Grade Level:</strong> {{ $employee->gradeLevel->level ?? 'N/A' }}
                                            </p>
                                            <p><strong>Step:</strong> {{ $employee->step->step ?? 'N/A' }}</p>
                                            <p><strong>Rank:</strong> {{ $employee->rank ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Service Dates -->
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Service Dates</h5>
                                            <p><strong>First Appointment:</strong>
                                                {{ $employee->first_appointment_date ? $employee->first_appointment_date->format('d M, Y') : 'N/A' }}
                                            </p>
                                            <p><strong>Confirmation Date:</strong>
                                                {{ $employee->confirmation_date ? $employee->confirmation_date->format('d M, Y') : 'N/A' }}
                                            </p>
                                            <p><strong>Present Appointment:</strong>
                                                {{ $employee->present_appointment_date ? $employee->present_appointment_date->format('d M, Y') : 'N/A' }}
                                            </p>
                                            <p><strong>Retirement Date:</strong>
                                                {{ $employee->retirement_date ? $employee->retirement_date->format('d M, Y') : 'N/A' }}
                                                @if ($employee->retirement_date && $employee->retirement_date->isPast())
                                                    <span class="badge badge-danger ml-2">Retired</span>
                                                @elseif($employee->retirement_date && $employee->retirement_date->diffInMonths(now()) <= 6)
                                                    <span class="badge badge-warning ml-2">Retiring Soon</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Financial Information</h5>
                                            <p><strong>Net Pay:</strong>
                                                {{ $employee->net_pay ? '₦' . number_format($employee->net_pay, 2) : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-2">Qualifications</h5>
                                            <p>{{ $employee->qualifications ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs for Documents, Transfers, Promotions -->
                        <div class="card-footer">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-documents" data-bs-toggle="tab" href="#documents">Documents</a>
                                </li>
                                <li class="nav-item">

                                    <a class="nav-link" data-bs-toggle="tab" href="#transfers">Transfer History</a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-promotions" data-bs-toggle="tab" href="#promotions">Promotion History</a>
                                </li>
                            </ul>
                            
                            <div class="tab-content p-3 border border-top-0">

                                <div class="tab-pane fade show active" id="documents">
                                    @if ($documents->count())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>Date Uploaded</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documents as $document)
                                                    <tr>
                                                        <td>{{ $document->document_type }}</td>
                                                        <td>{{ $document->created_at->format('d M, Y') }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/' . $document->document)}}"
                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="lni lni-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        {{-- Paginate with Bootstrap-5 --}}
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $documents->appends(request()->except('documents'))->links('pagination::bootstrap-5') }}
                                        </div>
                                    @else
                                        <div class="alert alert-info">No documents found.</div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="transfers">
                                    @if ($transfers->count())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>From MDA</th>
                                                    <th>To MDA</th>
                                                    <th>Effective Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transfers as $transfer)
                                                    <tr>
                                                        <td>{{ $transfer->previousMda->mda ?? 'N/A' }}</td>
                                                        <td>{{ $transfer->currentMda->mda ?? 'N/A' }}</td>
                                                        <td>{{ $transfer->effective_date->format('d M, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $transfers->links('pagination::bootstrap-5') }}
                                        </div>
                                    @else
                                        <div class="alert alert-info">No transfer history found.</div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="promotions">
                                    @if ($promotions->count())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Previous Grade Level</th>
                                                    <th>New Grade Level</th>
                                                    <th>Promotion Type</th>
                                                    <th>Effective Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($promotions as $promotion)
                                                    <tr>
                                                        <td>GL {{ $promotion->previousLevel->level ?? 'N/A' }} / Step
                                                            {{ $promotion->previousStep->step ?? 'N/A' }}</td>
                                                        <td>GL {{ $promotion->currentLevel->level ?? 'N/A' }} / Step
                                                            {{ $promotion->currentStep->step ?? 'N/A' }}</td>
                                                        <td>{{ $promotion->promotion_type }}</td>
                                                        <td>{{ $promotion->effective_date->format('d M, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $promotions->links('pagination::bootstrap-5') }}
                                        </div>
                                    @else
                                        <div class="alert alert-info">No promotion history found.</div>
                                    @endif

                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>

{{-- <script>
    // Activate tab from URL hash
    document.addEventListener('DOMContentLoaded', function () {
        let hash = window.location.hash;

        if (hash) {
            let tabLink = document.querySelector(`a[href="${hash}"]`);
            if (tabLink) {
                new bootstrap.Tab(tabLink).show();
            }
        }

        // Save hash on tab change
        document.querySelectorAll('.nav-link[data-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                history.replaceState(null, null, e.target.getAttribute('href'));
            });
        });
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle activating the correct tab from the hash
        const hash = window.location.hash;

        if (hash) {
            const tabTrigger = document.querySelector(`a.nav-link[href="${hash}"]`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // When switching tabs, update the URL hash
        document.querySelectorAll('a.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                history.replaceState(null, null, e.target.getAttribute('href'));
            });
        });
    });
</script>


@endsection
