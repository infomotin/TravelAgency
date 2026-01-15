<?php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use Illuminate\Http\Request;

class LeavePolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hr_setup.view')->only(['index']);
        $this->middleware('permission:hr_setup.create')->only(['create', 'store']);
        $this->middleware('permission:hr_setup.update')->only(['edit', 'update']);
        $this->middleware('permission:hr_setup.delete')->only(['destroy']);
    }

    public function index()
    {
        $policies = LeavePolicy::where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->paginate(20);
        return view('leave_policies.index', compact('policies'));
    }

    public function create()
    {
        return view('leave_policies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'annual_quota' => ['required', 'integer', 'min:0'],
            'carry_forward' => ['sometimes', 'boolean'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        $validated['carry_forward'] = $request->boolean('carry_forward');
        LeavePolicy::create($validated);
        return redirect()->route('leave_policies.index');
    }

    public function edit(LeavePolicy $leave_policy)
    {
        return view('leave_policies.edit', ['policy' => $leave_policy]);
    }

    public function update(Request $request, LeavePolicy $leave_policy)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'annual_quota' => ['required', 'integer', 'min:0'],
            'carry_forward' => ['sometimes', 'boolean'],
        ]);
        $validated['carry_forward'] = $request->boolean('carry_forward');
        $leave_policy->update($validated);
        return redirect()->route('leave_policies.index');
    }

    public function destroy(LeavePolicy $leave_policy)
    {
        $leave_policy->delete();
        return redirect()->route('leave_policies.index');
    }
}
