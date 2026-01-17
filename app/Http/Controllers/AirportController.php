<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AirportController extends Controller
{
    public function index()
    {
        $airports = DB::table('airports')
            ->leftJoin('countries', 'airports.country_id', '=', 'countries.id')
            ->select('airports.*', 'countries.name as country_name')
            ->orderBy('countries.name')
            ->orderBy('airports.name')
            ->get();

        $countries = DB::table('countries')
            ->orderBy('name')
            ->get();

        return view('airports.index', compact('airports', 'countries'));
    }
}
