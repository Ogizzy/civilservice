<?php

namespace App\Http\Controllers\Unit;

use App\Models\Unit;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('department','unitHead')->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $departments = Department::with('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.units.create', compact('departments','employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'unit_name' => 'required',
            'unit_code' => 'nullable|unique:units',
            'unit_head_id' => 'nullable|exists:employees,id'
        ]);

        Unit::create($data);
        
         $notification = array(
            'message' => 'Unit created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('units.index')->with($notification);
    }

    public function edit(Unit $unit)
    {
        $departments = Department::with('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.units.edit', compact('unit','departments','employees'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'unit_name' => 'required',
            'unit_code' => 'nullable|unique:units,unit_code,'.$unit->id,
            'unit_head_id' => 'nullable|exists:employees,id'
        ]);

        $unit->update($data);

         $notification = array(
            'message' => 'Unit updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('units.index')->with($notification);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
         $notification = array(
            'message' => 'Unit deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('units.index')->with($notification);
    }

    public function getUnitsByDepartment($departmentId)
    {
            return Unit::where('department_id', $departmentId)->get();

    }

}
