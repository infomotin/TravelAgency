<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\CalendarDate;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hr_setup.view')->only(['index']);
        $this->middleware('permission:hr_setup.update')->only(['generate', 'update']);
    }

    public function index(Request $request)
    {
        $agencyId = app('currentAgency')->id;
        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));

        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        $dates = CalendarDate::where('agency_id', $agencyId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        return view('calendar.index', [
            'year' => $year,
            'month' => $month,
            'dates' => $dates,
        ]);
    }

    public function generate(Request $request)
    {
        $agencyId = app('currentAgency')->id;
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'weekly_day' => ['required', 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday'],
        ]);
        $year = $validated['year'];
        $weeklyDay = $validated['weekly_day'];

        $start = Carbon::create($year, 1, 1);
        $end = Carbon::create($year, 12, 31);

        $ghd = Holiday::where('agency_id', $agencyId)
            ->whereYear('date', $year)
            ->where('type', 'ghd')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->all();

        $ohd = Holiday::where('agency_id', $agencyId)
            ->whereYear('date', $year)
            ->where('type', 'ohd')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->all();

        DB::transaction(function () use ($agencyId, $start, $end, $weeklyDay, $ghd, $ohd) {
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $status = 'WD';
                $isoDow = strtolower($cursor->format('l'));
                $dateStr = $cursor->toDateString();

                if (in_array($dateStr, $ghd, true)) {
                    $status = 'GHD';
                } elseif (in_array($dateStr, $ohd, true)) {
                    $status = 'OHD';
                } elseif ($isoDow === $weeklyDay) {
                    $status = 'HD';
                }

                CalendarDate::updateOrCreate(
                    ['agency_id' => $agencyId, 'date' => $dateStr],
                    ['status' => $status]
                );
                $cursor->addDay();
            }
        });

        return redirect()->route('calendar.index', ['year' => $year])
            ->with('success', "Calendar generated for {$year} with weekly {$weeklyDay}.");
    }

    public function update(Request $request, CalendarDate $calendarDate)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:WD,HD,GHD,OHD'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);
        $calendarDate->update($validated);
        return back()->with('success', 'Date status updated.');
    }
}

