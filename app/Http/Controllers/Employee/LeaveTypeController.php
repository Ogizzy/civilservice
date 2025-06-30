<?php

namespace App\Http\Controllers\Employee;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        // $leaveTypes = LeaveType::paginate(5); 
        $leaveTypes = LeaveType::when($request->search, function ($query, $search) {
    return $query->where('name', 'like', '%' . $search . '%');
    })->paginate(5);

        return view('admin.leaves.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leaves.leave-types.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'code' => 'required|unique:leave_types,code|max:10',
        'name' => 'required|max:100',
        'description' => 'nullable',
        'max_days_per_year' => 'required|integer|min:1',
        'is_active' => 'boolean'
    ]);

    LeaveType::create($validated);

       $notification = array(
            'message' => 'Leave type created successfully.',
            'alert-type' => 'success'
        );

    return redirect()->route('leave-types.index')->with($notification);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return view('employee.leave-types.show', compact('leaveType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
          $leaveType = LeaveType::findOrFail($id);
        return view('employee.leave-types.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, LeaveType $leaveType)
{
        $validated = $request->validate([
        'code' => 'required|max:10|unique:leave_types,code,'.$leaveType->id,
        'name' => 'required|max:100',
        'description' => 'nullable',
        'max_days_per_year' => 'required|integer|min:1',
        'is_active' => 'boolean'
    ]);

    $leaveType->update($validated);

     $notification = array(
            'message' => 'Leave type updated successfully.',
            'alert-type' => 'success'
        );

    return redirect()->route('leave-types.index')->with($notification);
}


public function toggleStatus(LeaveType $leaveType)
{
    $leaveType->update(['is_active' => !$leaveType->is_active]);

     $notification = array(
            'message' => 'Status updated successfully',
            'alert-type' => 'success'
        );
    
    return back()->with($notification);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $leaveType = LeaveType::findOrFail($id);
        $leaveType->delete();

        $notification = array(
            'message' => 'Leave type deleted successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('leave-types.index')->with($notification);
    
    }
}
