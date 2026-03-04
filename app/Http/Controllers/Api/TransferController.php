<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employee;
use App\Models\TransferHistory;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request, $employeeId)
{
    $employee = Employee::findOrFail($employeeId);

    $query = TransferHistory::where('employee_id', $employee->id)
        ->with(['previousMda', 'currentMda', 'document', 'user']);

    // Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('previousMda', function ($m) use ($search) {
                $m->where('mda', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('currentMda', function ($m) use ($search) {
                $m->where('mda', 'LIKE', "%{$search}%");
            });
        });
    }

    $transfers = $query->orderBy('effective_date', 'desc')
        ->paginate(10);

    return response()->json([
        'status' => true,
        'data'   => $transfers
    ]);
}

public function store(Request $request, $employeeId)
{
    $employee = Employee::findOrFail($employeeId);

    $validated = $request->validate([
        'current_mda'    => 'required|exists:mdas,id',
        'effective_date' => 'required|date',
        'document_file'  => 'required|file|max:10240',
    ]);

    // Upload file (Local storage)
    $path = $request->file('document_file')
        ->store('transfers', 'public');

    $url = asset('storage/' . $path);

    // Create document record
    $document = Document::create([
        'employee_id'   => $employee->id,
        'document_type' => 'Transfer Letter',
        'document'      => $url,
        'user_id'       => auth()->id(),
    ]);

    // Create transfer record
    $transfer = TransferHistory::create([
        'employee_id'        => $employee->id,
        'previous_mda'       => $employee->mda_id,
        'current_mda'        => $validated['current_mda'],
        'effective_date'     => $validated['effective_date'],
        'supporting_document'=> $document->id,
        'user_id'            => auth()->id(),
    ]);

    // Update employee MDA
    $employee->update([
        'mda_id' => $validated['current_mda']
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Transfer record created successfully',
        'data'    => $transfer->load([
            'previousMda',
            'currentMda',
            'document',
            'user'
        ])
    ], 201);
}


public function show($employeeId, $transferId)
{
    $transfer = TransferHistory::where('employee_id', $employeeId)
        ->with(['previousMda', 'currentMda', 'document', 'user'])
        ->findOrFail($transferId);

    return response()->json([
        'status' => true,
        'data'   => $transfer
    ]);
}

public function destroy($employeeId, $transferId)
{
    $transfer = TransferHistory::where('employee_id', $employeeId)
        ->findOrFail($transferId);

    $transfer->delete();

    return response()->json([
        'status'  => true,
        'message' => 'Transfer record deleted successfully'
    ]);
}

 

}
