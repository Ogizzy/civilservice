@extends('admin.admin_dashboard')
@section('admin')

<div class="container py-4">

    <h5 class="mb-3">Employees by LGA</h5>

    <!-- Filter Form -->
    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="lga" class="form-label">Select LGA</label>
            <select name="lga" id="lga" class="form-control" required>
                <option value="">-- Select LGA --</option>
                @foreach(App\Models\LGA::all() as $singleLga)
                    <option value="{{ $singleLga->name }}" {{ request('lga') == $singleLga->name ? 'selected' : '' }}>
                        {{ $singleLga->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Results Table -->
    @if(isset($employees) && count($employees))
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Employee No.</th>
                        <th>Name</th>
                        <th>MDA</th>
                        <th>Level</th>
                        <th>Step</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $emp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $emp->employee_number }}</td>
                            <td>{{ $emp->surname }} {{ $emp->first_name }}</td>
                            <td>{{ $emp->mda->mda ?? 'N/A' }}</td>
                            <td>{{ $emp->gradeLevel->level ?? 'N/A' }}</td>
                            <td>{{ $emp->step->step ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(request('lga'))
        <div class="alert alert-warning mt-4">No employees found for this LGA.</div>
    @endif

</div>

@endsection
