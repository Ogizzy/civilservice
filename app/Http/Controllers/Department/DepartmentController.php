<?php

namespace App\Http\Controllers\Department;

use App\Models\MDA;
use App\Models\Unit;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('surname')->get();
        $departments = Department::with('mda', 'hod')->paginate(10);
        return view('admin.departments.index', compact('departments', 'employees'));
    }

    public function create()
    {
        $mdas = MDA::orderBy('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.departments.create', compact('mdas', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mda_id' => 'required|exists:mdas,id',
            'department_name' => 'required',
            'department_code' => 'nullable|unique:departments',
            'hod_id' => 'nullable|exists:employees,id'
        ]);

        // Rule
        if (!empty($data['hod_id'])) {
            $exists = Department::where('hod_id', $data['hod_id'])->exists()
                || Unit::where('unit_head_id', $data['hod_id'])->exists();

            if ($exists) {
                return back()->withErrors([
                    'hod_id' => 'This employee is already a HOD or Unit Head.'
                ])->withInput();
            }
        }

        Department::create($data);

        $notification = array(
            'message' => 'Department created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('departments.index')->with($notification);
    }

    public function show(Department $department)
    {
        // Load units and HOD relationship
        $department->load(['mda', 'hod', 'units.unitHead']);

        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $mdas = MDA::orderBy('mda')->get();
        $employees = Employee::orderBy('surname')->get();
        return view('admin.departments.edit', compact('department', 'mdas', 'employees'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'mda_id' => 'required|exists:mdas,id',
            'department_name' => 'required',
            'department_code' => 'nullable|unique:departments,department_code,' . $department->id,
            'hod_id' => 'nullable|exists:employees,id'
        ]);

        $department->update($data);

        $notification = array(
            'message' => 'Department updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('departments.index')->with($notification);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        $notification = array(
            'message' => 'Department deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('departments.index')->with($notification);
    }

    // AJAX Method to get Departments by MDA
    public function getDepartmentsByMda($mdaId)
    {
        $departments = Department::where('mda_id', $mdaId)->get();
        return response()->json($departments);
    }

    // Load employees by department
    public function byDepartment(Department $department)
    {
        return response()->json(
            $department->employees()
                ->select('id', 'surname', 'first_name', 'employee_number')
                ->orderBy('surname')
                ->get()
        );
    }

    public function assignHodForm(Department $department)
{
    // Get all employees in this MDA
    $employees = $department->mda->employees ?? collect();

    return view('admin.departments.assign-hod', compact('department', 'employees'));
}


public function assignHod(Request $request, Department $department)
{
    $request->validate([
        'hod_id' => 'required|exists:employees,id',
    ]);

    $department->hod_id = $request->hod_id;
    $department->save();

     $notification = array(
            'message' => 'HOD Assigned Successfully',
            'alert-type' => 'success'
        );

    return redirect()->route('departments.index')->with($notification);
}

}
