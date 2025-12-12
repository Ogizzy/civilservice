<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\PromotionHistoryController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('api')->group(function () {
    // LOGIN
    Route::post('/login', [AuthController::class, 'login']);

    // PASSWORD RESET
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes (Requires Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::middleware(['api', 'auth:sanctum'])->group(function () {

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
    Route::get('/leave/info', [LeaveController::class, 'info']);
    Route::post('/leave/apply', [LeaveController::class, 'apply']);
    Route::get('/leave/history', [LeaveController::class, 'history']);

    // TRANSFER APIs
    Route::get('/transfers', [TransferController::class, 'index']);
    Route::post('/transfers/request', [TransferController::class, 'requestTransfer']);

    // PROMOTION APIs
    Route::get('/promotions', [PromotionHistoryController::class, 'index']);
    Route::post('/promotions/request', [PromotionHistoryController::class, 'requestPromotion']);
});
