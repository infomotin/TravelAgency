<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Passport;
use App\Models\PassportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PassportController extends Controller
{
    public function index()
    {
        $passports = Passport::where('agency_id', app('currentAgency')->id)
            ->latest()
            ->paginate(20);

        return view('passports.index', compact('passports'));
    }

    public function setup()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        $airlines = Airline::orderBy('name')->get();
        $airports = DB::table('airports')->orderBy('name')->get();
        $ticketAgencies = DB::table('ticket_agencies')->orderBy('name')->get();
        $currencies = DB::table('currencies')->orderBy('code')->get();
        $localAgents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();

        return view('passports.setup', compact('countries', 'airlines', 'airports', 'ticketAgencies', 'currencies', 'localAgents'));
    }

    public function storeCountry(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iso_code' => ['nullable', 'string', 'max:3', 'unique:countries,iso_code'],
        ]);

        DB::table('countries')->insert([
            'name' => $validated['name'],
            'iso_code' => $validated['iso_code'] ?? null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('passports.setup')->with('success', 'Country added successfully.');
    }

    public function storeAirport(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['nullable', 'string', 'max:3', 'unique:airports,iata_code'],
            'city' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ]);

        DB::table('airports')->insert([
            'name' => $validated['name'],
            'iata_code' => $validated['iata_code'] ?? null,
            'city' => $validated['city'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('passports.setup')->with('success', 'Airport added successfully.');
    }

    public function storeTicketAgency(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        DB::table('ticket_agencies')->insert([
            'name' => $validated['name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('passports.setup')->with('success', 'Ticket Agency added successfully.');
    }

    public function storeCurrency(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:3', 'unique:currencies,code'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['nullable', 'string', 'max:8'],
        ]);

        DB::table('currencies')->insert([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'symbol' => $validated['symbol'] ?? null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('passports.setup')->with('success', 'Currency added successfully.');
    }

    public function storeLocalAgent(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'commission_type' => ['required', 'in:percentage,fixed'],
            'commission_value' => ['required', 'numeric', 'min:0'],
        ]);

        DB::table('local_agents')->insert([
            'agency_id' => app('currentAgency')->id,
            'name' => $validated['name'],
            'commission_type' => $validated['commission_type'],
            'commission_value' => $validated['commission_value'],
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('passports.setup')->with('success', 'Local Agent added successfully.');
    }

    public function create()
    {
        $countries = \Illuminate\Support\Facades\DB::table('countries')->orderBy('name')->get();
        $airlines = \App\Models\Airline::orderBy('name')->get();
        $airports = \Illuminate\Support\Facades\DB::table('airports')->orderBy('name')->get();
        $ticketAgencies = \Illuminate\Support\Facades\DB::table('ticket_agencies')->orderBy('name')->get();
        $currencies = \Illuminate\Support\Facades\DB::table('currencies')->orderBy('code')->get();
        $localAgents = \Illuminate\Support\Facades\DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();
        return view('passports.create', compact('countries', 'airlines', 'airports', 'ticketAgencies', 'currencies', 'localAgents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'holder_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'local_agent_id' => 'nullable|integer|exists:local_agents,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'airport_id' => 'nullable|integer|exists:airports,id',
            'airline_id' => 'nullable|integer|exists:airlines,id',
            'ticket_agency_id' => 'nullable|integer|exists:ticket_agencies,id',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'passport_no' => 'required|string|max:50|unique:passports,passport_no',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'required|date',
            'purpose' => 'nullable|string|max:100',
            'document' => 'nullable|file|max:10240',
            'front' => 'nullable|file|max:10240',
            'back' => 'nullable|file|max:10240',
            'endorsement' => 'nullable|array',
            'endorsement.*' => 'nullable|file|max:10240',
            'visa' => 'nullable|array',
            'visa.*' => 'nullable|file|max:10240',
            'entry_charge' => 'nullable|numeric|min:0',
            'person_commission' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
        ]);

        $path = null;

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('passport_documents', 'public');
        }

        $entryCharge = $validated['is_free'] ? 0 : ($validated['entry_charge'] ?? 0);

        $localAgentId = $validated['local_agent_id'] ?? null;
        $commissionType = null;
        $commissionValue = 0;
        $agentCommissionAmount = 0;

        $localAgentName = null;

        if ($localAgentId) {
            $agent = DB::table('local_agents')
                ->where('id', $localAgentId)
                ->where('agency_id', app('currentAgency')->id)
                ->first();
            if ($agent) {
                $localAgentName = $agent->name;
                $commissionType = $agent->commission_type;
                $commissionValue = (float) $agent->commission_value;
                if ($commissionValue > 0 && $entryCharge > 0) {
                    if ($commissionType === 'percentage') {
                        $agentCommissionAmount = round($entryCharge * ($commissionValue / 100), 2);
                    } else {
                        $agentCommissionAmount = $commissionValue;
                    }
                }
            }
        }

        $passport = Passport::create([
            'agency_id' => app('currentAgency')->id,
            'holder_name' => $validated['holder_name'],
            'mobile' => $validated['mobile'] ?? null,
            'address' => $validated['address'] ?? null,
            'local_agent_id' => $localAgentId,
            'country_id' => $validated['country_id'] ?? null,
            'airport_id' => $validated['airport_id'] ?? null,
            'airline_id' => $validated['airline_id'] ?? null,
            'ticket_agency_id' => $validated['ticket_agency_id'] ?? null,
            'currency_id' => $validated['currency_id'] ?? null,
            'passport_no' => $validated['passport_no'],
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'],
            'document_path' => $path,
            'entry_charge' => $entryCharge,
            'person_commission' => $validated['person_commission'] ?? 0,
            'is_free' => $validated['is_free'] ?? false,
            'purpose' => $validated['purpose'] ?? null,
            'local_agent_name' => $localAgentName,
            'local_agent_commission_type' => $commissionType,
            'local_agent_commission_value' => $commissionValue,
            'local_agent_commission_amount' => $agentCommissionAmount,
        ]);

        if ($request->hasFile('front')) {
            $file = $request->file('front');
            $p = $file->store('passport_attachments', 'public');
            $passport->attachments()->create([
                'type' => 'front',
                'file_path' => $p,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->hasFile('back')) {
            $file = $request->file('back');
            $p = $file->store('passport_attachments', 'public');
            $passport->attachments()->create([
                'type' => 'back',
                'file_path' => $p,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->hasFile('endorsement')) {
            foreach ($request->file('endorsement') as $file) {
                $p = $file->store('passport_attachments', 'public');
                $passport->attachments()->create([
                    'type' => 'endorsement',
                    'file_path' => $p,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        if ($request->hasFile('visa')) {
            foreach ($request->file('visa') as $file) {
                $p = $file->store('passport_attachments', 'public');
                $passport->attachments()->create([
                    'type' => 'visa',
                    'file_path' => $p,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('passports.index')->with('success', 'Passport created successfully.');
    }

    public function show(Passport $passport)
    {
        $this->authorizeAgency($passport);

        return view('passports.show', compact('passport'));
    }

    public function barcode(Passport $passport)
    {
        $this->authorizeAgency($passport);
        return view('passports.barcode', compact('passport'));
    }

    public function report(Request $request)
    {
        $agencyId = app('currentAgency')->id;

        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'purpose' => ['nullable', 'string'],
            'local_agent_name' => ['nullable', 'string'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ]);

        $countries = DB::table('countries')->orderBy('name')->get();
        $localAgents = DB::table('passports')
            ->where('agency_id', $agencyId)
            ->whereNotNull('local_agent_name')
            ->distinct()
            ->orderBy('local_agent_name')
            ->pluck('local_agent_name');

        $query = DB::table('passports')
            ->leftJoin('countries', 'passports.country_id', '=', 'countries.id')
            ->where('passports.agency_id', $agencyId);

        if (!empty($filters['from_date'])) {
            $query->whereDate('passports.issue_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('passports.issue_date', '<=', $filters['to_date']);
        }

        if (!empty($filters['purpose'])) {
            $query->where('passports.purpose', $filters['purpose']);
        }

        if (!empty($filters['local_agent_name'])) {
            $query->where('passports.local_agent_name', $filters['local_agent_name']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('passports.country_id', $filters['country_id']);
        }

        $rows = $query
            ->selectRaw('passports.purpose, passports.local_agent_name, passports.country_id, countries.name as country_name, count(*) as total_passports, sum(passports.entry_charge) as total_entry_charge, sum(passports.local_agent_commission_amount) as total_agent_commission')
            ->groupBy('passports.purpose', 'passports.local_agent_name', 'passports.country_id', 'countries.name')
            ->orderBy('countries.name')
            ->orderBy('passports.local_agent_name')
            ->orderBy('passports.purpose')
            ->get();

        $purposes = [
            '' => 'All',
            'visa' => 'Visa',
            'ticket' => 'Ticket',
            'both' => 'Visa + Ticket',
            'other' => 'Other',
        ];

        return view('passports.report', [
            'rows' => $rows,
            'countries' => $countries,
            'localAgents' => $localAgents,
            'purposes' => $purposes,
            'filters' => $filters,
        ]);
    }

    public function reportPdf(Request $request)
    {
        $agencyId = app('currentAgency')->id;

        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'purpose' => ['nullable', 'string'],
            'local_agent_name' => ['nullable', 'string'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ]);

        $query = DB::table('passports')
            ->leftJoin('countries', 'passports.country_id', '=', 'countries.id')
            ->where('passports.agency_id', $agencyId);

        if (!empty($filters['from_date'])) {
            $query->whereDate('passports.issue_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('passports.issue_date', '<=', $filters['to_date']);
        }

        if (!empty($filters['purpose'])) {
            $query->where('passports.purpose', $filters['purpose']);
        }

        if (!empty($filters['local_agent_name'])) {
            $query->where('passports.local_agent_name', $filters['local_agent_name']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('passports.country_id', $filters['country_id']);
        }

        $rows = $query
            ->selectRaw('passports.purpose, passports.local_agent_name, passports.country_id, countries.name as country_name, count(*) as total_passports, sum(passports.entry_charge) as total_entry_charge, sum(passports.local_agent_commission_amount) as total_agent_commission')
            ->groupBy('passports.purpose', 'passports.local_agent_name', 'passports.country_id', 'countries.name')
            ->orderBy('countries.name')
            ->orderBy('passports.local_agent_name')
            ->orderBy('passports.purpose')
            ->get();

        $purposes = [
            'visa' => 'Visa',
            'ticket' => 'Ticket',
            'both' => 'Visa + Ticket',
            'other' => 'Other',
        ];

        $pdf = Pdf::loadView('passports.report_pdf', [
            'rows' => $rows,
            'filters' => $filters,
            'purposes' => $purposes,
        ])->setPaper('a4', 'portrait');

        $nameParts = [];
        if (!empty($filters['from_date']) || !empty($filters['to_date'])) {
            $nameParts[] = ($filters['from_date'] ?? 'from') . '-' . ($filters['to_date'] ?? 'to');
        }
        if (!empty($filters['purpose'])) {
            $nameParts[] = $filters['purpose'];
        }
        $fileName = 'passport-report-' . (empty($nameParts) ? 'all' : implode('-', $nameParts)) . '.pdf';

        return $pdf->download($fileName);
    }

    public function edit(Passport $passport)
    {
        $this->authorizeAgency($passport);
        $countries = \Illuminate\Support\Facades\DB::table('countries')->orderBy('name')->get();
        $airlines = \App\Models\Airline::orderBy('name')->get();
        $airports = \Illuminate\Support\Facades\DB::table('airports')->orderBy('name')->get();
        $ticketAgencies = \Illuminate\Support\Facades\DB::table('ticket_agencies')->orderBy('name')->get();
        $currencies = \Illuminate\Support\Facades\DB::table('currencies')->orderBy('code')->get();
        $localAgents = \Illuminate\Support\Facades\DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();
        return view('passports.edit', compact('passport', 'countries', 'airlines', 'airports', 'ticketAgencies', 'currencies', 'localAgents'));
    }

    public function update(Request $request, Passport $passport)
    {
        $this->authorizeAgency($passport);

        $validated = $request->validate([
            'holder_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'local_agent_id' => 'nullable|integer|exists:local_agents,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'airport_id' => 'nullable|integer|exists:airports,id',
            'airline_id' => 'nullable|integer|exists:airlines,id',
            'ticket_agency_id' => 'nullable|integer|exists:ticket_agencies,id',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'passport_no' => 'required|string|max:50|unique:passports,passport_no,' . $passport->id,
            'issue_date' => 'nullable|date',
            'expiry_date' => 'required|date',
            'purpose' => 'nullable|string|max:100',
            'document' => 'nullable|file|max:10240',
            'front' => 'nullable|file|max:10240',
            'back' => 'nullable|file|max:10240',
            'endorsement' => 'nullable|array',
            'endorsement.*' => 'nullable|file|max:10240',
            'visa' => 'nullable|array',
            'visa.*' => 'nullable|file|max:10240',
            'entry_charge' => 'nullable|numeric|min:0',
            'person_commission' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
        ]);

        $path = $passport->document_path;

        if ($request->hasFile('document')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            $path = $request->file('document')->store('passport_documents', 'public');
        }

        $entryCharge = $validated['is_free'] ? 0 : ($validated['entry_charge'] ?? 0);

        $localAgentId = $validated['local_agent_id'] ?? null;
        $commissionType = null;
        $commissionValue = 0;
        $agentCommissionAmount = 0;
        $localAgentName = null;

        if ($localAgentId) {
            $agent = DB::table('local_agents')
                ->where('id', $localAgentId)
                ->where('agency_id', app('currentAgency')->id)
                ->first();
            if ($agent) {
                $localAgentName = $agent->name;
                $commissionType = $agent->commission_type;
                $commissionValue = (float) $agent->commission_value;
                if ($commissionValue > 0 && $entryCharge > 0) {
                    if ($commissionType === 'percentage') {
                        $agentCommissionAmount = round($entryCharge * ($commissionValue / 100), 2);
                    } else {
                        $agentCommissionAmount = $commissionValue;
                    }
                }
            }
        }

        $passport->update([
            'holder_name' => $validated['holder_name'],
            'mobile' => $validated['mobile'] ?? null,
            'address' => $validated['address'] ?? null,
            'local_agent_id' => $localAgentId,
            'country_id' => $validated['country_id'] ?? null,
            'airport_id' => $validated['airport_id'] ?? null,
            'airline_id' => $validated['airline_id'] ?? null,
            'ticket_agency_id' => $validated['ticket_agency_id'] ?? null,
            'currency_id' => $validated['currency_id'] ?? null,
            'passport_no' => $validated['passport_no'],
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'],
            'document_path' => $path,
            'entry_charge' => $entryCharge,
            'person_commission' => $validated['person_commission'] ?? 0,
            'is_free' => $validated['is_free'] ?? false,
            'purpose' => $validated['purpose'] ?? null,
            'local_agent_name' => $localAgentName,
            'local_agent_commission_type' => $commissionType,
            'local_agent_commission_value' => $commissionValue,
            'local_agent_commission_amount' => $agentCommissionAmount,
        ]);

        if ($request->hasFile('front')) {
            $file = $request->file('front');
            $p = $file->store('passport_attachments', 'public');
            $passport->attachments()->create([
                'type' => 'front',
                'file_path' => $p,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->hasFile('back')) {
            $file = $request->file('back');
            $p = $file->store('passport_attachments', 'public');
            $passport->attachments()->create([
                'type' => 'back',
                'file_path' => $p,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->hasFile('endorsement')) {
            foreach ($request->file('endorsement') as $file) {
                $p = $file->store('passport_attachments', 'public');
                $passport->attachments()->create([
                    'type' => 'endorsement',
                    'file_path' => $p,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        if ($request->hasFile('visa')) {
            foreach ($request->file('visa') as $file) {
                $p = $file->store('passport_attachments', 'public');
                $passport->attachments()->create([
                    'type' => 'visa',
                    'file_path' => $p,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('passports.index')->with('success', 'Passport updated successfully.');
    }

    public function destroy(Passport $passport)
    {
        $this->authorizeAgency($passport);

        if ($passport->document_path) {
            Storage::disk('public')->delete($passport->document_path);
        }

        $passport->delete();

        return redirect()->route('passports.index')->with('success', 'Passport deleted successfully.');
    }

    public function destroyAttachment(\App\Models\PassportAttachment $attachment)
    {
        $passport = $attachment->passport;
        $this->authorizeAgency($passport);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        return back()->with('success', 'Attachment deleted.');
    }

    protected function authorizeAgency(Passport $passport): void
    {
        if ($passport->agency_id !== app('currentAgency')->id) {
            abort(404);
        }
    }
}
