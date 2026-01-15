<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
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
        $designations = Designation::where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->paginate(20);
        return view('designations.index', compact('designations'));
    }

    public function create()
    {
        return view('designations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        Designation::create($validated);
        return redirect()->route('designations.index');
    }

    public function edit(Designation $designation)
    {
        return view('designations.edit', compact('designation'));
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $designation->update($validated);
        return redirect()->route('designations.index');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designations.index');
    }
}
