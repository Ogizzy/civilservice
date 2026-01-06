<?php

namespace App\Http\Controllers\Posting;

use App\Models\MDA;
use App\Models\Unit;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\EmployeePosting;
use App\Http\Controllers\Controller;

class EmployeePostingController extends Controller
{

    public function index(Employee $employee)
    {
        $postings = $employee->postings()->latest()->get();
        return view('admin.postings.index', compact('employee', 'postings'));
    }

    public function create(Employee $employee)
    {


        $mdas = MDA::orderBy('mda')->get();

        return view('admin.postings.create', compact('employee', 'mdas'));
    }

    public function store(Request $request, Employee $employee)
    {
        $authEmployee = auth()->user()->employee;

        // HOD / Unit Head restrictions
        if (
            $authEmployee?->isHod() &&
            $employee->department_id !== $authEmployee->departmentHeaded->id
        ) {
            abort(403, 'HODs cannot post employees.');
        }

        if (
            $authEmployee?->isUnitHead() &&
            $employee->unit_id !== $authEmployee->unitHeaded->id
        ) {
            abort(403, 'Unit Heads cannot post employees.');
        }

        // Check if employee already has a posting to this MDA
        $existing = $employee->postings()->where('mda_id', $request->mda_id)->first();


        $notification = array(
            'message' => 'This employee has already been posted to the selected MDA.',
            'alert-type' => 'error'
        );

        if ($existing) {

            return back()->with($notification)->withInput();
        }

        // Validate request
        $data = $request->validate([
            'mda_id' => 'required|exists:mdas,id',
            'department_id' => 'required|exists:departments,id',
            'unit_id' => 'required|exists:units,id',
            'posting_type' => 'required|string',
            'posted_at' => 'required|date',
            'ended_at' => 'nullable|date|after_or_equal:posted_at',
            'remarks' => 'nullable|string',
        ]);

        // Create posting record
        EmployeePosting::create([
            'employee_id'        => $employee->id,
            'mda_id'             => $data['mda_id'],
            'department_id'      => $employee->department_id,
            'unit_id'            => $employee->unit_id,
            'department_id'      => $data['department_id'],
            'unit_id'            => $data['unit_id'],
            'posting_type'       => $data['posting_type'],
            'posted_by'          => auth()->id(),
            'posted_at'          => $data['posted_at'],
            'ended_at'           => $data['ended_at'] ?? null,
            'remarks'            => $data['remarks'] ?? null,
        ]);

        // Update employee current placement
        $employee->update([
            'mda_id'        => $data['mda_id'],
            'department_id' => $data['department_id'],
            'unit_id'       => $data['unit_id'],
        ]);


        $notification = array(
            'message' => 'Employee posted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.posting.index', $employee->id)->with($notification);
    }

    public function edit(Employee $employee, EmployeePosting $posting)
    {
        // Get the latest posting for this employee
        $latestPosting = $employee->postings()->latest('posted_at')->first();

        // Prevent editing if this is not the latest posting
        if (!$latestPosting || (int)$posting->id !== (int)$latestPosting->id) {
            $notification = array(
                'message' => 'Only the latest posting can be edited.',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }

        $mdas = MDA::orderBy('mda')->get();
        $departments = Department::where('mda_id', $posting->mda_id)->orderBy('department_name')->get();
        $units = Unit::where('department_id', $posting->department_id)->orderBy('unit_name')->get();


        return view('admin.postings.edit', compact(
            'employee',
            'posting',
            'mdas',
            'departments',
            'units'
        ));
    }

    public function update(Request $request, Employee $employee, EmployeePosting $posting)
    {
        $authEmployee = auth()->user()->employee;

        // ================= AUTHORIZATION =================
        // HOD restriction (view-only)
        if (
            $authEmployee?->isHod() &&
            $employee->department_id !== $authEmployee->departmentHeaded->id
        ) {
            abort(403, 'HODs cannot update postings.');
        }

        // Unit Head restriction (view-only)
        if (
            $authEmployee?->isUnitHead() &&
            $employee->unit_id !== $authEmployee->unitHeaded->id
        ) {
            abort(403, 'Unit Heads cannot update postings.');
        }

        // ================= VALIDATION =================
        $data = $request->validate([
            'mda_id'        => 'required|exists:mdas,id',
            'department_id' => 'required|exists:departments,id',
            'unit_id'       => 'required|exists:units,id',
            'remarks'       => 'nullable|string|max:1000',
            'ended_at'      => 'nullable|date|after_or_equal:posted_at',
            'posted_at'     => 'required|date',
            'posting_type' => 'required|string',
            'posted_by' => 'nullable|exists:users,id',
        ]);

        // ================= UPDATE POSTING =================
        $posting->update([
            'mda_id'        => $data['mda_id'],
            'department_id' => $data['department_id'],
            'unit_id'       => $data['unit_id'],
            'remarks'       => $data['remarks'],
            'posted_by'     => auth()->id(), // audit trail
            'ended_at'      => $data['ended_at'],
            'posted_at'     => $data['posted_at'],
            'posting_type' => $data['posting_type'],
            // 'posted_by' => $data['posted_by'],
        ]);

        // ================= UPDATE EMPLOYEE CURRENT PLACEMENT =================
        $employee->update([
            'mda_id'        => $data['mda_id'],
            'department_id' => $data['department_id'],
            'unit_id'       => $data['unit_id'],
        ]);

        $notification = array(
            'message' => 'Employee posted successfully',
            'alert-type' => 'success'
        );

        return redirect()
            ->route('employees.posting.index', $employee)
            ->with($notification);
    }


    public function destroy(Employee $employee, EmployeePosting $posting)
    {
        // Ensure posting belongs to this employee
        if ($posting->employee_id !== $employee->id) {
            abort(404);
        }

        // Get the latest posting for this employee
        $latestPosting = $employee->postings()->latest('posted_at')->first();

        // Prevent deletion if this is not the latest posting
        if (!$latestPosting || (int)$posting->id !== (int)$latestPosting->id) {

            $notification = array(
                'message' => 'Only the latest posting can be deleted',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        // ================= DELETE =================
        $posting->delete();

        $notification = array(
            'message' => 'Employee posting deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()
            ->route('employees.posting.index', $employee)->with($notification);
    }
}
