<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeavePolicy;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class HRReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hr_reports.view');
    }

    public function employeeSummary(Request $request)
    {
        $agencyId = app('currentAgency')->id;

        $departments = Department::where('agency_id', $agencyId)->orderBy('name')->get();
        $query = Employee::where('agency_id', $agencyId)->with(['department', 'designation', 'shift']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $employees = $query->orderBy('name')->paginate(50);

        return view('hr_reports.employees', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $request->only('department_id', 'status'),
        ]);
    }

    public function attendance(Request $request, AttendanceService $attendanceService)
    {
        $agencyId = app('currentAgency')->id;
        $month = $request->query('month', date('Y-m'));

        $employees = Employee::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $rows = [];
        foreach ($employees as $employee) {
            $summary = $attendanceService->summarizeMonthly($employee->id, $month);
            $rows[] = [
                'employee' => $employee,
                'days' => $summary['days'],
                'late_minutes' => $summary['late_minutes'],
                'early_leave_minutes' => $summary['early_leave_minutes'],
                'overtime_minutes' => $summary['overtime_minutes'],
            ];
        }

        return view('hr_reports.attendance', [
            'month' => $month,
            'rows' => $rows,
        ]);
    }

    public function leaves(Request $request)
    {
        $agencyId = app('currentAgency')->id;
        $policies = LeavePolicy::where('agency_id', $agencyId)->orderBy('name')->get();

        $from = $request->query('from');
        $to = $request->query('to');
        if (!$from || !$to) {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
        }

        $query = EmployeeLeave::with(['employee', 'policy'])
            ->whereBetween('start_date', [$from, $to]);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('leave_policy_id')) {
            $query->where('leave_policy_id', $request->integer('leave_policy_id'));
        }

        $leaves = $query->orderBy('start_date')->paginate(50);

        return view('hr_reports.leaves', [
            'leaves' => $leaves,
            'policies' => $policies,
            'filters' => [
                'from' => $from,
                'to' => $to,
                'status' => $request->get('status'),
                'leave_policy_id' => $request->get('leave_policy_id'),
            ],
        ]);
    }
}
