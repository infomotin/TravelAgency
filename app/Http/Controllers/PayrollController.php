<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function generate(Request $request, Employee $employee, PayrollService $service)
    {
        $validated = $request->validate([
            'month' => ['required', 'regex:/^\\d{4}-\\d{2}$/'],
        ]);
        $payslip = $service->generatePayslip($employee, $validated['month']);
        return response()->json($payslip);
    }
}

