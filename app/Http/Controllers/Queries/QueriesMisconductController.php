<?php

namespace App\Http\Controllers\Queries;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\QueriesMisconduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
    public function create()
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.queries.create', compact('employees'));
    }

    /**
     * Store a newly created query in storage.
     */
    public function store(Request $request)
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

        return redirect()->route('queries.index')->with($notification);
    }

    /**
     * Show the specified query/misconduct.
     */
    public function show(QueriesMisconduct $queriesMisconduct)
    {
        $queriesMisconduct->load(['employee', 'document', 'user']);
        return view('admin.queries.show', compact('queriesMisconduct'));
    }

    /**
     * Show the form for editing the specified query.
     */
    public function edit(QueriesMisconduct $queriesMisconduct)
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.queries.edit', compact('queriesMisconduct', 'employees'));
    }

    /**
     * Update the specified query in storage.
     */
    public function update(Request $request, QueriesMisconduct $queriesMisconduct)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'query_title' => $request->query_title,
            'query' => 'required|string',
            'date_issued' => 'required|date',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('supporting_document')) {
            if ($queriesMisconduct->supporting_document) {
                if ($document = Document::find($queriesMisconduct->supporting_document)) {
                    $document->delete();
                }
            }

            $path = $request->file('supporting_document')->store('queries', 'public');

            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'Query Letter',
                'document' => $path,
                'user_id' => Auth::id()
            ]);

            $queriesMisconduct->supporting_document = $document->id;
        }

        $queriesMisconduct->update([
            'employee_id' => $request->employee_id,
            'query_title' => $request->query_title,
            'query' => $request->input('query'),
            'date_issued' => $request->date_issued,
            'user_id' => Auth::id()
        ]);

        $notification = array(
            'message' => 'Query/Misconduct updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('queries.index')->with($notification);
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
