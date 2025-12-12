<?php

namespace App\Http\Controllers\Api;

use Aws\S3\Transfer;
use App\Models\Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;

class LeaveController extends Controller
{
    public function info(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'approved' => EmployeeLeave::where('employee_id', $user->employee_id)->where('status', 'approved')->count(),
            'pending'  => EmployeeLeave::where('employee_id', $user->employee_id)->where('status', 'pending')->count(),
            'rejected' => EmployeeLeave::where('employee_id', $user->employee_id)->where('status', 'rejected')->count(),
            'total'    => EmployeeLeave::where('employee_id', $user->employee_id)->count(),
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|integer',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
        ]);

        $leave = EmployeeLeave::create([
            'employee_id'       => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'reason'        => $request->reason,
            'status'        => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Leave applied successfully',
            'data'    => $leave
        ]);
    }

    public function history(Request $request)
    {
        return EmployeeLeave::where('employee_id', $request->employee_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
}
