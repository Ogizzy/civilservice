@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Promotions</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List of Promotions</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
           
 @if (auth()->user()->role->role === 'Employee')
      <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Promotion History for:<span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h6>
                <a href="{{ route('employee.dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fadeIn animated bx bx-chevrons-left"></i>Back
                </a>
          </div>
        @else
        <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Promotion History for:<span style="color: royalblue"> {{ $employee->surname }} {{ $employee->first_name }} {{ $employee->middle_name }}</span></h6>
                <a href="{{ route('employees.promotions.create', $employee->id) }}" class="btn btn-primary btn-sm">
                    <i class="lni lni-circle-plus"></i>New Promotion
                </a>
            </div>
         @endif
            

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
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Type</th>
                                    <th>Effective Date</th>
                                    <th>Promoted By</th>
                                    <th>Document</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>GL {{ $promotion->previousLevel->level ?? 'N/A' }} - Step
                                            {{ $promotion->previousStep->step ?? 'N/A' }}</td>
                                        <td>GL {{ $promotion->currentLevel->level ?? 'N/A' }} - Step
                                            {{ $promotion->currentStep->step ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($promotion->promotion_type) }}</td>
                                        <td>{{ $promotion->effective_date->format('d M, Y') }}</td>
                                        <td>{{ $promotion->user->surname ?? 'N/A' }} {{ $promotion->user->first_name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ $promotion->document->document }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="lni lni-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.promotions.show', [$employee->id, $promotion->id]) }}"
                                                class="btn btn-sm btn-secondary">Details</a>
                                                
                                            @if(auth()->user()->role->role != 'Employee')
                                                <form
                                                action="{{ route('employees.promotions.destroy', [$employee->id, $promotion->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger delete-btn"><i class="bx bxs-trash" title="Delete This Promotion"></i></button>
                                            </form>
                                              @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No promotion records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $promotions->links() }}
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
