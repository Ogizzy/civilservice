@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage Transfers</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List of Transfers</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>Transfer History for: <span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h6>
        <a href="{{ route('employees.transfers.create', $employee->id) }}" class="btn btn-sm btn-primary">
            <i class="fadeIn animated bx bx-plus"></i> New Transfer</a>
        
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
                        <th>From MDA</th>
                        <th>To MDA</th>
                        <th>Effective Date</th>
                        <th>Document</th>
                        <th>Action By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $transfer)
                        <tr>
                            <td>{{ $transfer->previousMda->mda ?? 'N/A' }}</td>
                            <td>{{ $transfer->currentMda->mda ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transfer->effective_date)->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ $transfer->document->document ?? '#' }}" target="_blank" class="btn btn-sm btn-info" title="View Transfer Document"><i class="lni lni-eye"></i></a>
                            </td>
                            <td>{{ $transfer->user->surname ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('employees.transfers.destroy', [$employee->id, $transfer->id]) }}" method="POST" onsubmit="return confirm('Delete this transfer?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $transfers->links() }}
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
