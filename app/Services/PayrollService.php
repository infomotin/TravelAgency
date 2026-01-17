<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Payslip;
use App\Models\SalaryStructure;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function generatePayslip(Employee $employee, string $month): Payslip
    {
        return DB::transaction(function () use ($employee, $month) {
            $structure = SalaryStructure::where('employee_id', $employee->id)->firstOrFail();
            $gross = $structure->basic + $structure->house_rent + $structure->medical + $structure->transport;

            $start = $month.'-01';
            $end = date('Y-m-t', strtotime($start));

            $attendance = AttendanceRecord::where('employee_id', $employee->id)
                ->whereBetween('date', [$start, $end])
                ->get();

            $lateMinutes = $attendance->sum('late_minutes');
            $earlyMinutes = $attendance->sum('early_leave_minutes');
            $overtimeMinutes = $attendance->sum('overtime_minutes');

            $lateFine = ($lateMinutes / 60) * 50;
            $earlyFine = ($earlyMinutes / 60) * 50;
            $overtimeAmount = ($overtimeMinutes / 60) * $structure->overtime_rate_per_hour;

            $advances = DB::table('advances')->where('employee_id', $employee->id)
                ->whereBetween('date', [$start, $end])->sum('amount');

            $deductions = $lateFine + $earlyFine + $advances;
            $net = $gross - $deductions + $overtimeAmount;

            $payslip = Payslip::updateOrCreate(
                ['employee_id' => $employee->id, 'month' => $month],
                [
                    'gross' => round($gross, 2),
                    'deductions' => round($deductions, 2),
                    'overtime_amount' => round($overtimeAmount, 2),
                    'net' => round($net, 2),
                ]
            );

            return $payslip;
        });
    }
}
