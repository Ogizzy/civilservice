<?php

namespace App\Http\Controllers\MDA;

use App\Models\MDA;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MdaController extends Controller
{
    /**
     * Display a listing of the MDAs.
     */

   public function index(Request $request)
{
    // Filter type: 'active', 'inactive', or 'all'
    $filter = $request->input('filter', 'active');

    // Base query
    $query = Mda::query();

    // Apply filter
    if ($filter === 'active') {
        $query->where('status', 1);
    } elseif ($filter === 'inactive') {
        $query->where('status', 0);
    }
    
      if ($request->has('search') && $request->search != '') {
        $query->where('mda', 'LIKE', '%'.$request->search.'%');
    }
    // Pagination (10 per page)
    $mdas = $query->orderBy('mda', 'asc')->paginate(10);

    // Keep query params for pagination links
    $mdas->appends($request->query());

    return view('admin.mda.index', compact('mdas', 'filter'));
}

  // In your MdaController
public function activate($id)
{
    $mda = Mda::findOrFail($id);
    $mda->update(['status' => 1]);
    return redirect()->back()->with('success', 'MDA activated successfully');
}

public function deactivate($id)
{
    $mda = Mda::findOrFail($id);
    $mda->update(['status' => 0]);
    return redirect()->back()->with('success', 'MDA deactivated successfully');
}

    
    /**
     * Show the form for creating a new MDA.
     */
    public function create()
    {
        return view('admin.mda.create');
    }

    /**
     * Store a newly created MDA in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mda' => 'required|string|max:255|unique:mdas',
            'mda_code' => 'required|string|max:255|unique:mdas',
            'head_id' => 'nullable|exists:employees,id'
        ]);

        MDA::create($validated);

        $notification = array(
            'message' => 'MDA created successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('mdas.index')->with($notification);
    }

    /**
     * Display the specified MDA.
     */
    public function show(Mda $mda)
    {
        // Load employees in this MDA
        $employees = Employee::where('mda_id', $mda->id)->paginate(10);
        return view('admin.mda.show', compact('mda', 'employees'));
    }

    /**
     * Show the form for editing the specified MDA.
     */
    public function edit(Mda $mda)
    {
        $employees = Employee::orderBy('surname')->get();
        return view('admin.mda.edit', compact('mda', 'employees'));
    }

    /**
     * Update the specified MDA in storage.
     */
    public function update(Request $request, Mda $mda)
    {
        $validated = $request->validate([
            'mda' => 'required|string|max:255|unique:mdas,mda,' . $mda->id,
            'mda_code' => 'required|string|max:255|unique:mdas,mda_code,' . $mda->id,
            'head_id' => 'nullable|exists:employees,id'
        ]);

        $mda->update($validated);


        $notification = array(
            'message' => 'MDA updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('mdas.index')
            ->with($notification);
    }

    /**
     * Remove the specified MDA from storage.
     */
    public function destroy(Mda $mda)
    {
        // Check if MDA has employees
        if ($mda->employees()->count() > 0) {

            $notification = array(
                'message' => 'Cannot delete MDA as it has employees',
                'alert-type' => 'error'
            );
    
            return redirect()->route('mdas.index')
                ->with($notification);
        }

        // Check if MDA is referenced in transfer history
        if ($mda->previousTransfers()->count() > 0 || $mda->currentTransfers()->count() > 0) {

            $notification = array(
                'message' => 'Cannot delete MDA as it is referenced in transfer history',
                'alert-type' => 'error'
            );

            return redirect()->route('mdas.index')
                ->with($notification);
        }

        $mda->delete();

        $notification = array(
            'message' => 'MDA deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('mdas.index')
            ->with($notification);
    }
}
