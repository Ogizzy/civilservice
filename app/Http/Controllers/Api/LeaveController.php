<?php

namespace App\Http\Controllers\Api;

// use Aws\S3\Transfer;
// use App\Models\Leave;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // RULE 1: END OF YEAR
        $today = now();
        $cutoff = \Carbon\Carbon::create($today->year, 11, 28);

        if ($today->gte($cutoff)) {
            return response()->json([
                'status' => false,
                'message' => 'Leave applications closed from Nov 28'
            ], 422);
        }

        // VALIDATION
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:11',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:11',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // RULE 2: 6 MONTHS
        $lastLeave = \App\Models\EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->latest('end_date')
            ->first();

        if ($lastLeave) {
            $eligible = \Carbon\Carbon::parse($lastLeave->end_date)->addMonths(6);

            if (now()->lt($eligible)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can apply after 6 months from last leave'
                ], 422);
            }
        }

        // CALCULATE DAYS
        $totalDays = \App\Models\EmployeeLeave::calculateTotalDays(
            $validated['start_date'],
            $validated['end_date']
        );

        // OVERLAP CHECK
        $overlap = \App\Models\EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
            })->exists();

        if ($overlap) {
            return response()->json([
                'status' => false,
                'message' => 'Overlapping leave exists'
            ], 422);
        }

        // FILE UPLOAD (Cloudinary)
        $documentUrl = null;
        $documentName = null;

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $documentName = $file->getClientOriginalName();

            $upload = Cloudinary::upload(
                $file->getRealPath(),
                ['folder' => 'civil_service/leaves']
            );

            $documentUrl = $upload->getSecurePath();
        }

        // CREATE LEAVE
        $leave = \App\Models\EmployeeLeave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'contact_address' => $validated['contact_address'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_phone' => $validated['emergency_phone'] ?? null,
            'supporting_document_url' => $documentUrl,
            'supporting_document_name' => $documentName,
            'status' => 'pending',
            'approval_stage' => 'hod',
            'created_by' => auth()->id(),
            'applied_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Leave application submitted successfully',
            'data' => $leave
        ], 201);
    }

 public function leaveBalances(Request $request)
{
    $employee = Auth::user()->employee;

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    $currentYear = now()->year;

    $leaveTypes = \App\Models\LeaveType::where('is_active', true)->get();

    $data = [];

    foreach ($leaveTypes as $type) {

        $balance = \App\Models\EmployeeLeaveBalance::where([
            'employee_id' => $employee->id,
            'leave_type_id' => $type->id,
            'year' => $currentYear
        ])->first();

        $data[] = [
            'leave_type_id' => $type->id,
            'leave_type' => $type->name,
            'allocated' => $balance?->entitled_days ?? $type->max_days_per_year,
            'used' => $balance?->used_days ?? 0,
            'pending' => 0, // optional (if you track pending separately)
            'available' => $balance?->remaining_days ?? $type->max_days_per_year,
        ];
    }

    return response()->json([
        'status' => true,
        'data' => $data
    ]);
}

 public function show($id)
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    $leave = \App\Models\EmployeeLeave::where('id', $id)
        ->where('employee_id', $employee->id)
        ->first();

    if (!$leave) {
        return response()->json([
            'status' => false,
            'message' => 'Leave not found for this employee'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $leave
    ]);
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
