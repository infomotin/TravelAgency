<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgencyDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LeavePolicyController;
use App\Http\Controllers\HRReportController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SalaryStructureController;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Accounting\AccountController as AccountingAccountController;
use App\Http\Controllers\Accounting\TransactionController as AccountingTransactionController;
use App\Http\Controllers\Accounting\AccountingReportController;
use App\Http\Controllers\Accounting\BillController;
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
Route::middleware(['auth'])->get('/dashboard', [AgencyDashboardController::class, 'index'])->name('agency.dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('agencies', \App\Http\Controllers\AgencyController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class)->except('show');
    Route::resource('designations', DesignationController::class)->except('show');
    Route::resource('shifts', ShiftController::class)->except('show');
    Route::resource('leave_policies', LeavePolicyController::class)->except('show');
    Route::resource('holidays', HolidayController::class)->except('show');
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('calendar/generate', [CalendarController::class, 'generate'])->name('calendar.generate');
    Route::put('calendar/{calendarDate}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::get('reports/hr/employees', [HRReportController::class, 'employeeSummary'])->name('hr_reports.employees');
    Route::get('reports/hr/attendance', [HRReportController::class, 'attendance'])->name('hr_reports.attendance');
    Route::get('reports/hr/leaves', [HRReportController::class, 'leaves'])->name('hr_reports.leaves');
    Route::get('payroll/salary-structures', [SalaryStructureController::class, 'index'])->name('payroll.salary_structures.index');
    Route::get('payroll/salary-structures/{employee}/edit', [SalaryStructureController::class, 'edit'])->name('payroll.salary_structures.edit');
    Route::put('payroll/salary-structures/{employee}', [SalaryStructureController::class, 'update'])->name('payroll.salary_structures.update');
    Route::resource('payroll/advances', AdvanceController::class)->only(['index', 'create', 'store', 'destroy'])->names('payroll.advances');
    Route::get('payroll/payslips', [PayslipController::class, 'index'])->name('payroll.payslips.index');
    Route::get('payroll/payslips/create', [PayslipController::class, 'create'])->name('payroll.payslips.create');
    Route::post('payroll/payslips', [PayslipController::class, 'store'])->name('payroll.payslips.store');
    Route::post('payroll/payslips/{payslip}/approve', [PayslipController::class, 'approve'])->name('payroll.payslips.approve');
    Route::resource('accounts', AccountingAccountController::class)->except('show');
    Route::resource('transactions', AccountingTransactionController::class);
    Route::resource('parties', \App\Http\Controllers\Accounting\PartyController::class);
    Route::resource('bills', BillController::class);
    Route::get('bills/{bill}/pay', [BillController::class, 'payForm'])->name('bills.pay');
    Route::post('bills/{bill}/pay', [BillController::class, 'storePayment'])->name('bills.pay.store');
    Route::delete('bill-attachments/{attachment}', [BillController::class, 'destroyAttachment'])->name('bill_attachments.destroy');
    Route::get('reports/accounting/ledger', [AccountingReportController::class, 'ledger'])->name('accounting.reports.ledger');
    Route::get('reports/accounting/trial-balance', [AccountingReportController::class, 'trialBalance'])->name('accounting.reports.trial_balance');
    Route::get('reports/accounting/profit-loss', [AccountingReportController::class, 'profitLoss'])->name('accounting.reports.profit_loss');
    Route::resource('roles', RoleController::class)->except('show');
    Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])->name('roles.permissions.edit');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    Route::get('roles/{role}/users', [RoleController::class, 'editUsers'])->name('roles.users.edit');
    Route::put('roles/{role}/users', [RoleController::class, 'updateUsers'])->name('roles.users.update');
    Route::resource('permissions', PermissionController::class)->except('show');
    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::get('admin/users/{user}/password', [AdminUserController::class, 'editPassword'])->name('admin.users.password.edit');
    Route::put('admin/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('admin.users.password.update');
});
