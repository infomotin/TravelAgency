<?php

use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('employees/{employee}/attendance', [AttendanceController::class, 'store']);
    Route::post('employees/{employee}/payslips/generate', [PayrollController::class, 'generate']);
    Route::post('tickets', [TicketController::class, 'store']);
});
