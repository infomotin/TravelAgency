@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Airports</h1>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px;">SL</th>
                    <th>Country Name</th>
                    <th>Airport Name</th>
                    <th>Airport Code</th>
                </tr>
                </thead>
                <tbody>
                @forelse($airports as $index => $airport)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $airport->country_name }}</td>
                        <td>{{ $airport->name }}</td>
                        <td>{{ $airport->iata_code }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No airports found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

