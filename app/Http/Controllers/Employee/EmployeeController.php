<?php

namespace App\Http\Controllers\Employee;

use Carbon\Carbon;
use App\Models\LGA;
use App\Models\MDA;
use App\Models\Step;
use App\Models\User;
use App\Models\Document;
use App\Models\Employee;
use App\Models\PayGroup;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
                // Retirement age is typically 65 years
                $validated['retirement_date'] = Carbon::parse($validated['dob'])->addYears(65)->format('Y-m-d');
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
    $documents = Document::where('employee_id', $employee->id)->paginate(5);
    $documents = $employee->documents()->paginate(5, ['*'], 'documents');
    $transfers = $employee->transferHistories()->with(['previousMda', 'currentMda'])->paginate(5, ['*'], 'transfers');
    $promotions = $employee->promotionHistories()->with(['previousLevel', 'currentLevel', 'previousStep', 'currentStep'])->paginate(5, ['*'], 'promotions');
    return view('admin.employee.show', compact('employee', 'documents', 'transfers', 'promotions'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        // $employee = Employee::findOrFail($id);
        $mdas = MDA::all();
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
                // Retirement age is typically 65 years
                $validated['retirement_date'] = Carbon::parse($validated['dob'])->addYears(65)->format('Y-m-d');
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

        $notification = array(
            'message' => 'Employee updated successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('employees.index')
            ->with($notification);
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
            
                $notification = array(
                    'message' => 'Cannot delete employee as they have related records',
                    'alert-type' => 'error'
                );

            return redirect()->route('employees.index')
                ->with($notification);
        }

        // Delete passport image if exists
        if ($employee->passport) {
            Storage::disk('public')->delete($employee->passport);
        }

        // Delete the associated user
        $userId = $employee->user_id;
        $employee->delete();
        User::find($userId)->delete();

 $notification = array(
            'message' => 'Employee deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employees.index')
            ->with($notification);
    }

    /**
     * Display employees by LGA of origin.
     */
    //  Filter Employees by LGA
public function employeesPerLga(Request $request)
{
    $query = DB::table('employees');

    if ($request->filled('mda_id')) {
        $query->where('mda_id', $request->mda_id);
    }
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }
    $lgaCounts = $query
        ->select('lga', DB::raw('count(*) as total'))
        ->groupBy('lga')
        ->orderBy('total', 'desc')
        ->get();

    $mdas = \App\Models\MDA::all();
    $genders = ['Male', 'Female'];

    return view('admin.reports.employes-by-lga', compact('lgaCounts', 'mdas', 'genders'));
}

    /**
     * Display employees by MDA.
     */
    public function byMda(Request $request)
    {
        $mdas = MDA::all();
        $selectedMdaId = $request->input('mda_id');
        $employees = collect();
        $genderStats = [];
    
        if ($selectedMdaId) {
            $employees = Employee::where('mda_id', $selectedMdaId)
                ->with(['mda', 'payGroup', 'gradeLevel', 'step'])
                ->get();
    
            $genderStats = [
                'Male' => $employees->where('gender', 'Male')->count(),
                'Female' => $employees->where('gender', 'Female')->count()
            ];
        }
    
        return view('admin.reports.employees-by-mda', compact('mdas', 'selectedMdaId', 'employees', 'genderStats'));
    }
    

    /**
     * Display employees by rank.
     */
    
// Filter Employee By Rank
public function employeesByRank(Request $request)
{
    $rank = $request->input('rank');

    $query = Employee::query();
    if ($rank) {
        $query->where('rank', $rank);
    }

    $employees = $query->with(['mda'])->get();
    $ranks = Employee::select('rank')->distinct()->pluck('rank');

    return view('admin.reports.employees-by-rank', compact('employees', 'ranks', 'rank'));
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
    // Filter Employees By Qualification
public function employeesByQualification(Request $request)
{
    $qualification = $request->input('qualification');

    $query = Employee::query();
    if ($qualification) {
        $query->where('qualifications', 'like', "%$qualification%");
    }

    $employees = $query->with('mda')->get();
    $qualifications = Employee::select('qualifications')->distinct()->pluck('qualifications');

    return view('admin.reports.employees-by-qualification', compact('employees', 'qualifications', 'qualification'));
}


    /**
     * Display employees by pay group, grade level, and step.
     */
    // Filter Employees By PayStructure
public function employeesByPayStructure(Request $request)
{
    $paygroup_id = $request->input('paygroup_id');
    $level_id = $request->input('level_id');
    $step_id = $request->input('step_id');

    $query = Employee::query();
    if ($paygroup_id) $query->where('paygroup_id', $paygroup_id);
    if ($level_id) $query->where('level_id', $level_id);
    if ($step_id) $query->where('step_id', $step_id);

    $employees = $query->with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();

    $payGroups = \App\Models\PayGroup::all();
    $levels = \App\Models\GradeLevel::all();
    $steps = \App\Models\Step::all();

    return view('admin.reports.employees-by-pay-structure', compact('employees', 'payGroups', 'levels', 'steps'));
}

    /**
     * Display employees that retired in a given date range.
     */
   // Filter Employee By Retirement
public function retiredEmployees(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = Employee::where('retirement_date', '<=', now());
    if ($startDate && $endDate) {
        $query->whereBetween('retirement_date', [$startDate, $endDate]);
    }

    $employees = $query->get();
    return view('admin.reports.retired-employees', compact('employees', 'startDate', 'endDate'));
}

    /**
     * Display employees retiring in a given date range.
     */
    public function retiringEmployees(Request $request)
    {
        $startDate = $request->input('start_date') ?? now();
        $endDate = $request->input('end_date') ?? now()->addMonths(6);
    
        $employees = Employee::whereBetween('retirement_date', [$startDate, $endDate])->get();
        return view('admin.reports.retiring-employees', compact('employees', 'startDate', 'endDate'));
    }

    
    // Stat on main Dashboard
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

 // Gender (Male and Female)
 $maleCount = Employee::where('gender', 'Male')->count();
 $femaleCount = Employee::where('gender', 'Female')->count();

 return view('admin.dashboard', compact('retirementPerYear', 'genderStats', 'mdaStats', 'maleCount', 'femaleCount'));
    
}


}