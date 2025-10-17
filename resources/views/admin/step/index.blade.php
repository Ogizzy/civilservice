@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
         <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Steps</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Steps</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    </ol>
                </nav>
            </div>

            <div class="mb-0 text-uppercase" style="margin-left: auto;">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStepModal">
                        <i class="lni lni-circle-plus"></i> Add Step
                    </button>
            </div>
        </div>
        <!--end breadcrumb-->
          <h6 class="mb-0 text-uppercase">Manage Steps</h6>

        <hr>

        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">

                     <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="{{ route('steps.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search steps...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>

                    <div class="table-responsive">
                        <table id="StepTable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>Step</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($steps as $index => $step)
                                    <tr>
                                        <td>{{ $steps->firstItem() + $index }}</td>
                                        <td>Step {{ $step->step }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm view-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewStepModal" 
                                                    data-id="{{ $step->id }}"
                                                    data-step="{{ $step->step }}">
                                                <i class="fadeIn animated bx bx-list-ul"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm edit-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editStepModal" 
                                                    data-id="{{ $step->id }}"
                                                    data-step="{{ $step->step }}">
                                                <i class="lni lni-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('steps.destroy', $step->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm delete-btn">
                                                    <i class="lni lni-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No steps found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                       <div class="mt-3">
                           {{ $steps->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Step Modal -->
    <div class="modal fade" id="addStepModal" tabindex="-1" aria-labelledby="addStepModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStepModalLabel">Add New Step</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('steps.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="step" class="form-label">Step Name</label>
                            <input type="text" name="step" id="step" class="form-control" value="{{ old('step') }}" placeholder="Enter Step" required>
                            @error('step') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Step Modal -->
    <div class="modal fade" id="editStepModal" tabindex="-1" aria-labelledby="editStepModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStepModalLabel">Edit Step</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStepForm" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_step" class="form-label">Step Name</label>
                            <input type="text" name="step" id="edit_step" class="form-control" required>
                            @error('step') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Step Modal -->
    <div class="modal fade" id="viewStepModal" tabindex="-1" aria-labelledby="viewStepModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStepModalLabel">View Step</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Step Name:</label>
                        <p id="view_step"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
                
            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var step = $(this).data('step');
                
                // Set the value in the edit form
                $('#edit_step').val(step);
                
                // Use the route helper to generate the correct URL
                var updateUrl = "{{ route('steps.update', ':id') }}";
                updateUrl = updateUrl.replace(':id', id);
                $('#editStepForm').attr('action', updateUrl);
            });
            
            // Handle view button click
            $(document).on('click', '.view-btn', function() {
                var step = $(this).data('step');
                $('#view_step').text(step);
            });
            
            // Clear modal forms when closed
            $('#addStepModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
            
            $('#editStepModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        });
    </script>

      <!-- Datatable and jQuery scripts -->
      <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#StepTable').DataTable({
                        paging: false, // ❌ Disable DataTables pagination
                        searching: false, // ❌ Disable DataTables search (we use Laravel search)
                        info: false,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-sm bg-secondary text-white'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm bg-success text-white'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-sm bg-info text-white'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-sm bg-danger text-white'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-sm bg-primary text-white'
                            }
                        ]
                    });

                    // Move buttons to custom div
                    table.buttons().container().appendTo('#exportButtons');
                });
            </script>
    
@endsection