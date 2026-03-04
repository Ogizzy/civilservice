<?php

namespace App\Http\Controllers\Api;

// use Aws\S3\Transfer;
// use App\Models\Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;

class LeaveController extends Controller
{
    public function info(Request $request)
    {
        $employee = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'approved' => EmployeeLeave::where('employee_id', $employee->id)->where('status', 'approved')->count(),
                'pending'  => EmployeeLeave::where('employee_id', $employee->id)->where('status', 'pending')->count(),
                'rejected' => EmployeeLeave::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
                'total'    => EmployeeLeave::where('employee_id', $employee->id)->count(),
            ]
        ]);
    }

    public function apply(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee profile not found'
            ], 404);
        }

        // END OF YEAR CUTOFF
        $today = now();
        $cutoff = \Carbon\Carbon::create($today->year, 11, 28);

        if ($today->gte($cutoff)) {
            return response()->json([
                'status' => false,
                'message' => 'Leave applications are closed for the year from 28th November.'
            ], 422);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:1000',
        ]);

        // 6-MONTH RULE
        $lastApprovedLeave = \App\Models\EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->latest('end_date')
            ->first();

        if ($lastApprovedLeave) {
            $eligibleDate = \Carbon\Carbon::parse($lastApprovedLeave->end_date)->addMonths(6);

            if (now()->lt($eligibleDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can only apply 6 months after last approved leave.'
                ], 422);
            }
        }

        $totalDays = \App\Models\EmployeeLeave::calculateTotalDays(
            $request->start_date,
            $request->end_date
        );

        // Overlapping check
        $overlapping = \App\Models\EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($overlapping) {
            return response()->json([
                'status' => false,
                'message' => 'You have overlapping leave applications.'
            ], 422);
        }

        $leave = \App\Models\EmployeeLeave::create([
            'employee_id'   => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'total_days'    => $totalDays,
            'reason'        => $request->reason,
            'status'        => 'pending',
            'approval_stage' => 'hod',
            'created_by'    => auth()->id(),
            'applied_date'  => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Leave application submitted successfully',
            'data'    => $leave
        ], 201);
    }



    public function history()
    {
        $employee = auth()->user()->employee;

        $leaves = \App\Models\EmployeeLeave::with('leaveType')
            ->where('employee_id', $employee->id)
            ->orderBy('applied_date', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'data'   => $leaves
        ]);
    }

    public function show($id)
    {
        $employee = auth()->user()->employee;

        $leave = \App\Models\EmployeeLeave::with('leaveType')
            ->where('employee_id', $employee->id)
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => $leave
        ]);
    }


    public function cancel($id)
    {
        $employee = auth()->user()->employee;

        $leave = \App\Models\EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $leave->update(['status' => 'cancelled']);

        return response()->json([
            'status'  => true,
            'message' => 'Leave cancelled successfully'
        ]);
    }
}
