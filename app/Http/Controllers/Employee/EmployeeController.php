<?php

namespace App\Http\Controllers\Employee;

use Carbon\Carbon;
use App\Models\LGA;
use App\Models\MDA;
use App\Models\Step;
use App\Models\User;
use App\Models\State;
use App\Models\Document;
use App\Models\Employee;
use App\Models\PayGroup;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Validators\ValidationException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request)
    {
        // $employees = Employee::with(['mda', 'payGroup', 'gradeLevel', 'step'])->get();
        // return view('admin.employee.index', compact('employees'));

        $query = Employee::with([
        'mda:id', 
        'payGroup:id',
        'gradeLevel:id,level', 
        'step:id,step'
    ])
    ->select(['id', 'employee_number', 'surname', 'first_name', 'mda_id', 'paygroup_id', 'level_id', 'step_id']);

    // Add search functionality
    if ($request->has('search') && $request->search != '') {
        $query->where(function($q) use ($request) {
            $q->where('employee_number', 'LIKE', '%'.$request->search.'%')
              ->orWhere('surname', 'LIKE', '%'.$request->search.'%')
              ->orWhere('first_name', 'LIKE', '%'.$request->search.'%');
        });
    }

    $employees = $query->orderBy('surname')->paginate(50);

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
        $states = State::all();
        return view('admin.employee.create', compact('mdas', 'payGroups', 'gradeLevels', 'steps', 'lgas', 'states'));
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
            'state_id' => 'required|exists:states,id',
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
            'employee_number' => $validated['employee_number'],
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

        return redirect()->route('employees.index')->with($notification);
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
    $employees = Employee::with('state')->get();
    $documents = Document::where('employee_id', $employee->id)->paginate(5);
    $documents = $employee->documents()->paginate(5, ['*'], 'documents');
    $transfers = $employee->transferHistories()->with(['previousMda', 'currentMda'])->paginate(5, ['*'], 'transfers');
    $promotions = $employee->promotionHistories()->with(['previousLevel', 'currentLevel', 'previousStep', 'currentStep'])->paginate(5, ['*'], 'promotions');
    return view('admin.employee.show', compact('employee', 'documents', 'transfers', 'promotions', 'employees'));
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
        $states = State::all();
        return view('admin.employee.edit', compact('employee', 'mdas', 'payGroups', 'gradeLevels', 'steps', 'lgas', 'states'));
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
            'state_id' => 'required|exists:states,id',
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
            'state' => 'nullable|string|max:100',
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
     * Display employees by LGA.
     */
 
public function employeesPerLga(Request $request)
{
    $query = DB::table('employees');

    if ($request->filled('mda_id')) {
        $query->where('mda_id', $request->mda_id);
    }
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }
   
  $grouped = $query
    ->select('lga', DB::raw('count(*) as total'))
    ->groupBy('lga')
    ->orderBy('total', 'desc')
    ->get();

// Pagination
$perPage = 10;
$currentPage = LengthAwarePaginator::resolveCurrentPage();
$currentItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();

$lgaCounts = new LengthAwarePaginator(
    $currentItems,
    $grouped->count(),
    $perPage,
    $currentPage,
    ['path' => $request->url(), 'query' => $request->query()]
);

// Other data
$mdas = \App\Models\MDA::all();
$genders = ['Male', 'Female'];

return view('admin.reports.employes-by-lga', [
    'lgaCounts' => $lgaCounts,
    'allLgaCounts' => $grouped,
    'mdas' => $mdas,
    'genders' => $genders
]);

}

    /**
     * Display employees by MDA.
     */
    public function byMda(Request $request)
    {
        // Get all MDAs for the filter dropdown
        $mdas = Mda::orderBy('mda')->get();
        
        // Get the selected MDA ID from request
        $selectedMdaId = $request->get('mda_id');
        
        // Get items per page (default to 25)
        $perPage = $request->get('per_page', 10);
        
        // Initialize variables
        $employees = collect();
        $genderStats = [];
        $totalGradeLevels = 0;
        
        if ($selectedMdaId) {
            // Get paginated employees for the selected MDA
            $employees = Employee::with(['mda', 'payGroup', 'gradeLevel'])
                ->where('mda_id', $selectedMdaId)
                ->orderBy('surname')
                ->orderBy('first_name')
                ->paginate($perPage);
            
            // Append query parameters to pagination links
            $employees->appends($request->query());
            
            // Get gender statistics for all employees in this MDA
            $allEmployees = Employee::where('mda_id', $selectedMdaId)->get();
            $genderStats = $allEmployees->groupBy('gender')->map->count()->toArray();
            
            // Get total unique grade levels for this MDA
            $totalGradeLevels = $allEmployees->pluck('grade_level_id')->unique()->count();
        }
        
        return view('admin.reports.employees-by-mda', compact(
            'mdas',
            'employees', 
            'selectedMdaId',
            'genderStats',
            'totalGradeLevels'
        ));
    }
    

    /**
     * Display employees by rank.
     */
    
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
   
public function employeesByPayStructure(Request $request)
{
    $paygroup_id = $request->input('paygroup_id');
    $level_id = $request->input('level_id');
    $step_id = $request->input('step_id');

    $query = Employee::query();
    if ($paygroup_id) $query->where('paygroup_id', $paygroup_id);
    if ($level_id) $query->where('level_id', $level_id);
    if ($step_id) $query->where('step_id', $step_id);

    $employees = $query->with(['mda', 'payGroup', 'gradeLevel', 'step'])->paginate(10);

    $payGroups = \App\Models\PayGroup::all();
    $levels = \App\Models\GradeLevel::all();
    $steps = \App\Models\Step::all();

    return view('admin.reports.employees-by-pay-structure', compact('employees', 'payGroups', 'levels', 'steps'));
}

    /**
     * Display employees that retired in a given date range.
     */
   public function retiredEmployees(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = Employee::where('retirement_date', '<=', now());
    if ($startDate && $endDate) {
        $query->whereBetween('retirement_date', [$startDate, $endDate]);
    }
    $totalRetired = $query->count();
    $employees = $query->paginate(5);
    return view('admin.reports.retired-employees', compact('employees', 'startDate', 'endDate', 'totalRetired'));
}

    /**
     * Display employees retiring in a given date range.
     */
    public function retiringEmployees(Request $request)
    {
        $startDate = $request->input('start_date') ?? now();
        $endDate = $request->input('end_date') ?? now()->addMonths(6);
        $search = $request->input('search');
    
        $employees = Employee::with('mda')
            ->whereBetween('retirement_date', [$startDate, $endDate])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                      ->orWhere('surname', 'like', "%$search%")
                      ->orWhere('employee_number', 'like', "%$search%");
                });
            })
            ->orderBy('retirement_date', 'asc')
            ->paginate(10)
            ->appends($request->only(['start_date', 'end_date', 'search']));
    
        return view('admin.reports.retiring-employees', compact('employees', 'startDate', 'endDate', 'search'));
    }
    
//     // Stat on main Dashboard
//     public function dashboard()
//     {
//      // Retirement per Year
//      $retirementPerYear = Employee::selectRaw('YEAR(retirement_date) as year, COUNT(*) as total')
//      ->groupBy('year')
//      ->orderBy('year')
//      ->pluck('total', 'year');

//  // Gender distribution
//  $genderStats = Employee::selectRaw('gender, COUNT(*) as total')
//      ->groupBy('gender')
//      ->pluck('total', 'gender');

//  // MDA-wise population
//  $mdaStats = MDA::withCount('employees')->orderBy('employees_count', 'desc')->take(10)->get();

//  // Gender (Male and Female)
//  $maleCount = Employee::where('gender', 'Male')->count();
//  $femaleCount = Employee::where('gender', 'Female')->count();

//  return view('admin.dashboard', compact('retirementPerYear', 'genderStats', 'mdaStats', 'maleCount', 'femaleCount'));
    
// }

// Import Employee via Excel
 // Show Import Form
 public function showImportForm()
 {
     return view('admin.employee.importemployee');
 }

 // Handle Excel Import
 public function import(Request $request)
 {
     // Validate the uploaded file
     $validated = $request->validate([
         'file' => 'required|mimes:xlsx,xls,csv|max:2048', // max 2MB for safety
     ]);

     try {
         // Perform the import
         Excel::import(new EmployeeImport, $validated['file']);

         return redirect()->back()->with('success', 'Employees imported successfully!');
         
     } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
         // Handle validation errors inside the Excel file
         $failures = $e->failures();

         $errorMessages = [];
         foreach ($failures as $failure) {
             $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
         }

         return redirect()->back()->withErrors($errorMessages);
     } catch (\Exception $e) {
         // Handle any other errors
         return redirect()->back()->withErrors('An error occurred during import: ' . $e->getMessage());
     }
 }

 public function downloadSampleTemplate()
    {
        $filePath = public_path('backend/excel-template/main-template.xlsx');
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found.');
        }
        $fileName = 'employee_import_template.xlsx';
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }


}