<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payslip;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.view')->only(['index']);
        $this->middleware('permission:payroll.create')->only(['create', 'store']);
        $this->middleware('permission:payroll.update')->only(['approve']);
    }

    public function index(Request $request)
    {
        $month = $request->query('month');
        $query = Payslip::with('employee');

        if ($month) {
            $query->where('month', $month);
        }

        $payslips = $query->orderByDesc('month')->orderBy('employee_id')->paginate(50);

        return view('payroll.payslips.index', [
            'payslips' => $payslips,
            'month' => $month,
        ]);
    }

    public function create()
    {
        $employees = Employee::where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('payroll.payslips.create', compact('employees'));
    }

    public function store(Request $request, PayrollService $service)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'regex:/^\d{4}-\d{2}$/'],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $service->generatePayslip($employee, $validated['month']);

        return redirect()->route('payroll.payslips.index', ['month' => $validated['month']]);
    }

    public function approve(Payslip $payslip)
    {
        $payslip->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back();
    }
}
