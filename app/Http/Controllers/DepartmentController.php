<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->paginate(20);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        Department::create($validated);
        return redirect()->route('departments.index');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $department->update($validated);
        return redirect()->route('departments.index');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index');
    }
}

