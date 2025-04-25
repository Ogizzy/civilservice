@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Pay Groups</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Pay Groups</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4></h4>
                <button type="button" class="btn btn-primary" onclick="createPayGroup()">
                    <i class="lni lni-circle-plus"></i> Add New Pay Group
                </button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>Pay Group</th>
                                    <th>Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payGroups as $index => $group)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $group->paygroup }}</td>
                                        <td>{{ $group->paygroup_code }}</td>
                                        <td>
                                            <a href="{{ route('pay-groups.show', $group) }}"
                                                class="btn btn-info btn-sm"><i class="lni lni-list"
                                                title="View Pay Group"></i></a>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                onclick="editPayGroup('{{ $group->id }}', '{{ $group->paygroup }}', '{{ $group->paygroup_code }}')">
                                                <i class="lni lni-pencil-alt" title="Edit Pay Group"></i>
                                            </button>
                                            <form action="{{ route('pay-groups.destroy', $group) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm delete-btn"><i class="lni lni-trash"
                                                    title="Delete Pay Group"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No pay groups found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pay Group Modal -->
    <div class="modal fade" id="payGroupModal" tabindex="-1" aria-labelledby="payGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payGroupModalLabel">Add New Pay Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payGroupForm" action="{{ route('pay-groups.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="method_field" name="_method" value="POST">
                        
                        <div class="mb-3">
                            <label>Pay Group Name</label>
                            <input type="text" id="paygroup" name="paygroup" class="form-control" placeholder="Enter Pay Group" required>
                            @error('paygroup') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Pay Group Code</label>
                            <input type="text" id="paygroup_code" name="paygroup_code" class="form-control" placeholder="Enter Pay Group Code" required>
                            @error('paygroup_code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
        });
        
        // Function to create a new pay group
        function createPayGroup() {
            // Set modal title
            $('#payGroupModalLabel').text('Add New Pay Group');
            
            // Clear form fields
            $('#payGroupForm').trigger('reset');
            
            // Set form action and method
            $('#payGroupForm').attr('action', '{{ route('pay-groups.store') }}');
            $('#method_field').val('POST');
            
            // Set button text and style
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Save');
            
            // Show modal
            $('#payGroupModal').modal('show');
        }
        
        // Function to edit a pay group
        function editPayGroup(id, paygroup, paygroup_code) {
            // Set modal title
            $('#payGroupModalLabel').text('Edit Pay Group');
            
            // Fill form with pay group data
            $('#paygroup').val(paygroup);
            $('#paygroup_code').val(paygroup_code);
            
            // Set form action and method
            $('#payGroupForm').attr('action', '{{ url("pay-groups") }}/' + id);
            $('#method_field').val('PUT');
            
            // Set button text and style
            $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Update');
            
            // Show modal
            $('#payGroupModal').modal('show');
        }
    </script>
@endsection