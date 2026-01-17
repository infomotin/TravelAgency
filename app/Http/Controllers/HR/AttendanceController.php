<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\CalendarDate;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'in_time' => ['nullable'],
            'out_time' => ['nullable'],
            'late_minutes' => ['nullable', 'integer', 'min:0'],
            'early_leave_minutes' => ['nullable', 'integer', 'min:0'],
            'overtime_minutes' => ['nullable', 'integer', 'min:0'],
            'source' => ['required', 'in:manual,biometric'],
        ]);
        $validated['employee_id'] = $employee->id;

        $agencyId = $employee->agency_id;
        $date = $validated['date'];
        $calStatus = CalendarDate::statusFor($agencyId, $date);
        if (in_array($calStatus, ['HD', 'GHD', 'OHD'], true)) {
            $validated['status'] = 'holiday';
            $validated['in_time'] = null;
            $validated['out_time'] = null;
            $validated['late_minutes'] = 0;
            $validated['early_leave_minutes'] = 0;
            $validated['overtime_minutes'] = 0;
        } else {
            $validated['status'] = 'present';
        }

        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $validated['date']],
            $validated
        );

        return response()->json($record);
    }
}
