<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Emp No.</th>
            <th>Name</th>
            <th>MDA</th>
            <th>Pay Group</th>
            <th>Grade</th>
            <th>Step</th>
            <th>Retirement Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->employee_number }}</td>
                <td>{{ $employee->surname }} {{ $employee->first_name }}</td>
                <td>{{ $employee->mda->mda ?? 'N/A' }}</td>
                <td>{{ $employee->paygroup->paygroup ?? 'N/A' }}</td>
                <td>{{ $employee->level->level ?? 'N/A' }}</td>
                <td>{{ $employee->step->step ?? 'N/A' }}</td>
                <td>{{ $employee->retirement_date ? \Carbon\Carbon::parse($employee->retirement_date)->format('d M, Y') : 'N/A' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">No employees found.</td></tr>
        @endforelse
    </tbody>
</table>
