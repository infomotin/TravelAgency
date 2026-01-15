<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\CommissionService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request, CommissionService $commissionService)
    {
        $validated = $request->validate([
            'airline_id' => ['required', 'exists:airlines,id'],
            'ticket_no' => ['required', 'string', 'unique:tickets,ticket_no'],
            'passenger_name' => ['required', 'string'],
            'fare' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'issue_date' => ['required', 'date'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        $commission = $commissionService->calculateForAirline($validated['airline_id'], (float)$validated['fare']);
        $validated['commission_amount'] = $commission;
        $validated['agent_commission_amount'] = round($commission * 0.5, 2);
        $validated['profit_loss'] = round(($validated['fare'] + ($validated['tax'] ?? 0)) - $commission, 2);
        $ticket = Ticket::create($validated);
        return response()->json($ticket, 201);
    }
}

