<?php

namespace App\Http\Controllers\Paygroup;

use App\Models\PayGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayGroupController extends Controller
{
     /**
     * Display a listing of the pay groups.
     */
    public function index()
    {
        $payGroups = PayGroup::all();
        return view('admin.paygroup.index', compact('payGroups'));
    }

    /**
     * Show the form for creating a new pay group.
     */
    public function create()
    {
        return view('admin.paygroup.create');
    }

    /**
     * Store a newly created pay group in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paygroup' => 'required|string|max:255|unique:pay_groups',
            'paygroup_code' => 'required|string|max:255|unique:pay_groups',
        ]);

        PayGroup::create($validated);

        return redirect()->route('pay-groups.index')
            ->with('success', 'Pay Group created successfully.');
    }

    /**
     * Display the specified pay group.
     */
    public function show(PayGroup $payGroup)
    {
        // Load employees in this pay group
        $payGroup->load('employees');
        return view('admin.paygroup.show', compact('payGroup'));
    }

    /**
     * Show the form for editing the specified pay group.
     */
    public function edit(PayGroup $payGroup)
    {
        return view('admin.paygroup.edit', compact('payGroup'));
    }

    /**
     * Update the specified pay group in storage.
     */
    public function update(Request $request, PayGroup $payGroup)
    {
        $validated = $request->validate([
            'paygroup' => 'required|string|max:255|unique:pay_groups,paygroup,' . $payGroup->id,
            'paygroup_code' => 'required|string|max:255|unique:pay_groups,paygroup_code,' . $payGroup->id,
        ]);

        $payGroup->update($validated);

        return redirect()->route('pay-groups.index')
            ->with('success', 'Pay Group updated successfully.');
    }

    /**
     * Remove the specified pay group from storage.
     */
    public function destroy(PayGroup $payGroup)
    {
        // Check if pay group has employees
        if ($payGroup->employees()->count() > 0) {
            return redirect()->route('pay-groups.index')
                ->with('error', 'Cannot delete Pay Group as it has employees.');
        }

        $payGroup->delete();

        return redirect()->route('pay-groups.index')
            ->with('success', 'Pay Group deleted successfully.');
    }
}
