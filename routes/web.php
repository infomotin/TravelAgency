<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LeavePolicyController;
use App\Http\Controllers\HRReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::withoutMiddleware([\App\Http\Middleware\SetCurrentAgency::class])->get('/debug', function (Request $request) {
    return "Host: " . $request->getHost();
});

Route::get('/', function () {
    $agency = app('currentAgency');

    return view('welcome', [
        'currentAgency' => $agency,
        'title' => $agency->name,
    ]);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('agencies', \App\Http\Controllers\AgencyController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class)->except('show');
    Route::resource('designations', DesignationController::class)->except('show');
    Route::resource('shifts', ShiftController::class)->except('show');
    Route::resource('leave_policies', LeavePolicyController::class)->except('show');
    Route::get('reports/hr/employees', [HRReportController::class, 'employeeSummary'])->name('hr_reports.employees');
    Route::get('reports/hr/attendance', [HRReportController::class, 'attendance'])->name('hr_reports.attendance');
    Route::get('reports/hr/leaves', [HRReportController::class, 'leaves'])->name('hr_reports.leaves');
});
