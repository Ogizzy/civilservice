<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeePosting;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    
public function index($employee_id)
{
    $employee = Employee::find($employee_id);

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    $postings = $employee->postings()->latest()->get();

    return response()->json([
        'status' => true,
        'data' => $postings
    ]);
}

public function store(Request $request, $employee_id)
{
    $employee = Employee::find($employee_id);

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    $data = $request->validate([
        'mda_id' => 'required|exists:mdas,id',
        'department_id' => 'required|exists:departments,id',
        'unit_id' => 'required|exists:units,id',
        'posting_type' => 'required|string',
        'posted_at' => 'required|date',
        'ended_at' => 'nullable|date|after_or_equal:posted_at',
        'remarks' => 'nullable|string',
    ]);

    $posting = EmployeePosting::create([
        'employee_id' => $employee->id,
        'mda_id' => $data['mda_id'],
        'department_id' => $data['department_id'],
        'unit_id' => $data['unit_id'],
        'posting_type' => $data['posting_type'],
        'posted_by' => auth()->id(),
        'posted_at' => $data['posted_at'],
        'ended_at' => $data['ended_at'] ?? null,
        'remarks' => $data['remarks'] ?? null,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Employee posted successfully',
        'data' => $posting
    ], 201);
}

public function update(Request $request, $id)
{
    $posting = \App\Models\EmployeePosting::find($id);

    if (!$posting) {
        return response()->json([
            'status' => false,
            'message' => 'Posting not found'
        ], 404);
    }

    $data = $request->validate([
        'mda_id'        => 'required|exists:mdas,id',
        'department_id' => 'required|exists:departments,id',
        'unit_id'       => 'required|exists:units,id',
        'posting_type'  => 'required|string',
        'posted_at'     => 'required|date',
        'ended_at'      => 'nullable|date|after_or_equal:posted_at',
        'remarks'       => 'nullable|string',
    ]);

    $posting->update($data);

    return response()->json([
        'status' => true,
        'message' => 'Posting updated successfully',
        'data' => $posting
    ]);
}

public function destroy($id)
{
    $posting = \App\Models\EmployeePosting::find($id);

    if (!$posting) {
        return response()->json([
            'status' => false,
            'message' => 'Posting not found'
        ], 404);
    }

    $posting->delete();

    return response()->json([
        'status' => true,
        'message' => 'Posting deleted successfully'
    ]);
}

}
