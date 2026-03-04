<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PromotionHistory;
use Illuminate\Http\Request;

class PromotionApiController extends Controller
{
    public function index(Request $request, $employeeId)
{
    $employee = Employee::findOrFail($employeeId);

    $query = PromotionHistory::where('employee_id', $employee->id)
        ->with([
            'previousGradeLevel',
            'previousStep',
            'currentGradeLevel',
            'currentStep',
            'document',
            'user'
        ]);

    if ($request->filled('search')) {
        $query->where('promotion_type', 'like', '%' . $request->search . '%');
    }

    $promotions = $query->orderBy('effective_date', 'desc')
        ->paginate(10);

    return response()->json([
        'status' => true,
        'data' => $promotions
    ]);
}

public function store(Request $request, $employeeId)
{
    $employee = Employee::findOrFail($employeeId);

    $validated = $request->validate([
        'promotion_type' => 'required|in:level advancement,step advancement',
        'current_level'  => 'required|exists:grade_levels,id',
        'current_step'   => 'required|exists:steps,id',
        'effective_date' => 'required|date',
        'document_file'  => 'required|file|mimes:pdf,doc,docx,jpg,jpeg|max:10240'
    ]);

    // Upload document
    $path = $request->file('document_file')
        ->store('promotions', 'public');

    $document = Document::create([
        'employee_id'   => $employee->id,
        'document_type' => 'Promotion Letter',
        'document'      => $path,
        'user_id'       => auth()->id(),
    ]);

    $promotion = PromotionHistory::create([
        'employee_id'        => $employee->id,
        'previous_level'     => $employee->level_id,
        'previous_step'      => $employee->step_id,
        'current_level'      => $validated['current_level'],
        'current_step'       => $validated['current_step'],
        'promotion_type'     => $validated['promotion_type'],
        'effective_date'     => $validated['effective_date'],
        'supporting_document'=> $document->id,
        'user_id'            => auth()->id(),
    ]);

    // Update employee
    $employee->update([
        'level_id' => $validated['current_level'],
        'step_id'  => $validated['current_step'],
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Promotion record created successfully',
        'data'    => $promotion->load([
            'previousGradeLevel',
            'previousStep',
            'currentGradeLevel',
            'currentStep',
            'document',
            'user'
        ])
    ], 201);
}


public function show($employeeId, $promotionId)
{
    $promotion = PromotionHistory::where('employee_id', $employeeId)
        ->with([
            'previousGradeLevel',
            'previousStep',
            'currentGradeLevel',
            'currentStep',
            'document',
            'user'
        ])
        ->findOrFail($promotionId);

    return response()->json([
        'status' => true,
        'data'   => $promotion
    ]);
}

public function destroy($employeeId, $promotionId)
{
    $promotion = PromotionHistory::where('employee_id', $employeeId)
        ->findOrFail($promotionId);

    $promotion->delete();

    return response()->json([
        'status'  => true,
        'message' => 'Promotion record deleted successfully'
    ]);
}


}
