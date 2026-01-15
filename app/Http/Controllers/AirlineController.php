<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::orderBy('name')->paginate(20);
        return view('airlines.index', compact('airlines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'size:3', 'unique:airlines,iata_code'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $airline = Airline::create($validated);
        return redirect()->route('airlines.index');
    }
}

