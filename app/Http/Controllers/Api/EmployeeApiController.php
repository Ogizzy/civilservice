<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;


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

    public function updateContact(Request $request, $employee_number = null)
    {
        // Accept from URL parameter or request body
        $employee_number = $employee_number ?? $request->employee_number;

        $request->merge(['employee_number' => $employee_number]);

        $request->validate([
            'employee_number' => 'required|string',
            'phone' => 'required|string|max:11',
            'email' => 'required|email|max:255',
        ]);

        $employee = Employee::where('employee_number', $employee_number)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'No employee record found for the provided Subhead Number.',
            ], 404);
        }

        $employee->update([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact information updated successfully.',
            'data' => [
                'employee_number' => $employee->employee_number,
                'phone' => $employee->phone,
                'email' => $employee->email,
            ]
        ]);
    }
}
