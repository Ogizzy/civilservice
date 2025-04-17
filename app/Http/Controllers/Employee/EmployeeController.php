<?php

namespace App\Http\Controllers\Employee;

use Carbon\Carbon;
use App\Models\LGA;
use App\Models\MDA;
use App\Models\Step;
use App\Models\User;
use App\Models\Employee;
use App\Models\PayGroup;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $employees = Employee::with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $mdas = MDA::all();
        $payGroups = PayGroup::all();
        $gradeLevels = GradeLevel::all();
        $steps = Step::all();
        $lgas = LGA::all();
        return view('admin.employee.create', compact('mdas', 'payGroups', 'gradeLevels', 'steps', 'lgas'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mda_id' => 'required|exists:mdas,id',
            'paygroup_id' => 'required|exists:pay_groups,id',
            'level_id' => 'required|exists:grade_levels,id',
            'step_id' => 'required|exists:steps,id',
            'employee_number' => 'required|string|max:255|unique:employees',
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:employees',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'first_appointment_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date',
            'present_appointment_date' => 'nullable|date',
            'retirement_date' => 'nullable|date',
            'rank' => 'nullable|string|max:100',
            'lga' => 'nullable|string|max:100',
            'qualifications' => 'nullable|string|max:255',
            'net_pay' => 'nullable|numeric',
            'passport' => 'nullable|image|max:2048',
        ]);

        // Handle passport upload
        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('passports', 'public');
            $validated['passport'] = $path;
        }

        // Calculate retirement date if not provided
        if (empty($validated['retirement_date'])) {
            if (!empty($validated['dob'])) {
                // Retirement age is typically 60 years
                $validated['retirement_date'] = Carbon::parse($validated['dob'])->addYears(60)->format('Y-m-d');
            } elseif (!empty($validated['first_appointment_date'])) {
                // If DOB not available, use 35 years of service from first appointment
                $validated['retirement_date'] = Carbon::parse($validated['first_appointment_date'])->addYears(35)->format('Y-m-d');
            }
        }

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create a user account for the employee
        $user = User::create([
            'surname' => $validated['surname'],
            'first_name' => $validated['first_name'],
            'other_names' => $validated['middle_name'] ?? '',
            'email' => $validated['email'] ?? ($validated['employee_number'] . '@gmail.com'),
            'password' => $validated['password'],
            'role_id' => 6, 
            'status' => 'active',
        ]);

        $validated['user_id'] = $user->id;

        Employee::create($validated);

        $notification = array(
            'message' => 'Employee created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.index')
            ->with($notification);
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['mda', 'payGroup', 'gradeLevel', 'step', 'documents', 'transferHistories', 'promotionHistories', 'commendationAward', 'queriesMisconduct']);
        return view('admin.employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        // $employee = Employee::findOrFail($id);
        $mdas = Mda::all();
        $payGroups = PayGroup::all();
        $gradeLevels = GradeLevel::all();
        $steps = Step::all();
        $lgas = LGA::all();
        return view('admin.employee.edit', compact('employee', 'mdas', 'payGroups', 'gradeLevels', 'steps', 'lgas'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'mda_id' => 'required|exists:mdas,id',
            'paygroup_id' => 'required|exists:pay_groups,id',
            'level_id' => 'required|exists:grade_levels,id',
            'step_id' => 'required|exists:steps,id',
            'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'phone' => 'nullable|string|max:11',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'first_appointment_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date',
            'present_appointment_date' => 'nullable|date',
            'retirement_date' => 'nullable|date',
            'rank' => 'nullable|string|max:100',
            'lga' => 'nullable|string|max:100',
            'qualifications' => 'nullable|string|max:255',
            'net_pay' => 'nullable|numeric',
            'passport' => 'nullable|image|max:2048',
        ]);

        // Check if password is provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
            
            // Update user's password too
            $employee->user->update([
                'password' => $validated['password']
            ]);
        }

        // Handle passport upload
        if ($request->hasFile('passport')) {
            // Delete old passport if exists
            if ($employee->passport) {
                Storage::disk('public')->delete($employee->passport);
            }
            
            $path = $request->file('passport')->store('passports', 'public');
            $validated['passport'] = $path;
        }

        // Calculate retirement date if not provided
        if (empty($validated['retirement_date'])) {
            if (!empty($validated['dob'])) {
                // Retirement age is typically 60 years
                $validated['retirement_date'] = Carbon::parse($validated['dob'])->addYears(60)->format('Y-m-d');
            } elseif (!empty($validated['first_appointment_date'])) {
                // If DOB not available, use 35 years of service from first appointment
                $validated['retirement_date'] = Carbon::parse($validated['first_appointment_date'])->addYears(35)->format('Y-m-d');
            }
        }

        // Update the employee details
        $employee->update($validated);

        // Update the associated user details
        $employee->user->update([
            'surname' => $validated['surname'],
            'first_name' => $validated['first_name'],
            'other_names' => $validated['middle_name'] ?? '',
            'email' => $validated['email'] ?? ($validated['employee_number'] . '@gmail.com'),
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check for related records
        if ($employee->documents()->count() > 0 || 
            $employee->transferHistories()->count() > 0 || 
            $employee->promotionHistories()->count() > 0 || 
            $employee->commendations()->count() > 0 || 
            $employee->queries()->count() > 0) {
            
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee as they have related records.');
        }

        // Delete passport image if exists
        if ($employee->passport) {
            Storage::disk('public')->delete($employee->passport);
        }

        // Delete the associated user
        $userId = $employee->user_id;
        $employee->delete();
        User::find($userId)->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Display employees by LGA of origin.
     */
    public function byLga(Request $request)
    {
        $lga = $request->input('lga');
        $employees = Employee::where('lga', $lga)->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.reports.employees-by-lga', compact('employees', 'lga'));
    }

    /**
     * Display employees by MDA.
     */
    public function byMda(Request $request)
    {
        $mdaId = $request->input('mda_id');
        $mda = Mda::find($mdaId);
        $employees = Employee::where('mda_id', $mdaId)->with(['payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.reports.employees-by-mda', compact('employees', 'mda'));
    }

    /**
     * Display employees by rank.
     */
    public function byRank(Request $request)
    {
        $rank = $request->input('rank');
        $employees = Employee::where('rank', $rank)->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.reports.employees-by-rank', compact('employees', 'rank'));
    }

    /**
     * Display employees by gender.
     */
    public function byGender(Request $request)
    {
        $gender = $request->input('gender');
        $employees = Employee::where('gender', $gender)->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.reports.employees-by-gender', compact('employees', 'gender'));
    }

    /**
     * Display employees by qualification.
     */
    public function byQualification(Request $request)
    {
        $qualification = $request->input('qualification');
        $employees = Employee::where('qualifications', 'like', "%$qualification%")->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        return view('admin.reports.employees-by-qualification', compact('employees', 'qualification'));
    }

    /**
     * Display employees by pay group, grade level, and step.
     */
    public function byPayStructure(Request $request)
    {
        $payGroupId = $request->input('paygroup_id');
        $levelId = $request->input('level_id');
        $stepId = $request->input('step_id');

        $query = Employee::query();

        if ($payGroupId) {
            $query->where('paygroup_id', $payGroupId);
        }

        if ($levelId) {
            $query->where('level_id', $levelId);
        }

        if ($stepId) {
            $query->where('step_id', $stepId);
        }

        $employees = $query->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        
        $payGroup = $payGroupId ? PayGroup::find($payGroupId) : null;
        $level = $levelId ? GradeLevel::find($levelId) : null;
        $step = $stepId ? Step::find($stepId) : null;

        return view('admin.reports.employees-by-pay-structure', compact('employees', 'payGroup', 'level', 'step'));
    }

    /**
     * Display employees that retired in a given date range.
     */
    public function retiredEmployees(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $employees = Employee::whereBetween('retirement_date', [$startDate, $endDate])
                              ->where('retirement_date', '<=', now())
                              ->with(['mda', 'payGroup', 'gradeLevel', 'step'])
                              ->get();
        
        // I am Calculating total amount that left the wage bill
        $totalAmount = $employees->sum('net_pay');
        
        return view('admin.reports.retired-employees', compact('employees', 'startDate', 'endDate', 'totalAmount'));
    }

    /**
     * Display employees retiring in a given date range.
     */
    public function retiringEmployees(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $employees = Employee::whereBetween('retirement_date', [$startDate, $endDate])
                              ->where('retirement_date', '>', now())
                              ->with(['mda', 'payGroup', 'gradeLevel', 'step'])
                              ->get();
    }

    public function dashboard()
    {
     // Retirement per Year
     $retirementPerYear = Employee::selectRaw('YEAR(retirement_date) as year, COUNT(*) as total')
     ->groupBy('year')
     ->orderBy('year')
     ->pluck('total', 'year');

 // Gender distribution
 $genderStats = Employee::selectRaw('gender, COUNT(*) as total')
     ->groupBy('gender')
     ->pluck('total', 'gender');

 // MDA-wise population
 $mdaStats = MDA::withCount('employees')->orderBy('employees_count', 'desc')->take(10)->get();

 return view('admin.dashboard', compact('retirementPerYear', 'genderStats', 'mdaStats'));
    
}
}