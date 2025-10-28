<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
                'employee_number' => $employee->employee_number,
                'name' => trim("{$employee->surname} {$employee->first_name} {$employee->middle_name}"),
            ]
        ]);
    }

    /**
     * (2) Update employee phone number and email.
     */

    public function updateContact(Request $request, $employee_number)
    {
        $request->validate([
            'phone' => 'required|string|max:11',
            'email' => 'required|email|max:255',
        ]);

        // Find the employee by their number
        $employee = Employee::where('employee_number', $employee_number)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found with the provided Subhead Number',
            ], 404);
        }

        // Update phone and email
        $employee->update([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Records updated successfully.',
            'data' => [
                'employee_number' => $employee->employee_number,
                'phone' => $employee->phone,
                'email' => $employee->email,
            ]
        ], 200);
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
            'step_id',
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

    $employees = Employee::with(['mda:id,mda', 'step:id,step'])
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
            'step_id',
            'rank',
        ])
        // ->whereYear('first_appointment_date', '<=', $year)
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
            'step' => optional($employee->step)->step,
            'rank' => $employee->rank,
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

}
