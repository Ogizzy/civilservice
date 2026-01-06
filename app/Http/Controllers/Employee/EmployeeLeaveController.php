<?php

namespace App\Http\Controllers\Employee;

use Log;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeaveBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\LeaveStatusNotification;
use Carbon\Carbon;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of leave applications
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->role->role;

        $query = EmployeeLeave::with([
            'employee.mda',
            'employee.department',
            'leaveType',
            'approvedBy',
            'createdBy',
        ]);

    // =========================
    // ROLE-BASED VISIBILITY
    // =========================

        /**
         * HOD
         * → Sees department leaves waiting for HOD + past ones
         */
        if (
            $role === 'Employee' &&
            $user->employee &&
            $user->employee->id === optional($user->employee->department)->hod_id
        ) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee->department_id);
            });
        }

        /**
         * Normal Employee
         */
        elseif ($role === 'Employee') {
            $query->where('employee_id', $user->employee->id);
        }

        /**
         * MDA Head
         * → ONLY sees leaves after HOD approval
         */
        elseif ($role === 'MDA Head') {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('mda_id', $user->mda_id);
            })
                ->where(function ($q) {
                    $q->where('approval_stage', 'mda_head') // pending at MDA
                        ->orWhere('approval_stage', 'completed'); // approved / rejected
                });
        }


        /**
         * Admin / HoS
         */
        else {
            // full access
        }

        // =========================
        // OPTIONAL FILTERS
        // =========================

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_stage')) {
            $query->where('approval_stage', $request->approval_stage);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }

        // =========================
        // FINAL DATA
        // =========================
        $leaves = $query
            ->orderBy('id', 'desc')->paginate(5)->withQueryString();

        $leaveTypes = LeaveType::where('is_active', true)->get();

        $employees = Employee::select('id', 'employee_number', 'surname', 'first_name', 'middle_name')->get();

        return view('admin.leaves.index', compact(
            'leaves',
            'leaveTypes',
            'employees'
        ));
    }

    
    /**
     * Show the form for creating a new leave application
     */

    public function create()
    {
        // $employee = auth()->user()->employee; 
        $employee = Auth::user()->employee; // get the employee
        $leaveTypes = LeaveType::where('is_active', true)->get();

        // Calculate leave balances for current year
        $leaveBalances = [];
        foreach ($leaveTypes as $type) {
            $used = $employee->leaves()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', date('Y'))
                ->where('status', 'approved')
                ->sum('total_days');

            $pending = $employee->leaves()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', date('Y'))
                ->where('status', 'pending')
                ->sum('total_days');

            $leaveBalances[$type->id] = [
                'allocated' => $type->max_days_per_year,
                'used' => $used,
                'pending' => $pending,
                'available' => $type->max_days_per_year - $used - $pending
            ];
        }

        return view('admin.leaves.create', compact('employee', 'leaveTypes', 'leaveBalances'));
    }
    /**
     * Store a newly created leave application
     */
    public function store(Request $request)
    {

        // RULE 1: END-OF-YEAR CUTOFF
        // =========================
        $today = Carbon::today();
        $cutoffDate = Carbon::create($today->year, 11, 28);

        if ($today->greaterThanOrEqualTo($cutoffDate)) {
            return back()->with(
                'error',
                'Leave applications are closed for the year from 28th November.'
            );
        }
        // Validate request data
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

        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'Employee profile not found.');
        }

         // =========================
    // RULE 2: 6-MONTH RULE
    // =========================
    $lastApprovedLeave = EmployeeLeave::where('employee_id', $employee->id)
        ->where('status', 'approved')
        ->orderByDesc('end_date')
        ->first();

    if ($lastApprovedLeave) {
        $eligibleDate = Carbon::parse($lastApprovedLeave->end_date)->addMonths(6);

        if (now()->lessThan($eligibleDate)) {

              $notification = array(
            'message' => 'You can only apply for a new Leave six (6) months after your last Approved Leave ended.',
            'alert-type' => 'error'
        );

            return redirect()->back()->with($notification);
        }
    }
        // Calculate leave days
        $totalDays = EmployeeLeave::calculateTotalDays($validated['start_date'], $validated['end_date']);

        // Check leave balance
        $leaveBalance = EmployeeLeaveBalance::where([
            'employee_id' => $employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'year' => date('Y')
        ])->first();

        if ($leaveBalance && $leaveBalance->remaining_days < $totalDays) {
            return back()->with('error', "Insufficient leave balance. You have {$leaveBalance->remaining_days} days remaining.");
        }

        // Check for overlapping leaves
        $overlapping = EmployeeLeave::where('employee_id', $employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })->exists();

        if ($overlapping) {
            return back()->with('error', 'You have overlapping leave applications.');
        }

        try {
            DB::transaction(function () use ($validated, $employee, $totalDays, $request) {
                $documentPath = null;
                $documentName = null;

                if ($request->hasFile('supporting_document')) {
                    $file = $request->file('supporting_document');
                    $documentName = $file->getClientOriginalName();
                    $documentPath = $file->store('leave-documents', 'public');
                }

                EmployeeLeave::create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $validated['leave_type_id'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'total_days' => $totalDays,
                    'reason' => $validated['reason'],
                    'contact_address' => $validated['contact_address'],
                    'contact_phone' => $validated['contact_phone'],
                    'emergency_contact' => $validated['emergency_contact'],
                    'emergency_phone' => $validated['emergency_phone'],
                    'supporting_document_url' => $documentPath,
                    'supporting_document_name' => $file->getClientOriginalName(),
                    'status' => 'pending',
                    'approval_stage' => 'hod',
                    'created_by' => Auth::id(),
                    'applied_date' => now(),
                ]);
            });

            return redirect()->route('leaves.index')->with([
                'message' => 'Leave application submitted successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error submitting application: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified leave application
     */
   public function show(EmployeeLeave $leave)
{
    $leave->load([
        'employee.mda',
        'employee.department',
        'employee.paygroup',
        'leaveType',
        'approvedBy',
        'createdBy',
        'hodApprovedBy',
        'mdaApprovedBy.mda',
        'mdaApprovedBy.role',
        'createdBy',
    ]);

    $user = Auth::user();
    $employee = $leave->employee;

    // =========================
    // AUTHORIZATION CHECK
    // =========================

    $isOwner = $user->employee && $user->employee->id === $leave->employee_id;

    $isHod = $user->employee &&
        $employee->department &&
        $employee->department->hod_id === $user->employee->id;

    $isMdaHead = $user->role->role === 'MDA Head' &&
        $user->mda_id === $employee->mda_id;

    $isAdmin = in_array($user->role->role, ['BDIC Super Admin','Head of Service', 'Commissioner']);

    if (! ($isOwner || $isHod || $isMdaHead || $isAdmin)) {
        abort(403, 'Unauthorized access to leave application.');
    }

    return view('admin.leaves.show', compact('leave', 'employee'));
}


    /**
     * Show the form for editing the specified leave application
     */
    public function edit(EmployeeLeave $leave)
    {
        // Only allow editing pending applications
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.show', $leave)->with('error', 'Cannot edit non-pending leave applications.');
        }

        // Check if user can edit this leave
        if (Auth::user()->employee && Auth::user()->employee->id !== $leave->employee_id) {
            abort(403, 'Unauthorized access to leave application.');
        }

        $leaveTypes = LeaveType::where('is_active', true)->get();
        $employee = $leave->employee;

        // Get leave balances for current year
        $currentYear = date('Y');
        $leaveBalances = EmployeeLeaveBalance::where('employee_id', $employee->id)
            ->where('year', $currentYear)
            ->with('leaveType')->get()
            ->keyBy('leave_type_id');

        // Calculate leave balances for current year
        $leaveBalances = [];
        foreach ($leaveTypes as $type) {
            $used = $employee->leaves()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', date('Y'))
                ->where('status', 'approved')
                ->sum('total_days');

            $pending = $employee->leaves()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', date('Y'))
                ->where('status', 'pending')
                ->sum('total_days');

            $leaveBalances[$type->id] = [
                'allocated' => $type->max_days_per_year,
                'used' => $used,
                'pending' => $pending,
                'available' => $type->max_days_per_year - $used - $pending
            ];
        }

        return view('admin.leaves.edit', compact('leave', 'leaveTypes', 'employee', 'leaveBalances'));
    }

    /**
     * Update the specified leave application
     */
    public function update(Request $request, EmployeeLeave $leave)
    {
        // Only allow updating pending applications
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.show', $leave)->with('error', 'Cannot update non-pending leave applications.');
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:20',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Check if employee has sufficient leave balance
        $totalDays = EmployeeLeave::calculateTotalDays($request->start_date, $request->end_date);
        $currentYear = date('Y');

        $leaveBalance = EmployeeLeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $currentYear)->first();

        if ($leaveBalance && $leaveBalance->remaining_days < $totalDays) {
            return redirect()->back()->with('error', 'Insufficient leave balance. You have ' . $leaveBalance->remaining_days . ' days remaining.');
        }

        // Check for overlapping leave applications (excluding current application)
        $overlapping = EmployeeLeave::where('employee_id', $leave->employee_id)
            ->where('id', '!=', $leave->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()->with('error', 'You have overlapping leave applications for the selected dates.');
        }

        $updateData = [
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'contact_address' => $request->contact_address,
            'contact_phone' => $request->contact_phone,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
        ];


        // Handle file upload if provided
        if ($request->hasFile('supporting_document')) {
            try {
                // Delete old document if it exists
                if ($leave->supporting_document_url && Storage::disk('public')->exists($leave->supporting_document_url)) {
                    Storage::disk('public')->delete($leave->supporting_document_url);
                }

                // Upload new document
                $file = $request->file('supporting_document');
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('leave-documents', 'public'); // saved in storage/app/public/leave-documents

                $updateData['supporting_document_url'] = $filePath;
                $updateData['supporting_document_name'] = $originalName;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Document upload failed: ' . $e->getMessage());
            }
        }


        $leave->update($updateData);

        $notification = array(
            'message' => 'Leave application updated successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('leaves.show', $leave)->with($notification);
    }

    /**
     * Cancel the specified leave application
     */
    public function cancel(EmployeeLeave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.show', $leave)->with('error', 'Cannot cancel non-pending leave applications.');
        }

        $leave->update(['status' => 'cancelled']);

        $notification = array(
            'message' => 'Leave application cancelled successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('leaves.index')->with($notification);
    }

    /**
     * HOD Approve leave application
     */

    public function hodApprove(Request $request, EmployeeLeave $leave)
    {
        $request->validate([
            'hod_remarks' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // Ensure HOD
        abort_unless(
            $user->employee &&
                $user->employee->department &&
                $user->employee->department->hod_id === $user->employee->id,
            403
        );

        // Ensure correct stage
        abort_unless($leave->approval_stage === 'hod', 403);

        DB::transaction(function () use ($leave, $user, $request) {
            $leave->update([
                'approval_stage'  => 'mda_head',
                // 'status'          => 'pending',
                'hod_approved_by' => auth()->user()->employee->id,
                'hod_approved_at' => now(),
                'hod_remarks'     => $request->hod_remarks,
            ]);
        });

        return back()->with([
            'message' => 'Leave approved and forwarded to MDA Head.',
            'alert-type' => 'success',
        ]);
    }



    /**
     * HOD Reject leave application
     */
    public function hodReject(Request $request, EmployeeLeave $leave)
    {
        $request->validate([
            'hod_remarks' => 'required|string|min:5',
        ]);

        $user = auth()->user();

        abort_unless(
            $user->employee &&
                optional($user->employee->department)->hod_id === $user->employee->id,
            403
        );

        abort_unless($leave->approval_stage === 'hod', 403);

        DB::transaction(function () use ($leave, $user, $request) {
            $leave->update([
                'status'          => 'rejected',
                'approval_stage'  => 'completed',
                'hod_approved_by' => auth()->user()->employee->id,
                'hod_approved_at' => now(),
                'hod_remarks'     => $request->hod_remarks,
            ]);
        });

        return back()->with([
            'message' => 'Leave rejected by HOD.',
            'alert-type' => 'error',
        ]);
    }


    /**
     * MDA Approve leave application
     */
    public function mdaApprove(Request $request, EmployeeLeave $leave)
    {
        $request->validate([
            'mda_head_remarks' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // Ensure MDA Head
        abort_unless($user->role->role === 'MDA Head', 403);

        // Ensure correct stage
        abort_unless($leave->approval_stage === 'mda_head', 403);

        DB::transaction(function () use ($leave, $user, $request) {
            $leave->update([
                'status'                 => 'approved',
                'approval_stage'         => 'completed',
                'mda_approved_by'        => auth()->id(),
                'mda_head_approved_at'   => now(),
                'mda_head_remarks'       => $request->mda_head_remarks,
                'approved_by'            => auth()->id(),
                'approved_at'            => now(),
            ]);
        });

        return back()->with([
            'message' => 'Leave Approved Successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * MDA Reject leave application
     */
    public function mdaReject(Request $request, EmployeeLeave $leave)
    {
        $request->validate([
            'mda_head_remarks' => 'required|string',
        ]);

        // SAFETY: only allow rejection at MDA stage
        if ($leave->approval_stage !== 'mda_head' || $leave->status !== 'pending') {
            return back()->with('error', 'This leave cannot be rejected at this stage.');
        }

        DB::transaction(function () use ($leave, $request) {

            $leave->update([
                'status' => 'rejected',
                'approval_stage' => 'completed',

                'mda_approved_by' => auth()->id(),
                'mda_head_approved_at' => now(),
                'mda_head_remarks' => $request->mda_head_remarks,

                // optional global fields
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        });

        $notification = array(
            'message' => 'Leave rejected successfully by MDA Head.',
            'alert-type' => 'success'
        );

        return redirect()
            ->route('leaves.index')->with($notification);
    }

    /**
     * Get leave balance for request
     */
    public function showLeaveBalance(Request $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $currentYear = date('Y');

        $leaveBalance = EmployeeLeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $currentYear)
            ->first();

        return view('leave-balance.show', [
            'entitled_days' => $leaveBalance?->entitled_days ?? 0,
            'used_days' => $leaveBalance?->used_days ?? 0,
            'remaining_days' => $leaveBalance?->remaining_days ?? 0,
        ]);
    }

    public function history()
    {
        $leaves = \App\Models\EmployeeLeave::join('employees', 'employee_leaves.employee_id', '=', 'employees.id')
            ->where('employees.user_id', auth()->user()->id)
            ->with('leaveType')
            ->select('employee_leaves.*') // Select only leave columns
            ->orderBy('applied_date', 'desc')
            ->get();

        return view('admin.leaves.history', compact('leaves'));
    }

    public function viewDocument($leave)
    {
        $leave = EmployeeLeave::findOrFail($leave);

        // Check if document exists
        if (!$leave->supporting_document_url || !Storage::exists($leave->supporting_document_url)) {
            abort(404, 'Document not found');
        }

        // Check if user is authorized
        if (!auth()->user()->canApproveLeaves() && auth()->user()->id != $leave->employee->user_id) {
            abort(403);
        }

        return response()->file(storage_path('app/' . $leave->supporting_document_url));
    }
}
