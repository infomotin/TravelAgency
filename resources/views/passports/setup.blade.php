@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Passport Setup</h1>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Countries</div>
            <div class="card-body">
                <form action="{{ route('passports.setup.countries.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-7">
                        <input type="text" name="name" class="form-control" placeholder="Country name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="iso_code" class="form-control" placeholder="ISO" maxlength="3">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>ISO</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($countries as $country)
                            <tr>
                                <td>{{ $country->name }}</td>
                                <td>{{ $country->iso_code }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted text-center">No countries added.</td>
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
            <div class="card-header">Currencies</div>
            <div class="card-body">
                <form action="{{ route('passports.setup.currencies.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-3">
                        <input type="text" name="code" class="form-control" placeholder="Code" maxlength="3" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control" placeholder="Currency name" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="symbol" class="form-control" placeholder="Symbol">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Symbol</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($currencies as $currency)
                            <tr>
                                <td>{{ $currency->code }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->symbol }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">No currencies added.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Airports</div>
            <div class="card-body">
                <form action="{{ route('passports.setup.airports.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Airport name" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="iata_code" class="form-control" placeholder="IATA" maxlength="3">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="city" class="form-control" placeholder="City">
                    </div>
                    <div class="col-md-3">
                        <select name="country_id" class="form-select">
                            <option value="">Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid mt-2">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>IATA</th>
                            <th>City</th>
                            <th>Country</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($airports as $airport)
                            <tr>
                                <td>{{ $airport->name }}</td>
                                <td>{{ $airport->iata_code }}</td>
                                <td>{{ $airport->city }}</td>
                                <td>
                                    @php
                                        $country = $countries->firstWhere('id', $airport->country_id);
                                    @endphp
                                    {{ $country->name ?? '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center">No airports added.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 mb-3">
            <div class="card-header">Ticket Agencies</div>
            <div class="card-body">
                <form action="{{ route('passports.setup.ticket_agencies.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Agency name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="contact_person" class="form-control" placeholder="Contact person">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="col-md-2">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-12 col-md-2 d-grid mt-2">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ticketAgencies as $agency)
                            <tr>
                                <td>{{ $agency->name }}</td>
                                <td>{{ $agency->contact_person }}</td>
                                <td>{{ $agency->phone }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">No ticket agencies added.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card h-100 mb-3">
            <div class="card-header">Airlines</div>
            <div class="card-body">
                <p class="text-muted small mb-2">Airlines are managed centrally for tickets and passports.</p>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>IATA</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($airlines as $airline)
                            <tr>
                                <td>{{ $airline->name }}</td>
                                <td>{{ $airline->iata_code }}</td>
                                <td>{{ ucfirst($airline->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">No airlines configured.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card h-100">
            <div class="card-header">Local Agents</div>
            <div class="card-body">
                <form action="{{ route('passports.setup.local_agents.store') }}" method="post" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Agent name" required>
                    </div>
                    <div class="col-md-3">
                        <select name="commission_type" class="form-select">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="commission_value" class="form-control" placeholder="Value" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
                <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($localAgents as $agent)
                            <tr>
                                <td>{{ $agent->name }}</td>
                                <td>{{ ucfirst($agent->commission_type) }}</td>
                                <td>{{ number_format($agent->commission_value, 2) }}</td>
                                <td>{{ ucfirst($agent->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center">No local agents added.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
