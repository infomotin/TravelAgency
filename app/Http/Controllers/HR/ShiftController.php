<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
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
        $shifts = Shift::where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->paginate(20);

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'grace_minutes' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        Shift::create($validated);

        return redirect()->route('shifts.index');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'grace_minutes' => ['nullable', 'integer', 'min:0'],
        ]);
        $shift->update($validated);

        return redirect()->route('shifts.index');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('shifts.index');
    }
}
