<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'address' => ['nullable', 'string', 'max:255'],
            'profile_email' => ['nullable', 'email', 'max:255'],
            'profile_mobile' => ['nullable', 'string', 'max:50'],
            'profile_full_name' => ['nullable', 'string', 'max:255'],
            'profile_address2' => ['nullable', 'string', 'max:255'],
            'profile_extra_info' => ['nullable', 'string'],
            'profile_facebook' => ['nullable', 'string', 'max:255'],
            'profile_website' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'currency' => $validated['currency'],
            'status' => $validated['status'],
            'address' => $validated['address'] ?? $agency->address,
        ];

        if ($request->hasFile('logo')) {
            if ($agency->logo_path) {
                Storage::disk('public')->delete($agency->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('agency-logos', 'public');
        }

        $settings = $agency->settings ?? [];
        $settings['profile_email'] = $validated['profile_email'] ?? ($settings['profile_email'] ?? null);
        $settings['profile_mobile'] = $validated['profile_mobile'] ?? ($settings['profile_mobile'] ?? null);
        $settings['profile_full_name'] = $validated['profile_full_name'] ?? ($settings['profile_full_name'] ?? null);
        $settings['profile_address2'] = $validated['profile_address2'] ?? ($settings['profile_address2'] ?? null);
        $settings['profile_extra_info'] = $validated['profile_extra_info'] ?? ($settings['profile_extra_info'] ?? null);
        $settings['profile_facebook'] = $validated['profile_facebook'] ?? ($settings['profile_facebook'] ?? null);
        $settings['profile_website'] = $validated['profile_website'] ?? ($settings['profile_website'] ?? null);

        $data['settings'] = $settings;

        $agency->update($data);

        return redirect()->route('agencies.edit', $agency)->with('success', 'Profile updated successfully.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('agencies.index');
    }
}
