@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">MDAs</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of MDAs</li>
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
            <!-- Button trigger for Create Modal -->
            <button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0" data-bs-toggle="modal"
                data-bs-target="#createMdaModal">
                <i class="bx bxs-plus-square"></i> Add New MDA
            </button>

            <!-- Modal -->
            <div class="modal fade" id="createMdaModal" tabindex="-1" aria-labelledby="createMdaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('mdas.store') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createMdaModalLabel">Create New MDA</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>MDA Name</label>
                                <input type="text" name="mda" class="form-control" placeholder="Enter MDA Name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label>MDA Code</label>
                                <input type="text" name="mda_code" class="form-control" placeholder="Enter MDA Code"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create MDA</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S/N</th>
                                    <th>MDA Name</th>
                                    <th>MDA Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdas as $index => $mda)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $mda->mda }}</td>
                                        <td>{{ $mda->mda_code }}</td>
                                        <td>
                                            <a href="{{ route('mdas.show', $mda->id) }}" class="btn btn-info btn-sm"><i
                                                    class="lni lni-list" title="View MDA Details"></i></a>
                                            <a href="#" class="btn btn-sm btn-warning edit-mda-btn"
                                                data-id="{{ $mda->id }}" data-name="{{ $mda->mda }}"
                                                data-code="{{ $mda->mda_code }}"><i class="lni lni-pencil-alt"
                                                    title="Edit MDA"></i></a>

                                            </a>
                                            <form action="{{ route('mdas.destroy', $mda->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm delete-btn" ><i
                                                        class="lni lni-trash" title="Delete MDA"></i>

                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($mdas->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No MDAs found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas for Edit -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editMdaCanvas" aria-labelledby="editMdaCanvasLabel">
        <div class="offcanvas-header">
            <h5 id="editMdaCanvasLabel">Edit MDA</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <form id="editMdaForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editMdaId" name="id">

                <div class="mb-3">
                    <label for="editMdaName" class="form-label">MDA Name</label>
                    <input type="text" name="mda" class="form-control" id="editMdaName" required>
                    {{ old('mda_code', $mda->mda_name) }}
                </div>

                <div class="mb-3">
                    <label for="editMdaCode" class="form-label">MDA Code</label>
                    <input type="text" name="mda_code" class="form-control" id="editMdaCode" required>
                    {{ old('mda_code', $mda->mda_code) }}
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(document).on('click', '.edit-mda-btn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const code = $(this).data('code');

            // Populate form fields
            $('#editMdaName').val(name);
            $('#editMdaCode').val(code);
            $('#editMdaForm').attr('action', `/mdas/${id}`);

            // Show offcanvas
            const offcanvas = new bootstrap.Offcanvas('#editMdaCanvas');
            offcanvas.show();
        });
    </script>
@endsection
