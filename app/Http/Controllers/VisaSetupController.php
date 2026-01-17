<?php

namespace App\Http\Controllers;

use App\Models\VisaType;
use App\Models\VisaTypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisaSetupController extends Controller
{
    public function index(Request $request)
    {
        $countries = DB::table('countries')->orderBy('name')->get();

        $countryId = (int) $request->query('country_id', 0);
        $selectedCountry = $countryId > 0 ? $countries->firstWhere('id', $countryId) : null;

        $visaTypes = collect();
        $documentsByType = collect();

        if ($selectedCountry) {
            $visaTypes = VisaType::where('country_id', $selectedCountry->id)
                ->orderBy('name')
                ->get();

            $documentsByType = VisaTypeDocument::whereIn('visa_type_id', $visaTypes->pluck('id'))
                ->orderBy('name')
                ->get()
                ->groupBy('visa_type_id');
        }

        return view('visas.setup', [
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'visaTypes' => $visaTypes,
            'documentsByType' => $documentsByType,
        ]);
    }

    public function storeVisaType(Request $request)
    {
        $validated = $request->validate([
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'default_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        VisaType::create([
            'country_id' => $validated['country_id'],
            'name' => $validated['name'],
            'default_fee' => $validated['default_fee'] ?? 0,
            'status' => 'active',
        ]);

        return redirect()
            ->route('visas.setup', ['country_id' => $validated['country_id']])
            ->with('success', 'Visa type added successfully.');
    }

    public function storeVisaTypeDocument(Request $request)
    {
        $validated = $request->validate([
            'visa_type_id' => ['required', 'integer', 'exists:visa_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_required' => ['nullable', 'boolean'],
        ]);

        $visaType = VisaType::findOrFail($validated['visa_type_id']);

        VisaTypeDocument::create([
            'visa_type_id' => $visaType->id,
            'name' => $validated['name'],
            'is_required' => (bool) ($validated['is_required'] ?? true),
        ]);

        return redirect()
            ->route('visas.setup', ['country_id' => $visaType->country_id])
            ->with('success', 'Visa document requirement added successfully.');
    }
}

