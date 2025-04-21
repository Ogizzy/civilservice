<?php

namespace App\Http\Controllers\Commendation;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\CommendationAward;
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
    public function create()
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.commendations.create', compact('employees'));
    }

    /**
     * Store a newly created commendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $documentId = null;
        if ($request->hasFile('supporting_document')) {
            // Upload to cloud storage (Cloudinary or AWS S3)
            // This is a placeholder - implement your cloud storage upload logic
            $path = $request->file('supporting_document')->store('commendation', 'public');
            
            // Create document record
            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'commendation',
                'document' => $path, // Store the cloud URL here
                'user_id' => Auth::id()
            ]);
            
            $documentId = $document->id;
        }

        CommendationAward::create([
            'employee_id' => $request->employee_id,
            'award' => $request->award,
            'awarding_body' => $request->awarding_body,
            'award_date' => $request->award_date,
            'supporting_document' => $documentId,
            'user_id' => Auth::id()
        ]);

        $notification = array(
            'message' => 'Commendation created successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('commendations.index')
            ->with($notification);
    }

    /**
     * Display the specified CommendationAward.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function show(CommendationAward $commendationAward)
    {
        $commendationAward->load(['employee', 'document', 'user']);
        return view('admin.commendations.show', compact('commendationAward'));
    }

    /**
     * Show the form for editing the specified CommendationAward.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function edit(CommendationAward $commendationAward)
    {
        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name')->get();
        return view('admin.commendations.edit', compact('commendationAward', 'employees'));
    }

    /**
     * Update the specified commendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommendationAward $commendationAward)
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

        if ($request->hasFile('supporting_document')) {
            // Delete old document if exists
            if ($commendationAward->supporting_document) {
                // Then delete the document record
                if ($document = Document::find($commendationAward->supporting_document)) {
                    $document->delete();
                }
            }
            
            // Upload to cloud storage or local machine
            $path = $request->file('supporting_document')->store('commendation', 'public');
            
            // Create new document record
            $document = Document::create([
                'employee_id' => $request->employee_id,
                'document_type' => 'Commendation Letter',
                'document' => $path,
                'user_id' => Auth::id()
            ]);
            
            $commendationAward->supporting_document = $document->id;
        }

        $commendationAward->update([
            'employee_id' => $request->employee_id,
            'award' => $request->award,
            'awarding_body' => $request->awarding_body,
            'award_date' => $request->award_date,
            'user_id' => Auth::id()
        ]);

        $notification = array(
            'message' => 'Commendation Updated successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('commendations.index')
            ->with($notification);
    }

    /**
     * Remove the specified CommendationAward from storage.
     *
     * @param  \App\Models\CommendationAward  $commendationAward
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommendationAward $commendationAward)
    {
        // Delete associated document if exists
        if ($commendationAward->supporting_document) {
            if ($document = Document::find($commendationAward->supporting_document)) {
                // If there's cloud storage integration, add code to delete file
                $document->delete();
            }
        }

        $commendationAward->delete();

        $notification = array(
            'message' => 'Commendation deleted successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('commendations.index')
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
