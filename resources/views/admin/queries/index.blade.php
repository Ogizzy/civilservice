@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
<div class="container">
    <h4>Queries / Misconduct Records</h4>
    <a href="{{ route('queries.create') }}" class="btn btn-primary btn-sm mb-3">
    <i class="lni lni-circle-plus"></i>Add New Query</a>
        
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead class="thead-dark">
            <tr>
                <th>Employee</th>
                <th>Date Issued</th>
                <th>Query Summary</th>
                <th>Document</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($queries as $query)
                <tr>
                    <td>{{ $query->employee->surname }}, {{ $query->employee->first_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($query->date_issued)->format('M d, Y') }}</td>
                    <td>{{ \Str::limit($query->query, 10) }}</td>
                    <td>
                        @if($query->document)
                            <a href="{{ asset('storage/' . $query->document->document) }}" target="_blank">View Document</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('queries.show', $query->id) }}" class="btn btn-info btn-sm" title="View Query"><i class="lni lni-eye"></i></a>
                        <a href="{{ route('queries.edit', $query->id) }}" class="btn btn-warning btn-sm" title="Edit Query"> <i class="bx bx-edit"></i></a>
                        <form action="{{ route('queries.destroy', $query->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete Query"><i class="bx bx-trash"></i> </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No queries found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $queries->links() }}
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

