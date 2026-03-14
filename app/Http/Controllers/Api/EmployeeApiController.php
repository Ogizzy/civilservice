<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class EmployeeApiController extends Controller
{
    /**
     * (1) Validate civil servant using subhead number.
     */
    public function validateCivilServant(Request $request, $employee_number = null)
    {
        // Accept from URL parameter, query string, or request body
        $employee_number = $employee_number ?? $request->query('employee_number') ?? $request->employee_number;

        if (!$employee_number) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subhead Number is required',
            ], 400);
        }

        $employee = Employee::where('employee_number', $employee_number)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subhead Number not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => trim("{$employee->surname}, {$employee->first_name} {$employee->middle_name}"),
                'employee_number' => $employee->employee_number,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'contact_address' => $employee->contact_address,
                'dob' => $employee->dob,
                'gender' => $employee->gender,
                'marital_status' => $employee->marital_status,
                'religion' => $employee->religion,
                'lga' => $employee->lga,
                'first_appointment_date' => $employee->first_appointment_date,
                'confirmation_date' => $employee->confirmation_date,
                'retirement_date' => $employee->retirement_date,
                'rank' => $employee->rank,
                'qualifications' => $employee->qualifications,
                'net_pay' => $employee->net_pay,
                'mda_id' => $employee->mda,
                'department_id' => $employee->department,
                'paygroup_id' => $employee->paygroup,
                'level_id' => $employee->gradeLevel,
                'step_id' => $employee->step,
                // 'passport' => $employee->passport,
                'passport' => $employee->passport ? $employee->passport : null,

                
            ]
        ]);
    }

    /**
     * Update employee information.
     */

    public function updateContact(Request $request, $employee_number)
    {
         $validated = $request->validate([
        'surname' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'phone' => 'required|string|max:11',
        'email' => 'required|email|max:255',
        'gender' => 'nullable|string|max:10',
        'marital_status' => 'nullable|string|max:20',
        'religion' => 'nullable|string|max:50',
        'lga' => 'nullable|string|max:100',
        'contact_address' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6|confirmed',
        'passport' => 'nullable|image|max:2048',
    ]);

    $employee = Employee::where('employee_number', $employee_number)->first();

    if (!$employee) {
        return response()->json([
            'status' => 'error',
            'message' => 'Employee not found.'
        ], 404);
    }

    // Update basic details
    $employee->fill($validated);

    // Update password only if provided
    if ($request->filled('password')) {
        $employee->password = Hash::make($request->password);
    }

    // Cloudinary upload
    if ($request->hasFile('passport')) {

        // Delete old image from Cloudinary
        if (!empty($employee->passport_public_id)) {
            Cloudinary::destroy($employee->passport_public_id);
        }

        $uploadedFile = Cloudinary::upload(
            $request->file('passport')->getRealPath(),
            ['folder' => 'civil_service/passports']
        );

        $employee->passport = $uploadedFile->getSecurePath();
        $employee->passport_public_id = $uploadedFile->getPublicId();
    }

    $employee->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Service Account updated successfully.',
        'data' => $employee
    ]);
    }


    /**
     * Get paginated list of employees for external systems (Civil Service Admin System).
     */
    public function index(Request $request)
    {
        // You can control pagination size using ?per_page=50
        $perPage = $request->get('per_page', 10);

        // Select only the required fields
        $employees = Employee::select([
            'employee_number',
            'surname',
            'first_name',
            'middle_name',
            'gender',
            'email',
            'phone',
            'dob',
            'first_appointment_date',
            'confirmation_date',
            'retirement_date',
            'mda_id',
            'level_id',
            'step_id',
            'paygroup_id',
            'rank',
        ])->paginate($perPage);

        Log::channel('api_updates')->info('Civil Service System Data Accessed', [
            'ip' => $request->ip(),
            'user' => optional($request->user())->email,
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee records retrieved successfully',
            'data' => $employees->items(),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'last_page' => $employees->lastPage(),
                'next_page_url' => $employees->nextPageUrl(),
                'prev_page_url' => $employees->previousPageUrl(),
            ]
        ]);
    }



    /**
     * Returns paginated employees filtered by year.
     * The Civil Service Administration System will call this endpoint.
     */
    public function getEmployeesByYear(Request $request, $year)
{
    if (!preg_match('/^\d{4}$/', $year)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid year format. Year must be 4 digits.',
        ], 422);
    }

    $perPage = $request->input('per_page', 10);

    $employees = Employee::with(['mda:id,mda', 'gradeLevel:id,level', 'step:id,step'])
        ->select([
            'employee_number',
            'surname',
            'first_name',
            'middle_name',
            'gender',
            'email',
            'phone',
            'dob',
            'first_appointment_date',
            'confirmation_date',
            'retirement_date',
            'mda_id',
            'level_id',
            'step_id',
            'rank',
            'passport',
        ])
        ->whereYear('retirement_date', $year)
        ->orderBy('surname')
        ->paginate($perPage);

    if ($employees->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => "No employees found for year {$year}.",
        ], 404);
    }

    // Format output with MDA and Step names
    $data = $employees->map(function ($employee) {
        return [
            'employee_number' => $employee->employee_number,
            'surname' => $employee->surname,
            'first_name' => $employee->first_name,
            'middle_name' => $employee->middle_name,
            'gender' => $employee->gender,
            'email' => $employee->email,
            'phone' => $employee->phone,
            'dob' => $employee->dob,
            'first_appointment_date' => $employee->first_appointment_date,
            'confirmation_date' => $employee->confirmation_date,
            'retirement_date' => $employee->retirement_date,
            'mda' => optional($employee->mda)->mda,
            'grade_level' => optional($employee->gradeLevel)->level,
            'step' => optional($employee->step)->step,
            'rank' => $employee->rank,
            'passport' => $employee->passport ? $employee->passport : null,
        ];
    });

    Log::channel('api_updates')->info('Civil Service System Data Accessed', [
        'ip' => $request->ip(),
        'user' => optional($request->user())->email,
        'time' => now()->toDateTimeString(),
        ]);

    return response()->json([
        'status' => 'success',
        'message' => "Employee records for year {$year} retrieved successfully",
        'data' => $data,
        'pagination' => [
            'current_page' => $employees->currentPage(),
            'per_page' => $employees->perPage(),
            'total' => $employees->total(),
            'last_page' => $employees->lastPage(),
            'next_page_url' => $employees->nextPageUrl(),
            'prev_page_url' => $employees->previousPageUrl(),
        ],
    ]);
}


// For Mobile App: Get employee profile details for the authenticated user. The mobile app will call this endpoint to display the employee's profile information.
/*
|--------------------------------------------------------------------------
Display Employee Details 
|--------------------------------------------------------------------------
*/

public function employeeProfile(Request $request)
{
    $employee = $request->user(); // or auth()->user()


    $employee->load([
        'mda:id,mda',
        'gradeLevel:id,level',
        'step:id,step',
        'paygroup:id,paygroup'
    ]);
    
    return response()->json([
        'status' => 'success',
        'data' => [
            'employee_number' => $employee->employee_number,
            'surname' => $employee->surname,
            'first_name' => $employee->first_name,
            'middle_name' => $employee->middle_name,
            'gender' => $employee->gender,
            'email' => $employee->email,
            'phone' => $employee->phone,
            'rank' => $employee->rank,
            'mda' => optional($employee->mda)->mda,
            'level' => optional($employee->gradeLevel)->level,
            'step' => optional($employee->step)->step,
            'paygroup' => optional($employee->paygroup)->paygroup,
            'passport' => $employee->passport,
        ]
    ]);
}

}
