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
        </div>
        <!--end breadcrumb-->


        <div class="page-content">
            <h4>All Platform Features</h4>
            <a href="{{ route('features.create') }}" class="btn btn-primary mb-3">+ Add Feature</a>
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
                                    <th>Feature</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($features as $i => $feature)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $feature->feature }}</td>
                                        <td>{{ $feature->description }}</td>
                                        <td>
                                            <a href="{{ route('features.show', $feature->id) }}"
                                                class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('features.edit', $feature->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('features.destroy', $feature->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $features->links() }}
                    </div>
                </div>
            </div>
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
@endsection
