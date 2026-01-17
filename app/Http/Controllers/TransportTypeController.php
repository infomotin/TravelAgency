<?php

namespace App\Http\Controllers;

use App\Models\TransportType;
use Illuminate\Http\Request;

class TransportTypeController extends Controller
{
    public function index()
    {
        $agencyId = app('currentAgency')->id;

        $transportTypes = TransportType::where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        return view('transport_types.index', compact('transportTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        $validated['status'] = 'active';

        TransportType::create($validated);

        return redirect()->route('transport_types.index')->with('success', 'Transport type created.');
    }

    public function update(Request $request, TransportType $transport_type)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $transport_type->update($validated);

        return redirect()->route('transport_types.index')->with('success', 'Transport type updated.');
    }

    public function destroy(TransportType $transport_type)
    {
        $transport_type->delete();

        return redirect()->route('transport_types.index')->with('success', 'Transport type deleted.');
    }
}
