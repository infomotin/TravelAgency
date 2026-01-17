<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Party;
use App\Models\Passport;
use App\Models\Ticket;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('agency_id', app('currentAgency')->id)
            ->latest('issue_date')
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $clients = Party::where('agency_id', app('currentAgency')->id)
            ->where('type', 'customer')
            ->orderBy('name')
            ->get();

        $employees = DB::table('employees')
            ->where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();

        $agents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $vendors = DB::table('ticket_agencies')
            ->orderBy('name')
            ->get();

        $airlines = Airline::orderBy('name')->get();

        $airports = DB::table('airports')
            ->orderBy('name')
            ->get();

        $passports = Passport::where('agency_id', app('currentAgency')->id)
            ->orderBy('holder_name')
            ->orderBy('passport_no')
            ->get();

        return view('tickets.create', compact('clients', 'employees', 'agents', 'vendors', 'airlines', 'airports', 'passports'));
    }

    public function store(Request $request, CommissionService $commissionService)
    {
        $validated = $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:parties,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'invoice_no' => ['nullable', 'string', 'max:100', 'unique:tickets,invoice_no'],
            'airline_id' => ['required', 'integer', 'exists:airlines,id'],
            'from_airport_id' => ['nullable', 'integer', 'exists:airports,id'],
            'to_airport_id' => ['nullable', 'integer', 'exists:airports,id'],
            'ticket_no' => ['required', 'string', 'max:100', 'unique:tickets,ticket_no'],
            'pnr' => ['nullable', 'string', 'max:100'],
            'gds' => ['nullable', 'string', 'max:100'],
            'passenger_name' => ['required', 'string', 'max:255'],
            'passport_id' => ['nullable', 'integer', 'exists:passports,id'],
            'pax_type' => ['nullable', 'string', 'max:50'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_issue_date' => ['nullable', 'date'],
            'passport_expire_date' => ['nullable', 'date'],
            'fare' => ['required', 'numeric', 'min:0'],
            'base_fare' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'vendor_id' => ['nullable', 'integer', 'exists:ticket_agencies,id'],
            'commission_percent' => ['nullable', 'numeric', 'min:0'],
            'taxes_commission' => ['nullable', 'numeric', 'min:0'],
            'ait' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'extra_fee' => ['nullable', 'numeric', 'min:0'],
            'class' => ['nullable', 'string', 'max:50'],
            'ticket_type' => ['nullable', 'string', 'max:50'],
            'segment' => ['nullable', 'integer', 'min:1'],
            'issue_date' => ['required', 'date'],
            'journey_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'commission_7_percent' => ['nullable', 'numeric', 'min:0'],
            'client_price' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'country_tax_bd' => ['nullable', 'numeric', 'min:0'],
            'country_tax_ut' => ['nullable', 'numeric', 'min:0'],
            'country_tax_e5' => ['nullable', 'numeric', 'min:0'],
            'country_tax_es' => ['nullable', 'numeric', 'min:0'],
            'country_tax_xt' => ['nullable', 'numeric', 'min:0'],
            'country_tax_ow' => ['nullable', 'numeric', 'min:0'],
            'country_tax_qa' => ['nullable', 'numeric', 'min:0'],
            'country_tax_pz' => ['nullable', 'numeric', 'min:0'],
            'country_tax_g4' => ['nullable', 'numeric', 'min:0'],
            'country_tax_p7' => ['nullable', 'numeric', 'min:0'],
            'country_tax_p8' => ['nullable', 'numeric', 'min:0'],
            'country_tax_r9' => ['nullable', 'numeric', 'min:0'],
            'flight_from' => ['nullable', 'string', 'max:100'],
            'flight_to' => ['nullable', 'string', 'max:100'],
            'flight_airline' => ['nullable', 'string', 'max:100'],
            'flight_no' => ['nullable', 'string', 'max:100'],
            'flight_date' => ['nullable', 'date'],
            'departure_time' => ['nullable'],
            'arrival_time' => ['nullable'],
            'sales_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'agent_id' => ['nullable', 'integer', 'exists:local_agents,id'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;

        $invoiceNo = trim($validated['invoice_no'] ?? '');
        if ($invoiceNo === '') {
            $validated['invoice_no'] = $this->generateInvoiceNumber($validated['agency_id']);
        }

        $fare = (float) ($validated['fare'] ?? 0);
        $baseFare = (float) ($validated['base_fare'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);
        $commissionPercent = (float) ($validated['commission_percent'] ?? 0);
        $taxesCommission = (float) ($validated['taxes_commission'] ?? 0);
        $ait = (float) ($validated['ait'] ?? 0);

        $commissionAmount = 0;
        if ($commissionPercent > 0) {
            $commissionAmount = round($fare * ($commissionPercent / 100), 2);
        } else {
            $commissionAmount = $commissionService->calculateForAirline($validated['airline_id'], $fare);
        }

        $validated['commission_amount'] = $commissionAmount;
        $validated['taxes_commission'] = $taxesCommission;
        $validated['ait'] = $ait;
        $validated['net_commission'] = round($commissionAmount + $taxesCommission - $ait, 2);

        $validated['agent_commission_amount'] = round($commissionAmount * 0.5, 2);

        $clientPrice = (float) ($validated['client_price'] ?? $fare + $tax);
        $purchasePrice = (float) ($validated['purchase_price'] ?? $baseFare + $tax);

        $validated['client_price'] = $clientPrice;
        $validated['purchase_price'] = $purchasePrice;
        $validated['profit'] = round($clientPrice - $purchasePrice, 2);

        $validated['profit_loss'] = $validated['profit'];

        if (empty($validated['segment'])) {
            $validated['segment'] = 1;
        }

        $ticket = Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Air ticket invoice created successfully.');
    }

    public function invoice(Ticket $ticket)
    {
        if ($ticket->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $ticket->load('client', 'airline');

        if (! $ticket->invoice_no && $ticket->agency_id) {
            $ticket->invoice_no = $this->generateInvoiceNumber($ticket->agency_id);
            $ticket->save();
        }

        return view('tickets.invoice', compact('ticket'));
    }

    protected function generateInvoiceNumber(int $agencyId): string
    {
        $today = now()->format('Ymd');
        $prefix = 'TICKET-'.$today.'-';

        $lastInvoice = DB::table('tickets')
            ->where('agency_id', $agencyId)
            ->where('invoice_no', 'like', $prefix.'%')
            ->orderBy('invoice_no', 'desc')
            ->value('invoice_no');

        $nextNumber = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice);
            $lastSeq = (int) end($parts);
            if ($lastSeq > 0) {
                $nextNumber = $lastSeq + 1;
            }
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
