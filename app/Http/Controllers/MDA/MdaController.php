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
    public function index()
    {
        $mdas = MDA::all();// Show all MDAs, both active and inactive
        // $mdas = Mda::where('status', true)->get(); // Only active MDAs
        return view('admin.mda.index', compact('mdas'));
    }

    public function deactivate(Mda $mda)
    {
        $mda->status = 0;
        $mda->save();
    
        return redirect()->route('mdas.index')->with('message', 'MDA deactivated successfully.');
    }
    
    public function activate(Mda $mda)
    {
        $mda->status = 1;
        $mda->save();
    
        return redirect()->route('mdas.index')->with('message', 'MDA activated successfully.');
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
        return view('admin.mda.edit', compact('mda'));
    }

    /**
     * Update the specified MDA in storage.
     */
    public function update(Request $request, Mda $mda)
    {
        $validated = $request->validate([
            'mda' => 'required|string|max:255|unique:mdas,mda,' . $mda->id,
            'mda_code' => 'required|string|max:255|unique:mdas,mda_code,' . $mda->id,
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
