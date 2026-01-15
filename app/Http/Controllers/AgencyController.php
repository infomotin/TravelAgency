<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:agencies.view')->only(['index', 'show']);
        $this->middleware('permission:agencies.create')->only(['create', 'store']);
        $this->middleware('permission:agencies.update')->only(['edit', 'update']);
        $this->middleware('permission:agencies.delete')->only(['destroy']);
    }

    public function index()
    {
        $agencies = Agency::orderBy('name')->paginate(20);
        return view('agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('agencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:agencies,slug'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $agency = Agency::create($validated);
        return redirect()->route('agencies.show', $agency);
    }

    public function show(Agency $agency)
    {
        return view('agencies.show', compact('agency'));
    }

    public function edit(Agency $agency)
    {
        return view('agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:agencies,slug,' . $agency->id],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);
        $agency->update($validated);
        return redirect()->route('agencies.show', $agency);
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('agencies.index');
    }
}
