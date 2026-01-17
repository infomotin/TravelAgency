<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::where('agency_id', app('currentAgency')->id)
            ->latest()
            ->paginate(20);

        return view('accounting.parties.index', compact('parties'));
    }

    public function create()
    {
        return view('accounting.parties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:customer,vendor',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric',
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        $validated['status'] = 'active';

        $party = Party::create($validated);

        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    public function show(Party $party)
    {
        // This will serve as the Party Balance Sheet / Ledger
        $party->load(['bills' => function ($query) {
            $query->latest('bill_date');
        }]);

        // Calculate current balance (Opening + Billed - Paid)
        // Note: Logic might need adjustment based on type (Customer vs Vendor)
        // For now assuming Customer logic (Debit increases balance)

        return view('accounting.parties.show', compact('party'));
    }

    public function edit(Party $party)
    {
        return view('accounting.parties.edit', compact('party'));
    }

    public function update(Request $request, Party $party)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:customer,vendor',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $party->update($validated);

        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        if ($party->bills()->exists()) {
            return back()->with('error', 'Cannot delete party with existing bills.');
        }

        $party->delete();

        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }
}
