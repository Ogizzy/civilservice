@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    <div class="card radius-10">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Commendation Details</h5>
            <a href="{{ route('commendations.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Employee</th>
                        <td>{{ $commendationAward->employee->surname }} {{ $commendationAward->employee->first_name }} {{ $commendationAward->employee->middle_name }}</td>
                    </tr>

                    <tr>
                        <th>Employee Number</th>
                        <td>{{ $commendationAward->employee->employee_number }}</td>
                    </tr>

                    <tr>
                        <th>Award</th>
                        <td>{{ $commendationAward->award }}</td>
                    </tr>

                    <tr>
                        <th>Awarding Body</th>
                        <td>{{ $commendationAward->awarding_body }}</td>
                    </tr>

                    <tr>
                        <th>Award Date</th>
                        <td>{{ \Carbon\Carbon::parse($commendationAward->award_date)->format('F d, Y') }}</td>
                    </tr>

                    <tr>
                        <th>Document</th>
                        <td>
                            @if ($commendationAward->document)
                                <a href="{{ asset('storage/' . $commendationAward->document->document) }}" target="_blank" class="btn btn-info btn-sm">
                                    View Document
                                </a>
                            @else
                                <span class="text-muted">No document uploaded.</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Created By</th>
                        <td>{{ $commendationAward->user->surname ?? 'N/A' }} {{ $commendationAward->user->first_name ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
