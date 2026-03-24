<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }

        // Create sanctum token
        $token = $user->createToken('mobile_app_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'employee_id' => optional($user->employee)->id, 
                'employee_number' => optional($user->employee)->employee_number,
                'surname' => $user->surname,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'status' => $user->status,
            ]
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Incorrect old password'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password reset successfully'], 200);
    }


    // OTP Based Password Reset Methods
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:employees,email'
        ]);

        $otp = rand(100000, 999999);

        DB::table('password_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

        // Mail::raw("Your reset code is: $otp", function ($message) use ($request) {
        //     $message->to($request->email)
        //             ->subject('Password Reset Code');
        // });

        $employee = Employee::where('email', $request->email)->first();

        Mail::raw("Dear {$employee->surname}, {$employee->first_name} {$employee->middle_name}.
Your password reset code for the Civil Service Management System is: $otp
This code will expire in 10 minutes. Do not share this code with anyone.
If you did not request a password reset, please ignore this email.

Regards,
Benue State Civil Service Commission", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset Code');
        });

        return response()->json([
            'status' => true,
            'message' => 'One Time Password (OTP) sent successfully'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $otpRecord = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        // Check if OTP exists
        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Check if OTP expired
        if (now()->gt($otpRecord->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $otp = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        if (now()->gt($otp->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_otps')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successful'
        ]);
    }
    //  End of OTP Based Password Reset Methods

}
