<?php

namespace App\Http\Controllers;

use App\Models\ClientCategory;
use Illuminate\Http\Request;

class ClientCategoryController extends Controller
{
    public function index()
    {
        $agencyId = app('currentAgency')->id;
        $categories = ClientCategory::where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        return view('client_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefix' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        $validated['status'] = 'active';

        ClientCategory::create($validated);

        return redirect()->route('client_categories.index')->with('success', 'Client category created.');
    }

    public function update(Request $request, ClientCategory $client_category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefix' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $client_category->update($validated);

        return redirect()->route('client_categories.index')->with('success', 'Client category updated.');
    }

    public function destroy(ClientCategory $client_category)
    {
        $client_category->delete();

        return redirect()->route('client_categories.index')->with('success', 'Client category deleted.');
    }
}
