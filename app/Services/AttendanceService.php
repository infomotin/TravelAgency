<?php

namespace App\Services;

use App\Models\AttendanceRecord;

class AttendanceService
{
    public function summarizeMonthly(int $employeeId, string $month): array
    {
        $start = $month . '-01';
        $end = date('Y-m-t', strtotime($start));
        $records = AttendanceRecord::where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])->get();
        return [
            'days' => $records->count(),
            'late_minutes' => $records->sum('late_minutes'),
            'early_leave_minutes' => $records->sum('early_leave_minutes'),
            'overtime_minutes' => $records->sum('overtime_minutes'),
        ];
    }
}

