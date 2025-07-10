@extends('admin.admin_dashboard')
@section('title', 'Commendations & Awards')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Commendations / Awards</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Commendations / Awards</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="lni lni-trophy me-1"></i>
            Commendations / Awards
            <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm float-end">
                <i class="lni lni-circle-plus"></i> Add New
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Award</th>
                            <th>Awarding Body</th>
                            <th>Award Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commendationAwards as $commendation)
                            <tr>
                                <td>
                                    {{ $commendation->employee->employee_number }} - 
                                    {{ $commendation->employee->surname }}, {{ $commendation->employee->first_name }}
                                </td>
                                <td>{{ $commendation->award }}</td>
                                <td>{{ $commendation->awarding_body }}</td>
                                <td>{{ $commendation->award_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('employees.commendations.show', ['employee' => $commendation->employee_id, 'commendation' => $commendation->id]) }}" class="btn btn-sm btn-info" title="View Details">
                                           <i class="fadeIn animated bx bx-list-ul"></i>
                                        </a>
                                       {{-- <a href="{{ route('employees.commendations.edit', ['employee' => $commendation->employee_id, 'commendation' => $commendation->id]) }}" class="btn btn-sm btn-warning" title="Edit Award">
                                            <i class="fadeIn animated bx bx-edit"></i>
                                        </a> --}}
                                       {{-- <form action="{{ route('employees.commendations.destroy', ['employee' => $commendation->employee_id, 'commendation' => $commendation->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                <i class="lni lni-trash"></i>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No commendations or awards found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $commendationAwards->links() }}
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