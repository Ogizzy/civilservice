<?php

namespace App\Http\Controllers\Transfer;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TransferHistory;
use App\Http\Controllers\Controller;
use App\Models\MDA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransferHistoryController extends Controller
{
    /**
     * Display a listing of the employee's transfer history.
     */
  public function index(Request $request, Employee $employee)
{
    $query = TransferHistory::where('employee_id', $employee->id)
        ->with(['previousMda', 'currentMda', 'document', 'user']);

    // SEARCH
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('previousMda', function ($m) use ($search) {
                $m->where('mda', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('currentMda', function ($m) use ($search) {
                $m->where('mda', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('mda', function ($u) use ($search) {
                $u->where('name', 'LIKE', "%{$search}%");
            });
        });
    }

    $transfers = $query->orderBy('effective_date', 'desc')->paginate(2);

    // Preserve search term in pagination
    $transfers->appends($request->query());

    return view('admin.transfer.index', compact('employee', 'transfers'));
}


    /**
     * Show the form for creating a new transfer record.
     */
    public function create(Employee $employee)
    {
        $mdas = MDA::all();
        return view('admin.transfer.create', compact('employee', 'mdas'));
    }

    /**
     * Store a newly created transfer record in storage.
     */
    public function store(Request $request, Employee $employee)
    {
      // Validate the form data
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'current_mda' => 'required|exists:mdas,id',
        'effective_date' => 'required|date',
        'document_file' => 'required|file|max:10240', // 10MB max
    ]);

    // Fetch the employee by ID
    // $employee = Employee::findOrFail($validated['employee_id']);  // Fetch the employee based on ID

    // Upload supporting document On (Local Machine)
    $path = $request->file('document_file')->store('transfers', 'public');
    $url = asset('storage/' . $path);

    // Upload supporting document On (Cloudinary or AWS S3 Bucket).)
    // $path = $request->file('document_file')->store('transfers', 's3');
    // $url = Storage::disk('s3')->url($path);

    // Create document record
    $document = Document::create([
        'employee_id' => $employee->id, // Store the employee ID
        'document_type' => 'Transfer Letter',
        'document' => $url,
        'user_id' => Auth::id(),
    ]);

    // Create transfer record
    TransferHistory::create([
        'employee_id' => $employee->id,
        'previous_mda' => $employee->mda_id,
        'current_mda' => $validated['current_mda'],
        'effective_date' => $validated['effective_date'],
        'supporting_document' => $document->id,
        'user_id' => Auth::id(),
    ]);

    // Update the employee's current MDA
    $employee->update(['mda_id' => $validated['current_mda']]);

    $notification = array(
        'message' => 'Transfer record created successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('employees.transfers.index', $employee->id)
        ->with($notification);
    }

    /**
     * Display the specified transfer record.
     */
    public function show(Employee $employee, TransferHistory $transfer)
    {
        if ($transfer->employee_id !== $employee->id) {
            return abort(404);
        }

        $transfer->load(['previousMda', 'currentMda', 'document', 'user']);
        
        return view('admin.transfer.show', compact('employee', 'transfer'));
    }

    /**
     * Remove the specified transfer record from storage.
     */
    public function destroy(Employee $employee, TransferHistory $transfer)
    {
        if ($transfer->employee_id !== $employee->id) {
            return abort(404);
        }
        // Don't delete the document, as it might be referenced elsewhere
        $transfer->delete();

        $notification = array(
            'message' => 'Transfer record deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.transfers.index', $employee->id)
            ->with($notification);
    }
}
