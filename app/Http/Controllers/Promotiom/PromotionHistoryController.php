<?php

namespace App\Http\Controllers\Promotiom;

use App\Models\Step;
use App\Models\Document;
use App\Models\Employee;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use App\Models\PromotionHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PromotionHistoryController extends Controller
{
    /**
     * Display a listing of the employee's promotion history.
     */
    public function index(Employee $employee)
    {
        $promotions = PromotionHistory::where('employee_id', $employee->id)
        ->with(['previousGradeLevel','previousStep','currentGradeLevel','currentStep','document', 
            'user'])->orderBy('effective_date', 'desc')->paginate(5);
        
        return view('admin.promotion.index', compact('employee', 'promotions'));
    }

    /**
     * Show the form for creating a new promotion record.
     */
    public function create(Employee $employee)
    {
        $gradeLevels = GradeLevel::all();
        $steps = Step::all();
        
        return view('admin.promotion.create', compact('employee','steps','gradeLevels' ));
    }

    /**
     * Store a newly created promotion record in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'promotion_type' => 'required|in:level advancement,step advancement',
            'current_level' => 'required|exists:grade_levels,id',
            'current_step' => 'required|exists:steps,id',
            'effective_date' => 'required|date',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg|max:10240', // 10MB max
        ]);

        // Upload supporting document
        $path = $request->file('document_file')->store('promotions', 'public');
        // $url = Storage::url($path);

        // Create document record
        $document = Document::create([
            'employee_id' => $employee->id,
            'document_type' => 'Promotion Letter',
            'document' => $path,
            'user_id' => Auth::id(),
        ]);

        // Create promotion record
        PromotionHistory::create([
            'employee_id' => $employee->id,
            'previous_level' => $employee->level_id,
            'previous_step' => $employee->step_id,
            'current_level' => $validated['current_level'],
            'current_step' => $validated['current_step'],
            'promotion_type' => $validated['promotion_type'],
            'effective_date' => $validated['effective_date'],
            'supporting_document' => $document->id,
            'user_id' => Auth::id(),
        ]);

        // Update employee's current level and step //
        $employee->update([
            'level_id' => $validated['current_level'],
            'step_id' => $validated['current_step'],
        ]);

        $notification = array(
            'message' => 'Promotion record created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.promotions.index', $employee->id)
            ->with($notification);
    }

    /**
     * Display the specified promotion record.
     */
    public function show(Employee $employee, PromotionHistory $promotion)
    {
        if ($promotion->employee_id !== $employee->id) {
            return abort(404);
        }

        $promotion->load([ 'previousGradeLevel', 'previousStep','currentGradeLevel','currentStep','document', 'user'
        ]);
        
        return view('admin.promotion.show', compact('employee', 'promotion'));
    }

    /**
     * Remove the specified promotion record from storage.
     */
    public function destroy(Employee $employee, PromotionHistory $promotion)
    {
        if ($promotion->employee_id !== $employee->id) {
            return abort(404);
        }

        // Don't delete the document, as it might be referenced elsewhere
        $promotion->delete();

        return redirect()->route('employees.promotions.index', $employee->id)
            ->with('success', 'Promotion record deleted successfully.');
    }
}
