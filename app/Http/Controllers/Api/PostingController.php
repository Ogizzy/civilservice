<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    
public function index()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    $postings = $employee->postings()
        ->with(['mda:id,mda', 'department:id,department_name', 'unit:id,unit_name'])
        ->latest('posted_at')
        ->get();

    return response()->json([
        'status' => true,
        'data' => $postings
    ]);
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
