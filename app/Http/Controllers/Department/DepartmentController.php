<?php

namespace App\Http\Controllers\Department;

use App\Models\MDA;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('mda', 'hod')->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $mdas = Mda::orderBy('mda')->get();
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
       return Department::where('mda_id', $mdaId)->get();
    }
}
