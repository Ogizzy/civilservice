<?php

namespace App\Http\Controllers\Document;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents for an employee.
     */
    public function index(Request $request, Employee $employee)
    {
        // Start the query from employee relationship
        $query = $employee->documents();
        // Apply search if keyword is provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_type', 'LIKE', "%{$search}%");
            });
        }
        // Paginate the results
        $documents = $query->orderBy('created_at', 'desc')->paginate(5);
        // Preserve search query on pagination links
        $documents->appends(['search' => $request->search]);

        return view('admin.documents.index', compact('documents', 'employee'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Employee $employee)
    {
        return view('admin.documents.create', compact('employee'));
    }
    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240', // Max 10MB
        ]);

        // Handle file upload to local storage
        if ($request->hasFile('document_file')) {
            // Get the original file name
            $originalName = $request->file('document_file')->getClientOriginalName();

            // Create a unique file name to prevent overwriting
            $fileName = time() . '_' . $originalName;

            // Store the file in the public/documents directory
            $path = $request->file('document_file')->storeAs(
                'documents/' . $employee->id,
                $fileName,
                'public'
            );
            // Create database record
            Document::create([
                'employee_id' => $employee->id,
                'document_type' => $validated['document_type'],
                'document' => $path,
                'user_id' => Auth::id(),
            ]);

            $notification = array(
                'message' => 'Document uploaded successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('employees.documents.index', $employee->id)
                ->with($notification);
        }

        return back()->with('error', 'Failed to upload document.');
    }

    /**
     * Display the specified document.
     */
    public function show(Employee $employee, Document $document)
    {
        return view('admin.documents.show', compact('employee', 'document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Employee $employee, Document $document)
    {
        return view('admin.documents.edit', compact('employee', 'document'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Employee $employee, Document $document)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $document->document_type = $validated['document_type'];

        // Handle file upload if new document is provided
        if ($request->hasFile('document_file')) {
            // Delete old document if it exists
            $oldPath = str_replace('/storage/', '', parse_url($document->document, PHP_URL_PATH));
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('document_file')->store('documents', 'public');
            $document->document = Storage::disk('public')->url($path);
        }

        $document->user_id = Auth::id(); // Track who updated the document
        $document->save();

        $notification = array(
            'message' => 'Document updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.documents.index', $employee->id)
            ->with($notification);
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Employee $employee, Document $document)
    {
        // Delete file from storage
        $path = parse_url($document->document, PHP_URL_PATH);
        if ($path) {
            Storage::disk('s3')->delete(ltrim($path, '/'));
        }

        $document->delete();

        $notification = array(
            'message' => 'Document deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.documents.index', $employee->id)
            ->with($notification);
    }
}
