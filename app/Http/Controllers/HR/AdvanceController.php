<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.view')->only(['index']);
        $this->middleware('permission:payroll.create')->only(['create', 'store']);
        $this->middleware('permission:payroll.delete')->only(['destroy']);
    }

    public function index()
    {
        $advances = Advance::with('employee')
            ->orderByDesc('date')
            ->paginate(20);

        return view('payroll.advances.index', compact('advances'));
    }

    public function create()
    {
        $employees = Employee::where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();

        return view('payroll.advances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        Advance::create($validated);

        return redirect()->route('payroll.advances.index');
    }

    public function destroy(Advance $advance)
    {
        $advance->delete();

        return redirect()->route('payroll.advances.index');
    }
}
