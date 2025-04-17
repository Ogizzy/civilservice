@extends('admin.admin_dashboard')
@section('admin')

<div class="container">
    <h5>Document: {{ $document->document_type }}</h5>

    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Uploaded:</strong> {{ $document->created_at->format('d M, Y') }}</p>
            <p><strong>By:</strong> {{ $document->user->surname ?? 'N/A' }} {{ $document->user->first_name ?? 'N/A' }}</p>
            <p><strong>File:</strong></p>
            <iframe src="{{ asset('storage/' . $document->document) }}" width="100%" height="600px" frameborder="0"></iframe>
        </div>
    </div>

    <a href="{{ route('employees.documents.index', $employee->id) }}" class="btn btn-secondary mt-3">Back</a>
</div>

@endsection
