<?php

use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\PostingController;
use App\Http\Controllers\Api\PromotionApiController;
use App\Http\Controllers\Api\PromotionHistoryController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // LOGIN
    Route::post('/login', [AuthController::class, 'login']);

    // PASSWORD RESET
  
    // Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});

// Reset Link Based Password Reset for Web
Route::post('/forgot-password', 
    [PasswordResetController::class, 'sendResetLink']);

Route::post('/reset-password', 
    [PasswordResetController::class, 'resetPassword']);

// OTP Based Password Reset for Mobile App
Route::prefix('password')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])
        ->middleware('throttle:5,1');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-otp', [AuthController::class, 'resetPasswordWithOtp']);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes (Requires Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // EMPLOYEE APIs
    Route::get('/employee/validate/{employee_number?}', [EmployeeApiController::class, 'validateCivilServant']);
    Route::post('/employee/update/{employee_number}', [EmployeeApiController::class, 'updateContact']);
    Route::get('/civil-service/employees', [EmployeeApiController::class, 'index']);
    Route::get('/employees/by-year/{year}', [EmployeeApiController::class, 'getEmployeesByYear']);

    // Employee APIs
    Route::get('/employee/profile', [EmployeeApiController::class, 'employeeProfile']);
    Route::put('/employee/update', [EmployeeApiController::class, 'updateEmployee']);
    Route::post('/employee/upload-image', [EmployeeApiController::class, 'uploadImage']);

    // LEAVE APIs
    Route::get('/leave/balances', [LeaveController::class, 'leaveBalances']);
    Route::get('/leave/history', [LeaveController::class, 'history']);
    Route::post('/leave/apply', [LeaveController::class, 'apply']);
    Route::get('/leave/{id}', [LeaveController::class, 'show']);
    Route::post('/leave/{id}/cancel', [LeaveController::class, 'cancel']);
    

    // TRANSFER APIs 
    Route::get('/employees/{employee}/transfers', [TransferController::class, 'index']);
    Route::post('/employees/{employee}/transfers', [TransferController::class, 'store']);
    Route::get('/employees/{employee}/transfers/{transfer}', [TransferController::class, 'show']);
    Route::delete('/employees/{employee}/transfers/{transfer}', [TransferController::class, 'destroy']);

    // PROMOTION APIs
    Route::get('/employees/{employee}/promotions', [PromotionApiController::class, 'index']);
    Route::post('/employees/{employee}/promotions', [PromotionApiController::class, 'store']);
    Route::get('/employees/{employee}/promotions/{promotion}', [PromotionApiController::class, 'show']);
    Route::delete('/employees/{employee}/promotions/{promotion}', [PromotionApiController::class, 'destroy']);
    
    // Employee Posting APIs
    Route::prefix('postings')->group(function () {

    Route::get('/', [PostingController::class, 'index']); // employee postings
    Route::post('/create', [PostingController::class, 'store']);

    Route::get('/{id}', [PostingController::class, 'show'])
        ->where('id', '[0-9]+');

    Route::put('/{id}', [PostingController::class, 'update']);
    Route::delete('/{id}', [PostingController::class, 'destroy']);
});

Route::get('/mdas', function () {
    return \App\Models\MDA::select('id','mda')->get();
});

    });
