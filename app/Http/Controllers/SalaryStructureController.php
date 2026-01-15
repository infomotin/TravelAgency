<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.view')->only(['index']);
        $this->middleware('permission:payroll.update')->only(['edit', 'update']);
    }

    public function index()
    {
        $structures = SalaryStructure::with('employee')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('payroll.salary_structures.index', compact('structures'));
    }

    public function edit(Employee $employee)
    {
        $structure = SalaryStructure::where('employee_id', $employee->id)->first();
        return view('payroll.salary_structures.edit', compact('employee', 'structure'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic' => ['required', 'numeric', 'min:0'],
            'house_rent' => ['nullable', 'numeric', 'min:0'],
            'medical' => ['nullable', 'numeric', 'min:0'],
            'transport' => ['nullable', 'numeric', 'min:0'],
            'overtime_rate_per_hour' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated = array_merge([
            'house_rent' => 0,
            'medical' => 0,
            'transport' => 0,
            'overtime_rate_per_hour' => 0,
        ], $validated);

        SalaryStructure::updateOrCreate(
            ['employee_id' => $employee->id],
            $validated
        );

        return redirect()->route('payroll.salary_structures.index');
    }
}
