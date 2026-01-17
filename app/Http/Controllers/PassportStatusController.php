<?php

namespace App\Http\Controllers;

use App\Models\PassportStatus;
use Illuminate\Http\Request;

class PassportStatusController extends Controller
{
    public function index()
    {
        $agencyId = app('currentAgency')->id;

        $statuses = PassportStatus::where('agency_id', $agencyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('passport_statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        $validated['status'] = 'active';

        PassportStatus::create($validated);

        return redirect()->route('passport_statuses.index')->with('success', 'Passport status created.');
    }

    public function update(Request $request, PassportStatus $passport_status)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $passport_status->update($validated);

        return redirect()->route('passport_statuses.index')->with('success', 'Passport status updated.');
    }

    public function destroy(PassportStatus $passport_status)
    {
        $passport_status->delete();

        return redirect()->route('passport_statuses.index')->with('success', 'Passport status deleted.');
    }
}
