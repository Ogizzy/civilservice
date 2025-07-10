<?php

namespace App\Http\Controllers\Queries;
use Log;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\QueriesMisconduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QueriesMisconductController extends Controller
{
     /**
     * Display a listing of the queries/misconducts.
     */
    public function index()
    {
        $queries = QueriesMisconduct::with(['employee', 'document'])->paginate(10);
        return view('admin.queries.index', compact('queries'));
    }

    /**
     * Show the form for creating a new query.
     */
    public function create(Employee $employee)
    {
         return view('admin.queries.create', compact('employee'));
    }

    /**
     * Store a newly created query in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'query_title' => 'required|string|max:255',
            'query' => 'required|string',
            'date_issued' => 'required|date',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $documentId = null;
        if ($request->hasFile('supporting_document')) {
            $path = $request->file('supporting_document')->store('queries', 'public');

            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'Query/Misconduct Letter',
                'document' => $path,
                'user_id' => Auth::id(),
            ]);

            $documentId = $document->id;
        }

        QueriesMisconduct::create([
            'employee_id' => $request->employee_id,
            'query_title' => $request->query_title,
            'query' => $request->input('query'),
            'date_issued' => $request->date_issued,
            'supporting_document' => $documentId,
            'user_id' => Auth::id(),
        ]);

        $notification = array(
            'message' => 'Query/Misconduct record created successfully',
            'alert-type' => 'success'
        );

       return redirect()->route('employees.queries.index', $employee->id)->with($notification);
    }

    /**
     * Show the specified query/misconduct.
     */
    public function show($employeeId, $queryId)
    {
        $employee = Employee::findOrFail($employeeId);
        $queriesMisconduct = QueriesMisconduct::findOrFail($queryId);

        return view('admin.queries.show', compact('employee', 'queriesMisconduct'));
    }


    /**
     * Show the form for editing the specified query.
     */
   public function edit(Employee $employee, QueriesMisconduct $queriesMisconduct)
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.queries.edit', compact('employee', 'queriesMisconduct', 'employees'));
    }

    /**
     * Update the specified query in storage.
     */
  /**
 * Update the specified query in storage.
 */
public function update(Request $request, Employee $employee, QueriesMisconduct $queriesMisconduct)
{
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|exists:employees,id',
        'query_title' => 'required|string|max:255',
        'query' => 'required|string',
        'date_issued' => 'required|date',
        'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Use database transaction to ensure data consistency
    DB::beginTransaction();
    
    try {
        // Save the original document ID before any changes
        $originalDocumentId = $queriesMisconduct->supporting_document;
        $newDocumentId = $originalDocumentId; // Default to current document ID
        
        // Handle document upload if a new file is provided
        if ($request->hasFile('supporting_document')) {
            // Store the new file
            $path = $request->file('supporting_document')->store('queries', 'public');

            // Create new document record
            $newDocument = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'Query/Misconduct Letter',
                'document' => $path,
                'user_id' => Auth::id(),
            ]);

            // Set the new document ID
            $newDocumentId = $newDocument->id;
        }

        // Update the QueriesMisconduct fields (including the new document ID if changed)
        $queriesMisconduct->update([
            'employee_id' => $request->employee_id,
            'query_title' => $request->query_title,
            'query' => $request->input('query'),
            'date_issued' => $request->date_issued,
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
            'message' => 'Query/Misconduct updated successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('employees.queries.index', $employee->id)->with($notification);
        
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollback();
        
        // If we uploaded a new file but failed to update the record, clean up the new file
        if (isset($path) && $path) {
            Storage::disk('public')->delete($path);
        }
        
        // Log the error for debugging
        Log::error('Failed to update query/misconduct: ' . $e->getMessage());
        
        $notification = [
            'message' => 'Failed to update query/misconduct. Please try again.',
            'alert-type' => 'error'
        ];
        
        return redirect()->back()->with($notification)->withInput();
    }
}


    /**
     * Remove the specified query.
     */
    public function destroy(QueriesMisconduct $queriesMisconduct)
    {
        if ($queriesMisconduct->supporting_document) {
            if ($document = Document::find($queriesMisconduct->supporting_document)) {
                $document->delete();
            }
        }

        $queriesMisconduct->delete();

        $notification = array(
            'message' => 'Query/Misconduct deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('queries.index')->with($notification);
    }

    /**
     * Show all queries for a specific employee.
     */
    public function employeeQueries(Employee $employee)
    {
        $queries = QueriesMisconduct::where('employee_id', $employee->id)
            ->with(['document', 'user'])->paginate(10);

        return view('admin.queries.employee', compact('queries', 'employee'));
    }
}
