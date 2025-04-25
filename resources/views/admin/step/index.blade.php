@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Steps</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Steps</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4></h4>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStepModal">
                        <i class="lni lni-circle-plus"></i> Add Step
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
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
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $step->step }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm view-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewStepModal" 
                                                    data-id="{{ $step->id }}"
                                                    data-step="{{ $step->step }}">
                                                <i class="lni lni-eye"></i>
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
@endsection