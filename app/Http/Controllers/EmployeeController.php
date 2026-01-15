<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employees.view')->only(['index', 'show']);
        $this->middleware('permission:employees.create')->only(['create', 'store']);
        $this->middleware('permission:employees.update')->only(['edit', 'update']);
        $this->middleware('permission:employees.delete')->only(['destroy']);
    }

    public function index()
    {
        $employees = Employee::where('agency_id', app('currentAgency')->id)->paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $agencyId = app('currentAgency')->id;
        $departments = Department::where('agency_id', $agencyId)->orderBy('name')->get();
        $designations = Designation::where('agency_id', $agencyId)->orderBy('name')->get();
        $shifts = Shift::where('agency_id', $agencyId)->orderBy('name')->get();
        return view('employees.create', compact('departments', 'designations', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        $employee = Employee::create($validated);
        return redirect()->route('employees.show', $employee);
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $agencyId = app('currentAgency')->id;
        $departments = Department::where('agency_id', $agencyId)->orderBy('name')->get();
        $designations = Designation::where('agency_id', $agencyId)->orderBy('name')->get();
        $shifts = Shift::where('agency_id', $agencyId)->orderBy('name')->get();
        return view('employees.edit', compact('employee', 'departments', 'designations', 'shifts'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code,' . $employee->id],
            'name' => ['required', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);
        $employee->update($validated);
        return redirect()->route('employees.show', $employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }
}
