<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'marital_status' => ['nullable', 'string'],
            'blood_group' => ['nullable', 'string'],
            'nid' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'emergency_contact_relation' => ['nullable', 'string'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employee-photos', 'public');
        }

        $employee = Employee::create($validated);

        if (! empty($employee->email)) {
            $this->ensureEmployeeUserAndRole($employee);
        }

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
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code,'.$employee->id],
            'name' => ['required', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'marital_status' => ['nullable', 'string'],
            'blood_group' => ['nullable', 'string'],
            'nid' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'emergency_contact_relation' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employee-photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.show', $employee);
    }

    protected function ensureEmployeeUserAndRole(Employee $employee): void
    {
        $email = $employee->email;
        if (! $email) {
            return;
        }

        $user = User::where('email', $email)->first();
        $passwordPlain = null;

        if (! $user) {
            $passwordPlain = Str::random(10);
            $user = User::create([
                'agency_id' => $employee->agency_id,
                'branch_id' => $employee->branch_id,
                'name' => $employee->name,
                'email' => $email,
                'status' => 'active',
                'password' => Hash::make($passwordPlain),
            ]);
        }

        if (! $employee->user_id) {
            $employee->user_id = $user->id;
            $employee->save();
        }

        $role = Role::firstOrCreate(
            ['slug' => 'employee'],
            [
                'name' => 'Employee',
                'agency_id' => $employee->agency_id,
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        if ($passwordPlain) {
            try {
                $loginUrl = route('login.form');
                $subject = 'Your Employee Account for TravelAgency ERP';
                $body = "Dear {$employee->name},\n\n"
                    ."An account has been created for you in the TravelAgency ERP.\n\n"
                    ."Login URL: {$loginUrl}\n"
                    ."Email: {$email}\n"
                    ."Password: {$passwordPlain}\n\n"
                    ."Please log in and change your password after first login.\n\n"
                    ."Regards,\n"
                    .'TravelAgency HR';

                Mail::raw($body, function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index');
    }
}
