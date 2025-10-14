<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/employee/validate/{employee_number?}', [EmployeeApiController::class, 'validateCivilServant']);
Route::post('/employee/update/{employee_number?}', [EmployeeApiController::class, 'updateContact']);
