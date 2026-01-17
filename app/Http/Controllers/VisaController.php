<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Visa;
use App\Models\VisaType;
use App\Models\VisaTypeDocument;
use App\Models\VisaDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function index()
    {
        $visas = Visa::with('passport')
            ->whereHas('passport', function ($query) {
                $query->where('agency_id', app('currentAgency')->id);
            })
            ->latest('issue_date')
            ->paginate(20);

        $countries = DB::table('countries')->orderBy('name')->get();
        $countryNames = $countries->pluck('name', 'id');

        $agents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->orderBy('name')
            ->get();
        $agentNames = $agents->pluck('name', 'id');

        return view('visas.index', compact('visas', 'countryNames', 'agentNames'));
    }

    public function create(Request $request)
    {
        $countries = DB::table('countries')->orderBy('name')->get();

        $passports = Passport::where('agency_id', app('currentAgency')->id)
            ->orderBy('holder_name')
            ->orderBy('passport_no')
            ->get();

        $agents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedPassportId = (int) $request->query('passport_id', 0);

        $countryId = (int) $request->query('country_id', 0);
        if ($countryId === 0 && $selectedPassportId > 0) {
            $passportForCountry = Passport::where('agency_id', app('currentAgency')->id)
                ->where('id', $selectedPassportId)
                ->first();
            if ($passportForCountry && $passportForCountry->country_id) {
                $countryId = (int) $passportForCountry->country_id;
            }
        }

        $visaTypes = VisaType::where('status', 'active')
            ->when($countryId > 0, function ($query) use ($countryId) {
                $query->where('country_id', $countryId);
            })
            ->orderBy('name')
            ->get();

        $typeDocuments = collect();
        if ($visaTypes->isNotEmpty()) {
            $typeDocuments = VisaTypeDocument::whereIn('visa_type_id', $visaTypes->pluck('id'))
                ->orderBy('name')
                ->get()
                ->groupBy('visa_type_id');
        }

        return view('visas.create', compact('countries', 'passports', 'agents', 'visaTypes', 'typeDocuments', 'countryId', 'selectedPassportId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'passport_id' => ['required', 'integer', 'exists:passports,id'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'visa_type_id' => ['required', 'integer', 'exists:visa_types,id'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['required', 'date'],
            'visa_fee' => ['nullable', 'numeric', 'min:0'],
            'agent_id' => ['nullable', 'integer', 'exists:local_agents,id'],
            'document' => ['nullable', 'file', 'max:10240'],
            'type_documents' => ['nullable', 'array'],
            'type_documents.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $passport = Passport::where('id', $validated['passport_id'])
            ->where('agency_id', app('currentAgency')->id)
            ->firstOrFail();

        $path = null;

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('visa_documents', 'public');
        }

        $visaType = VisaType::where('id', $validated['visa_type_id'])
            ->where('country_id', $validated['country_id'])
            ->firstOrFail();

        $visaFee = $validated['visa_fee'] ?? $visaType->default_fee ?? 0;
        $agentId = $validated['agent_id'] ?? null;
        $agentCommissionAmount = 0;

        if ($agentId) {
            $agent = DB::table('local_agents')
                ->where('id', $agentId)
                ->where('agency_id', app('currentAgency')->id)
                ->first();

            if ($agent && $visaFee > 0) {
                $commissionValue = (float) $agent->commission_value;
                if ($commissionValue > 0) {
                    if ($agent->commission_type === 'percentage') {
                        $agentCommissionAmount = round($visaFee * ($commissionValue / 100), 2);
                    } else {
                        $agentCommissionAmount = $commissionValue;
                    }
                }
            }
        }

        $visa = Visa::create([
            'passport_id' => $passport->id,
            'country_id' => $validated['country_id'] ?? null,
            'visa_type' => $visaType->name,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'],
            'visa_fee' => $visaFee,
            'visa_type_id' => $visaType->id,
            'agent_id' => $agentId,
            'agent_commission' => $agentCommissionAmount,
            'document_path' => $path,
        ]);

        if ($request->hasFile('type_documents')) {
            $allowedIds = VisaTypeDocument::where('visa_type_id', $visaType->id)->pluck('id')->all();
            foreach ($request->file('type_documents') as $docId => $file) {
                if (!$file) {
                    continue;
                }
                if (!in_array((int) $docId, $allowedIds, true)) {
                    continue;
                }
                $storedPath = $file->store('visa_documents', 'public');
                $visa->documents()->create([
                    'visa_type_document_id' => (int) $docId,
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('visas.index')->with('success', 'Visa record created successfully.');
    }

    public function edit(Visa $visa)
    {
        $this->authorizeVisa($visa);

        $countries = DB::table('countries')->orderBy('name')->get();

        $passports = Passport::where('agency_id', app('currentAgency')->id)
            ->orderBy('holder_name')
            ->orderBy('passport_no')
            ->get();

        $agents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $visaTypes = collect();
        if ($visa->country_id) {
            $visaTypes = VisaType::where('country_id', $visa->country_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        $typeDocuments = collect();
        if ($visaTypes->isNotEmpty()) {
            $typeDocuments = VisaTypeDocument::whereIn('visa_type_id', $visaTypes->pluck('id'))
                ->orderBy('name')
                ->get()
                ->groupBy('visa_type_id');
        }

        return view('visas.edit', compact('visa', 'countries', 'passports', 'agents', 'visaTypes', 'typeDocuments'));
    }

    public function update(Request $request, Visa $visa)
    {
        $this->authorizeVisa($visa);

        $validated = $request->validate([
            'passport_id' => ['required', 'integer', 'exists:passports,id'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'visa_type_id' => ['required', 'integer', 'exists:visa_types,id'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['required', 'date'],
            'visa_fee' => ['nullable', 'numeric', 'min:0'],
            'agent_id' => ['nullable', 'integer', 'exists:local_agents,id'],
            'document' => ['nullable', 'file', 'max:10240'],
            'type_documents' => ['nullable', 'array'],
            'type_documents.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $passport = Passport::where('id', $validated['passport_id'])
            ->where('agency_id', app('currentAgency')->id)
            ->firstOrFail();

        $path = $visa->document_path;

        if ($request->hasFile('document')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            $path = $request->file('document')->store('visa_documents', 'public');
        }

        $visaType = VisaType::where('id', $validated['visa_type_id'])
            ->where('country_id', $validated['country_id'])
            ->firstOrFail();

        $visaFee = $validated['visa_fee'] ?? $visaType->default_fee ?? 0;
        $agentId = $validated['agent_id'] ?? null;
        $agentCommissionAmount = 0;

        if ($agentId) {
            $agent = DB::table('local_agents')
                ->where('id', $agentId)
                ->where('agency_id', app('currentAgency')->id)
                ->first();

            if ($agent && $visaFee > 0) {
                $commissionValue = (float) $agent->commission_value;
                if ($commissionValue > 0) {
                    if ($agent->commission_type === 'percentage') {
                        $agentCommissionAmount = round($visaFee * ($commissionValue / 100), 2);
                    } else {
                        $agentCommissionAmount = $commissionValue;
                    }
                }
            }
        }

        $visa->update([
            'passport_id' => $passport->id,
            'country_id' => $validated['country_id'] ?? null,
            'visa_type' => $visaType->name,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'],
            'visa_fee' => $visaFee,
            'visa_type_id' => $visaType->id,
            'agent_id' => $agentId,
            'agent_commission' => $agentCommissionAmount,
            'document_path' => $path,
        ]);

        if ($request->hasFile('type_documents')) {
            $allowedIds = VisaTypeDocument::where('visa_type_id', $visaType->id)->pluck('id')->all();
            foreach ($request->file('type_documents') as $docId => $file) {
                if (!$file) {
                    continue;
                }
                if (!in_array((int) $docId, $allowedIds, true)) {
                    continue;
                }
                $storedPath = $file->store('visa_documents', 'public');
                $visa->documents()->create([
                    'visa_type_document_id' => (int) $docId,
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('visas.index')->with('success', 'Visa record updated successfully.');
    }

    public function destroy(Visa $visa)
    {
        $this->authorizeVisa($visa);

        if ($visa->document_path) {
            Storage::disk('public')->delete($visa->document_path);
        }

        $visa->delete();

        return redirect()->route('visas.index')->with('success', 'Visa record deleted successfully.');
    }

    public function report(Request $request)
    {
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'agent_id' => ['nullable', 'integer', 'exists:local_agents,id'],
            'visa_type' => ['nullable', 'string', 'max:100'],
        ]);

        $countries = DB::table('countries')->orderBy('name')->get();

        $agents = DB::table('local_agents')
            ->where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $query = Visa::with('passport')
            ->whereHas('passport', function ($query) {
                $query->where('agency_id', app('currentAgency')->id);
            });

        if (!empty($filters['from_date'])) {
            $query->whereDate('issue_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('issue_date', '<=', $filters['to_date']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (!empty($filters['agent_id'])) {
            $query->where('agent_id', $filters['agent_id']);
        }

        if (!empty($filters['visa_type'])) {
            $query->where('visa_type', $filters['visa_type']);
        }

        $visas = $query
            ->orderBy('issue_date', 'desc')
            ->get();

        $countryNames = $countries->pluck('name', 'id');
        $agentNames = $agents->pluck('name', 'id');

        $totalVisaFee = $visas->sum('visa_fee');
        $totalAgentCommission = $visas->sum('agent_commission');

        return view('visas.report', [
            'visas' => $visas,
            'countries' => $countries,
            'agents' => $agents,
            'countryNames' => $countryNames,
            'agentNames' => $agentNames,
            'filters' => $filters,
            'totalVisaFee' => $totalVisaFee,
            'totalAgentCommission' => $totalAgentCommission,
        ]);
    }

    public function invoice(Visa $visa)
    {
        $this->authorizeVisa($visa);

        $visa->load('passport');

        if (!$visa->invoice_no && $visa->passport && $visa->passport->agency_id) {
            $visa->invoice_no = $this->generateInvoiceNumber($visa->passport->agency_id);
            $visa->invoice_date = now()->toDateString();
            $visa->save();
        }

        return view('visas.invoice', compact('visa'));
    }

    protected function generateInvoiceNumber(int $agencyId): string
    {
        $today = now()->format('Ymd');
        $prefix = 'VISA-' . $today . '-';

        $lastInvoice = DB::table('visas')
            ->join('passports', 'visas.passport_id', '=', 'passports.id')
            ->where('passports.agency_id', $agencyId)
            ->where('visas.invoice_no', 'like', $prefix . '%')
            ->orderBy('visas.invoice_no', 'desc')
            ->value('visas.invoice_no');

        $nextNumber = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice);
            $lastSeq = (int) end($parts);
            if ($lastSeq > 0) {
                $nextNumber = $lastSeq + 1;
            }
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function authorizeVisa(Visa $visa): void
    {
        $passport = $visa->passport;

        if (!$passport || $passport->agency_id !== app('currentAgency')->id) {
            abort(404);
        }
    }
}
