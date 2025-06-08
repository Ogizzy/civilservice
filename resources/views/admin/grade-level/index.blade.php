@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Grade Levels</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List of Grade Levels</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4></h4>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gradeLevelModal" onclick="createGradeLevel()">
                        <i class="lni lni-circle-plus"></i> Add Grade Level
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
                                    <th>Grade Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gradeLevels as $index => $level)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>GL {{ $level->level }}</td>
                                        <td>
                                            <a href="{{ route('grade-levels.show', $level->id) }}"
                                                class="btn btn-info btn-sm"><i class="fadeIn animated bx bx-list-ul"></i></a>
                                            <button type="button" 
                                                class="btn btn-warning btn-sm" 
                                                onclick="editGradeLevel({{ $level->id }}, '{{ $level->level }}')">
                                                <i class="lni lni-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('grade-levels.destroy', $level->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm delete-btn" ><i class="lni lni-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No grade levels found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Level Modal -->
    <div class="modal fade" id="gradeLevelModal" tabindex="-1" aria-labelledby="gradeLevelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradeLevelModalLabel">Add Grade Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="gradeLevelForm" action="{{ route('grade-levels.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="method_field" name="_method" value="POST">
                        <div class="mb-3">
                            <label>Grade Level Name</label>
                            <input type="text" id="level" name="level" class="form-control" placeholder="Enter Grade Level" required>
                            @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            
            // Initialize DataTable with buttons
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
        
        // Function for creating a new grade level
        function createGradeLevel() {
            // Set modal title for add
            $('#gradeLevelModalLabel').text('Add Grade Level');
            
            // Clear form fields
            $('#gradeLevelForm').trigger('reset');
            
            // Reset form action and method
            $('#gradeLevelForm').attr('action', '{{ route('grade-levels.store') }}');
            $('#method_field').val('POST');
            
            // Reset button text
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Save');
            
            // Open modal
            $('#gradeLevelModal').modal('show');
        }
        
        // Function for editing a grade level
        function editGradeLevel(id, level) {
            // Set modal title for edit
            $('#gradeLevelModalLabel').text('Edit Grade Level');
            
            // Fill the form with grade level data
            $('#level').val(level);
            
            // Change form action and method for update
            $('#gradeLevelForm').attr('action', '{{ url("grade-levels") }}/' + id);
            $('#method_field').val('PUT');
            
            // Change button text
            $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Update');
            
            // Open modal
            $('#gradeLevelModal').modal('show');
        }
    </script>
@endsection