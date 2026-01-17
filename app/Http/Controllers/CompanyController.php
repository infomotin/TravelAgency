<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('agency_id', app('currentAgency')->id)
            ->where('type', 'non_invoice')
            ->latest()
            ->paginate(20);

        return view('companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        Company::create([
            'agency_id' => app('currentAgency')->id,
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'type' => 'non_invoice',
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function update(Request $request, Company $company)
    {
        if ($company->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $company->update([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}

