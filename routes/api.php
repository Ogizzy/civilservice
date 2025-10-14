<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/employee/validate-civil-servant/{employee_number?}', [EmployeeApiController::class, 'validateCivilServant']);
Route::post('/employee/update-contact/{employee_number?}', [EmployeeApiController::class, 'updateContact']);
