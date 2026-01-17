@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Visa Setup</h1>
<form method="get" action="{{ route('visas.setup') }}" class="card mb-3">
    <div class="card-body row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Country</label>
            <select name="country_id" class="form-select" required>
                <option value="">Select country</option>
                @php
                    $countryId = optional($selectedCountry)->id ?? request('country_id');
                @endphp
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" @if($countryId == $country->id) selected @endif>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Load</button>
        </div>
    </div>
</form>
@if($selectedCountry)
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Visa Types for {{ $selectedCountry->name }}</div>
            <div class="card-body">
                <form action="{{ route('visas.setup.types.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <input type="hidden" name="country_id" value="{{ $selectedCountry->id }}">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Visa type name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" name="default_fee" class="form-control" placeholder="Default fee">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Default Fee</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($visaTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>{{ number_format($type->default_fee, 2) }}</td>
                                <td>{{ ucfirst($type->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">No visa types added for this country.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Document Requirements</div>
            <div class="card-body">
                <form action="{{ route('visas.setup.documents.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-5">
                        <select name="visa_type_id" class="form-select" required>
                            <option value="">Select visa type</option>
                            @foreach($visaTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control" placeholder="Document name" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Visa Type</th>
                            <th>Document</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($visaTypes as $type)
                            @php
                                $docs = $documentsByType->get($type->id) ?? collect();
                            @endphp
                            @if($docs->isEmpty())
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td class="text-muted">No documents defined.</td>
                                </tr>
                            @else
                                @foreach($docs as $doc)
                                    <tr>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $doc->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted text-center">No visa types for this country.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

