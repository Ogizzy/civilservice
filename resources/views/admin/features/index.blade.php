@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">

         <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Features</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">List of Features</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                     <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bxs-plus-square"></i> Add Feature
             </button>
                </div>
            </div>
            <!--end breadcrumb-->

        <h6 class="mb-0 text-uppercase">Platform Features</h6>

            <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">

                <!-- Toolbar for search + export/print -->
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Laravel Search -->
                        <form method="GET" action="{{ route('features.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" value="{{ request('search') }}"
                                placeholder="Search features...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <!-- Export/Print Buttons -->
                        <div id="exportButtons"></div>
                    </div>

                <div class="table-responsive">
                    <table id="platformFeaturesTable" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>S/N</th>
                                <th>Feature</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($features as $i => $feature)
                                <tr>
                                   <td>{{ $features->firstItem() + $i }}</td>
                                    <td>{{ $feature->feature }}</td>
                                    <td>{{ $feature->description }}</td>
                                    <td>
                                        <a href="{{ route('features.show', $feature->id) }}" class="btn btn-info btn-sm"><i
                                                class="lni lni-eye" title="View Feature Details"></i></a>

                                        <button class="btn btn-warning btn-sm editBtn" data-id="{{ $feature->id }}"
                                            data-feature="{{ $feature->feature }}"
                                            data-description="{{ $feature->description }}"><i class="bx bxs-edit"
                                                title="Edit This Feature"></i>

                                        </button>
                                        <form action="{{ route('features.destroy', $feature->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm delete-btn"><i class="bx bxs-trash"
                                                    title="Delete This Feature"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $features->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('features.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Feature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Feature Name</label>
                            <input type="text" name="feature" class="form-control" placeholder="Enter Feature Name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Feature Description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Feature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" value="PUT">
                        <div class="mb-3">
                            <label>Feature Name</label>
                            <input type="text" name="feature" id="editFeatureName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="editFeatureDesc" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript to handle modal data --}}
    <script>
        $(document).ready(function() {
            $('.editBtn').click(function() {
                const id = $(this).data('id');
                const feature = $(this).data('feature');
                const description = $(this).data('description');

                $('#editFeatureName').val(feature);
                $('#editFeatureDesc').val(description);
                $('#editForm').attr('action', `/features/${id}`);

                $('#editModal').modal('show');
            });

            $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
                $(document).ready(function() {
                    // Initialize DataTables but disable pagination/search
                    let table = $('#platformFeaturesTable').DataTable({
                        paging: false, 
                        searching: false, 
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
