<?php

namespace App\Http\Controllers\MDA;

use App\Http\Controllers\Controller;
use App\Models\MDA;
use Illuminate\Http\Request;

class MdaController extends Controller
{
    /**
     * Display a listing of the MDAs.
     */
    public function index()
    {
        $mdas = MDA::all();
        return view('admin.mda.index', compact('mdas'));
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

        return redirect()->route('mdas.index')
            ->with('success', 'MDA created successfully.');
    }

    /**
     * Display the specified MDA.
     */
    public function show(Mda $mda)
    {
        // Load employees in this MDA
        $mda->load('employees');
        return view('admin.mda.show', compact('mda'));
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

        return redirect()->route('mdas.index')
            ->with('success', 'MDA updated successfully.');
    }

    /**
     * Remove the specified MDA from storage.
     */
    public function destroy(Mda $mda)
    {
        // Check if MDA has employees
        if ($mda->employees()->count() > 0) {
            return redirect()->route('mdas.index')
                ->with('error', 'Cannot delete MDA as it has employees.');
        }

        // Check if MDA is referenced in transfer history
        if ($mda->previousTransfers()->count() > 0 || $mda->currentTransfers()->count() > 0) {
            return redirect()->route('mdas.index')
                ->with('error', 'Cannot delete MDA as it is referenced in transfer history.');
        }

        $mda->delete();

        return redirect()->route('mdas.index')
            ->with('success', 'MDA deleted successfully.');
    }
}
