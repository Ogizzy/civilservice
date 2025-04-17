@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Documents</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Employee Documents</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

    <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Documents for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h5>
        <a href="{{ route('employees.documents.create', $employee->id) }}" class="btn btn-primary btn-sm">
            <i class="lni lni-upload"></i> Upload Document
        </a>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Type</th>
                        <th>Uploaded On</th>
                        <th>Uploaded By</th>
                        <th>View</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $doc)
                        <tr>
                            <td>{{ $doc->document_type }}</td>
                            <td>{{ $doc->created_at->format('d M, Y') }}</td>
                            <td>{{ $doc->user->surname ?? 'N/A' }} {{ $doc->user->first_name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $doc->document) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                            </td>
                            <td>
                                <a href="{{ route('employees.documents.edit', [$employee->id, $doc->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('employees.documents.destroy', [$employee->id, $doc->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No documents found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $documents->links() }}</div>
        </div>
    </div>
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
