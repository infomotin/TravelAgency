<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hr_setup.view')->only(['index']);
        $this->middleware('permission:hr_setup.create')->only(['create', 'store']);
        $this->middleware('permission:hr_setup.update')->only(['edit', 'update']);
        $this->middleware('permission:hr_setup.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $agencyId = app('currentAgency')->id;
        $query = Holiday::where('agency_id', $agencyId)->orderBy('date');

        if ($request->filled('year')) {
            $year = (int) $request->get('year');
            $query->whereYear('date', $year);
        } else {
            $year = (int) date('Y');
        }

        $holidays = $query->paginate(30);

        return view('hr.holidays.index', [
            'holidays' => $holidays,
            'year' => $year,
        ]);
    }

    public function create()
    {
        return view('hr.holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;

        Holiday::create($validated);

        return redirect()->route('holidays.index');
    }

    public function edit(Holiday $holiday)
    {
        return view('hr.holidays.edit', [
            'holiday' => $holiday,
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $holiday->update($validated);

        return redirect()->route('holidays.index');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index');
    }
}
