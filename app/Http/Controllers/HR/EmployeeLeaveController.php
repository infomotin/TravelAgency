<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeavePolicy;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeLeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hr_reports.view')->only(['index']);
        $this->middleware('permission:hr_setup.create')->only(['create', 'store']);
        $this->middleware('permission:hr_setup.update')->only(['update']);
        $this->middleware('permission:hr_setup.delete')->only(['destroy']);
    }

    public function create(Request $request)
    {
        $agencyId = app('currentAgency')->id;
        $employees = Employee::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        $policies = LeavePolicy::where('agency_id', $agencyId)->orderBy('name')->get();

        return view('hr.employee_leaves.create', compact('employees', 'policies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_policy_id' => ['required', 'exists:leave_policies,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable,', 'string'],
            'attachment' => ['nullable', 'file', 'max:4096'],
        ]);

        $agencyId = app('currentAgency')->id;
        $employee = Employee::where('id', $validated['employee_id'])
            ->where('agency_id', $agencyId)
            ->firstOrFail();

        $start = new \Carbon\Carbon($validated['start_date']);
        $end = new \Carbon\Carbon($validated['end_date']);
        $validated['days'] = $end->diffInDays($start) + 1;

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $validated['status'] = 'pending';

        EmployeeLeave::create($validated);

        return redirect()->route('employee_leaves.index');
    }

    public function index()
    {
        $agencyId = app('currentAgency')->id;

        $leaves = EmployeeLeave::with(['employee', 'policy'])
            ->whereHas('employee', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('hr.employee_leaves.index', compact('leaves'));
    }

    public function update(Request $request, EmployeeLeave $employee_leave)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $oldStatus = $employee_leave->status;
        $employee_leave->status = $validated['status'];
        $employee_leave->save();

        if ($oldStatus !== 'approved' && $employee_leave->status === 'approved') {
            $this->applyToAttendance($employee_leave);
        }

        return redirect()->route('employee_leaves.index');
    }

    public function destroy(EmployeeLeave $employee_leave)
    {
        if ($employee_leave->attachment_path) {
            Storage::disk('public')->delete($employee_leave->attachment_path);
        }
        $employee_leave->delete();

        return redirect()->route('employee_leaves.index');
    }

    protected function applyToAttendance(EmployeeLeave $leave): void
    {
        $employeeId = $leave->employee_id;
        $current = $leave->start_date->copy();
        $end = $leave->end_date->copy();

        while ($current->lte($end)) {
            AttendanceRecord::updateOrCreate(
                ['employee_id' => $employeeId, 'date' => $current->toDateString()],
                [
                    'status' => 'absent',
                    'in_time' => null,
                    'out_time' => null,
                    'late_minutes' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                    'source' => 'manual',
                ]
            );

            $current->addDay();
        }
    }
}
