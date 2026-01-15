<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
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
        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $validated['date']],
            $validated
        );
        return response()->json($record);
    }
}

