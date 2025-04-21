<?php

namespace App\Http\Controllers\Queries;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QueriesMisconduct extends Controller
{
     /**
     * Display a listing of the queries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queries = QueriesMisconduct::with(['employee', 'document'])->paginate(10);
        return view('admin.queries.index', compact('queries'));
    }

    /**
     * Show the form for creating a new query.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.queries.create', compact('employees'));
    }

    /**
     * Store a newly created query in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
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
            // Upload to cloud storage (Cloudinary or AWS S3)
            $path = $request->file('supporting_document')->store('queries', 'public');
            
            // Create document record
            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'query',
                'document' => $path, // Store the cloud URL here
                'user_id' => Auth::id()
            ]);
            
            $documentId = $document->id;
        }

        QueriesMisconduct::create([
            'employee_id' => $request->employee_id,
            'query' => $request->query,
            'date_issued' => $request->date_issued,
            'supporting_document' => $documentId,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('queries.index')
            ->with('success', 'Query/Misconduct record created successfully.');
    }

    /**
     * Display the specified query.
     *
     * @param  \App\Models\QueriesMisconduct $queriesMisconduct
     * @return \Illuminate\Http\Response
     */
    public function show(QueriesMisconduct $queriesMisconduct)
    {
        $queriesMisconduct->load(['employee', 'document', 'user']);
        return view('admin.queries.show', compact('queriesMisconduct'));
    }

    /**
     * Show the form for editing the specified query.
     *
     * @param  \App\Models\QueriesMisconduct  $queriesMisconduct
     * @return \Illuminate\Http\Response
     */
    public function edit(QueriesMisconduct $queriesMisconduct)
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.queries.edit', compact('queriesMisconduct', 'employees'));
    }

    /**
     * Update the specified query in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QueriesMisconduct  $queriesMisconduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QueriesMisconduct $queriesMisconduct)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'query' => 'required|string',
            'date_issued' => 'required|date',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('supporting_document')) {
            // Delete old document if exists
            if ($queriesMisconduct->supporting_document) {
                // If there's cloud storage integration, add code to delete old file
                if ($document = Document::find($queriesMisconduct->supporting_document)) {
                    $document->delete();
                }
            }
            
            // Upload to cloud storage
            $path = $request->file('supporting_document')->store('queries', 'public');
            
            // Create new document record
            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'query',
                'document' => $path,
                'user_id' => Auth::id()
            ]);
            
            $queriesMisconduct->supporting_document = $document->id;
        }

        $queriesMisconduct->update([
            'employee_id' => $request->employee_id,
            'QueriesMisconduct' => $request->QueriesMisconduct,
            'date_issued' => $request->date_issued,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('queries.index')
            ->with('success', 'Query/Misconduct record updated successfully');
    }

    /**
     * Remove the specified query from storage.
     *
     * @param  \App\Models\QueriesMisconduct  $queriesMisconduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(QueriesMisconduct $queriesMisconduct)
    {
        // Delete associated document if exists
        if ($queriesMisconduct->supporting_document) {
            if ($document = Document::find($queriesMisconduct->supporting_document)) {
                // If there's cloud storage integration, add code to delete file
                $document->delete();
            }
        }

        $queriesMisconduct->delete();

        return redirect()->route('queries.index')
            ->with('success', 'Query/Misconduct record deleted successfully');
    }

    /**
     * Display queries for a specific employee
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function employeeQueries(Employee $employee)
    {
        $queries = QueriesMisconduct::where('employee_id', $employee->id)
            ->with(['document', 'user'])
            ->paginate(10);
            
        return view('admin.queries.employee', compact('queries', 'employee'));
    }
}
