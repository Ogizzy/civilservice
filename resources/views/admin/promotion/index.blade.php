@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Promotions</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Promotions</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
           
 @if (auth()->user()->role->role === 'Employee')
      <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Promotion History for:<span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h6>
                <a href="{{ route('employee.dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fadeIn animated bx bx-chevrons-left"></i>Back
                </a>
          </div>
        @else
        <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Promotion History for:<span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h6>
                <a href="{{ route('employees.promotions.create', $employee->id) }}" class="btn btn-primary btn-sm">
                    <i class="lni lni-circle-plus"></i>New Promotion
                </a>
            </div>
         @endif
            

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">

                     <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="{{ route('employees.promotions.index', $employee->id) }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search Promotion Type...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="employeesTable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Type</th>
                                    <th>Effective Date</th>
                                    <th>Promoted By</th>
                                    <th>Document</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>GL {{ $promotion->previousLevel->level ?? 'N/A' }} - Step
                                            {{ $promotion->previousStep->step ?? 'N/A' }}</td>
                                        <td>GL {{ $promotion->currentLevel->level ?? 'N/A' }} - Step
                                            {{ $promotion->currentStep->step ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($promotion->promotion_type) }}</td>
                                        <td>{{ $promotion->effective_date->format('d M, Y') }}</td>
                                        <td>{{ $promotion->user->surname ?? 'N/A' }} {{ $promotion->user->first_name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $promotion->document->document) }}" target="_blank"
                                                class="btn btn-sm btn-secondary" title="View Document">
                                                <i class="lni lni-eye"> View</i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.promotions.show', [$employee->id, $promotion->id]) }}"
                                                class="btn btn-sm btn-secondary">Details</a>
                                                
                                            @if(auth()->user()->role->role != 'Employee')
                                                <form
                                                action="{{ route('employees.promotions.destroy', [$employee->id, $promotion->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger delete-btn"><i class="bx bxs-trash" title="Delete This Promotion"></i></button>
                                            </form>
                                              @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No promotion records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $promotions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#employeesTable').DataTable({
                        paging: false, // ❌ Disable DataTables pagination
                        searching: false, // ❌ Disable DataTables search (we use Laravel search)
                        info: false,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-sm btn-warning'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm btn-success'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-sm btn-info'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-sm btn-danger'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-sm btn-primary'
                            }
                        ]
                    });

                    // Move buttons to custom div
                    table.buttons().container().appendTo('#exportButtons');
                });
            </script>
@endsection
