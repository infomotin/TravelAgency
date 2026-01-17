<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $agencyId = app('currentAgency')->id;

        $products = Product::where('agency_id', $agencyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        $validated['status'] = 'active';

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Service product created.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Service product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Service product deleted.');
    }
}
