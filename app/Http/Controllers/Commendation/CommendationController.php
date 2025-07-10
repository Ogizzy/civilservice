<?php

namespace App\Http\Controllers\Commendation;

use Log;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\CommendationAward;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommendationController extends Controller
{
   /**
     * Display a listing of the commendations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commendationAwards = CommendationAward::with(['employee', 'document'])->paginate(10);
        return view('admin.commendations.index', compact('commendationAwards'));
    }

    /**
     * Show the form for creating a new commendation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employeeId)
    {
         $employee = Employee::findOrFail($employeeId);
        // $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.commendations.create', compact('employee'));
    }

    /**
     * Store a newly created commendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employee $employee)
{
    // Merge employee_id into the request data before validation
    $data = array_merge($request->all(), ['employee_id' => $employee->id]);

    $validator = Validator::make($data, [
        'employee_id' => 'required|exists:employees,id',
        'award' => 'required|string|max:255',
        'awarding_body' => 'required|string|max:255',
        'award_date' => 'required|date',
        'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $documentId = null;
    if ($request->hasFile('supporting_document')) {
        $path = $request->file('supporting_document')->store('commendation', 'public');

        $document = Document::create([
            'employee_id' => $employee->id,
            'document_type' => 'Commendation Letter',
            'document' => $path,
            'user_id' => Auth::id()
        ]);

        $documentId = $document->id;
    }

    CommendationAward::create([
        'employee_id' => $employee->id,
        'award' => $data['award'],
        'awarding_body' => $data['awarding_body'],
        'award_date' => $data['award_date'],
        'supporting_document' => $documentId,
        'user_id' => Auth::id()
    ]);

    return redirect()->route('employees.commendations.index', ['employee' => $employee->id])->with([
        'message' => 'Commendation created successfully.',
        'alert-type' => 'success'
    ]);
}


    /**
     * Display the specified CommendationAward.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
public function show(Employee $employee, CommendationAward $commendation)
{
    return view('admin.commendations.show', [
        'employee' => $employee,
        'commendationAward' => $commendation, // Keep the view variable name for consistency
    ]);
}

    /**
     * Show the form for editing the specified CommendationAward.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee, CommendationAward $commendation)
    {
        return view('admin.commendations.edit', compact('employee', 'commendation'));
    }



    /**
     * Update the specified commendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee, CommendationAward $commendation)
{
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|exists:employees,id',
        'award' => 'required|string|max:255',
        'awarding_body' => 'required|string|max:255',
        'award_date' => 'required|date',
        'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Use database transaction to ensure data consistency
    DB::beginTransaction();
    
    try {
        // Save the original document ID before any changes
        $originalDocumentId = $commendation->supporting_document;
        $newDocumentId = $originalDocumentId; // Default to current document ID
        
        // Handle document upload if a new file is provided
        if ($request->hasFile('supporting_document')) {
            // Store the new file
            $path = $request->file('supporting_document')->store('commendation', 'public');

            // Create new document record
            $newDocument = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'Commendation Letter',
                'document' => $path,
                'user_id' => Auth::id(),
            ]);

            // Set the new document ID
            $newDocumentId = $newDocument->id;
        }

        // Update the CommendationAward fields (including the new document ID if changed)
        $commendation->update([
            'employee_id' => $request->employee_id,
            'award' => $request->award,
            'awarding_body' => $request->awarding_body,
            'award_date' => $request->award_date,
            'supporting_document' => $newDocumentId,
            'user_id' => Auth::id(),
        ]);

        // Delete the old document only after successfully updating the record
        if ($request->hasFile('supporting_document') && $originalDocumentId && $originalDocumentId !== $newDocumentId) {
            $oldDocument = Document::find($originalDocumentId);
            if ($oldDocument) {
                // Delete the file from storage
                Storage::disk('public')->delete($oldDocument->document);
                // Delete the document record
                $oldDocument->delete();
            }
        }

        // Commit the transaction
        DB::commit();

        $notification = [
            'message' => 'Commendation updated successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('employees.commendations.index', $employee->id)->with($notification);
        
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollback();
        
        // If we uploaded a new file but failed to update the record, clean up the new file
        if (isset($path) && $path) {
            Storage::disk('public')->delete($path);
        }
        
        // Log the error for debugging
        Log::error('Failed to update commendation: ' . $e->getMessage());
        
        $notification = [
            'message' => 'Failed to update commendation. Please try again.',
            'alert-type' => 'error'
        ];
        
        return redirect()->back()->with($notification)->withInput();
    }
}

    /**
     * Remove the specified CommendationAward from storage.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee, CommendationAward $commendation)
{
    // Delete associated document file if exists
    if ($commendation->supporting_document) {
        $documentPath = public_path('uploads/commendations/' . $commendation->supporting_document);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }
    }

    $commendation->delete();

    $notification = array(
        'message' => 'Commendation deleted successfully.',
        'alert-type' => 'success'
    );

    return redirect()->route('employees.commendations.index', ['employee' => $employee->id])
        ->with($notification);
}
    /**
     * Display CommendationAward for a specific employee
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function employeeCommendations(Employee $employee)
    {
        $commendationAwards = CommendationAward::where('employee_id', $employee->id)
            ->with(['document', 'user'])->paginate(10);
            
        return view('admin.commendations.employee', compact('commendationAwards', 'employee'));
    }
}
