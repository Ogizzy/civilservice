<?php

namespace App\Http\Controllers\ServiceAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ServiceAccountController extends Controller
{
  /**
     * Show the My Service Account form.
     */
    public function edit()
    {
        $user = Auth::user();
        $employee = $user->employee;

        $profileData = Auth::user();

        return view('admin.employee.service_account', compact('user', 'employee', 'profileData'));
    }

    /**
     * Update the My Service Account details.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'lga' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'passport' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Update User basic info
        $user->update([
            'surname' => $validated['surname'],
            'first_name' => $validated['first_name'],
            'other_names' => $validated['middle_name'] ?? '',
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Update Employee info
        if ($employee) {
            $updateData = [
                'gender' => $validated['gender'],
                'marital_status' => $validated['marital_status'],
                'religion' => $validated['religion'],
                'lga' => $validated['lga'],
                'phone' => $validated['phone'],
                'contact_address' => $validated['contact_address'],
            ];

            // Handle passport upload
            if ($request->hasFile('passport')) {
                // Delete old passport if exists
                if ($employee->passport) {
                    Storage::disk('public')->delete($employee->passport);
                }

                $path = $request->file('passport')->store('passports', 'public');
                $updateData['passport'] = $path;
            }

            $employee->update($updateData);
        }

        return redirect()->back()->with('success', 'Your Service Account has been updated successfully!');
    }
}