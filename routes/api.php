<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/employee/validate/{employee_number?}', [EmployeeApiController::class, 'validateCivilServant']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/employee/update/{employee_number}', [EmployeeApiController::class, 'updateContact']);
    Route::get('/civil-service/employees', [EmployeeApiController::class, 'index']);
    Route::get('/employees/by-year/{year}', [EmployeeApiController::class, 'getEmployeesByYear']);
});