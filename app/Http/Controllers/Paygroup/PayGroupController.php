<?php

namespace App\Http\Controllers\Paygroup;

use App\Models\Employee;
use App\Models\PayGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayGroupController extends Controller
{
     /**
     * Display a listing of the pay groups.
     */
    public function index(Request $request)
    {
    $perPage = $request->input('per_page', 10);

    // Retrieve paginated pay groups ordered alphabetically by name
    $payGroups = PayGroup::orderBy('paygroup', 'asc')->paginate($perPage);

    return view('admin.paygroup.index', compact('payGroups'));
    }

    public function deactivate(PayGroup $payGroup)
    {
        $payGroup->status = 0;
        $payGroup->save();
    
        return redirect()->route('pay-groups.index')->with('message', 'Paygroup deactivated successfully.');
    }
    
    public function activate(PayGroup $payGroup)
    {
        $payGroup->status = 1;
        $payGroup->save();
    
        return redirect()->route('pay-groups.index')->with('message', 'Paygroup activated successfully.');
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

        $notification = array(
            'message' => 'Pay Group created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pay-groups.index')->with($notification);
    }

    /**
     * Display the specified pay group.
     */
   public function show(Request $request, PayGroup $payGroup)
{
    // Query employees belonging to this pay group
    $query = Employee::with(['gradeLevel:id,level', 'step:id,step', 'mda:id,mda'])
        ->where('paygroup_id', $payGroup->id)
        ->select(['id', 'employee_number', 'surname', 'first_name', 'mda_id', 'level_id', 'step_id']);

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('employee_number', 'LIKE', "%$search%")
              ->orWhere('email', 'LIKE', "%$search%")
              ->orWhere('surname', 'LIKE', "%$search%")
              ->orWhere('first_name', 'LIKE', "%$search%")
              ->orWhere('middle_name', 'LIKE', "%$search%");
        });
    }
    // Handle pagination size (default: 10 per page)
    $perPage = $request->input('per_page', 10);

    // Paginate and order results
    $employees = $query->orderBy('surname')->paginate($perPage);

    return view('admin.paygroup.show', compact('payGroup', 'employees'));
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

        $notification = array(
            'message' => 'Pay Group Updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pay-groups.index')->with($notification);
    }

    /**
     * Remove the specified pay group from storage.
     */
    public function destroy(PayGroup $payGroup)
    {
        // Check if pay group has employees
        if ($payGroup->employees()->count() > 0) {

            $notification = array(
                'message' => 'Cannot delete Pay Group as it has employees',
                'alert-type' => 'error'
            );
    
    
            return redirect()->route('pay-groups.index')
                ->with($notification);
        }

        $payGroup->delete();

        $notification = array(
            'message' => 'Pay Group deleted successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('pay-groups.index')
            ->with($notification);
    }
}
