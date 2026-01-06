<?php

namespace App\Http\Controllers\Unit;

use App\Models\MDA;
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
        $mdas = MDA::orderBy('mda')->get();
        $departments = Department::with('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.units.create', compact('mdas','departments','employees'));
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
        $mdas = MDA::orderBy('mda')->get();
        $departments = Department::with('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.units.edit', compact('mdas', 'unit','departments','employees'));
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

    // Load employees by unit
    public function byUnit(Unit $unit)
    {
        return response()->json(
            $unit->employees()
                ->select('id', 'surname', 'first_name', 'employee_number')
                ->orderBy('surname')
                ->get()
        );
    }


    public function employeesByUnitDepartment(Unit $unit)
{
    $employees = $unit->department->employees()->select('id','surname','first_name','employee_number')->get();
    return response()->json($employees);
}

    // Assign Unit Head
    public function assignHead(Request $request, Unit $unit)
{
   $request->validate([
        'unit_head_id' => 'required|exists:employees,id',
    ]);

    $employeeId = $request->unit_head_id;

    // Check if employee already a unit head of another unit
    $existingUnit = Unit::where('unit_head_id', $employeeId)
                        ->where('id', '<>', $unit->id)
                        ->first();

    if ($existingUnit) {
        return response()->json([
            'message' => 'This employee is already assigned as Unit Head of another unit.'
        ], 422);
    }

    // Check if employee belongs to this department
    $employee = Employee::find($employeeId);
    if ($employee->department_id !== $unit->department_id) {
        return response()->json([
            'message' => 'Cannot assign employee from a different department.'
        ], 422);
    }

    // Assign
    $unit->unit_head_id = $employeeId;
    $unit->save();

    return response()->json([
        'message' => 'Unit Head assigned successfully!'
    ]);
}


}
