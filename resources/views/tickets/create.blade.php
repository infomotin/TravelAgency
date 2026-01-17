@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Invoice (Air Ticket)</h1>
<form action="{{ route('tickets.store') }}" method="post">
    @csrf
    <div class="card mb-3">
        <div class="card-header">Invoice</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales By</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice No</label>
                    <input type="text" name="invoice_no" class="form-control" value="{{ old('invoice_no') }}" placeholder="Auto or manual">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales Date</label>
                    <input type="date" name="sales_date" class="form-control" value="{{ old('sales_date', now()->toDateString()) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Select Agent</label>
                    <select name="agent_id" class="form-select">
                        <option value="">Select Agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ticket Details</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Ticket No</label>
                    <input type="text" name="ticket_no" class="form-control" value="{{ old('ticket_no') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gross Fare (Sale)</label>
                    <input type="number" step="0.01" name="fare" class="form-control" value="{{ old('fare') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Base Fare (Buy)</label>
                    <input type="number" step="0.01" name="base_fare" class="form-control" value="{{ old('base_fare') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-select">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Commission %</label>
                    <input type="number" step="0.01" name="commission_percent" class="form-control" value="{{ old('commission_percent') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Taxes Commission</label>
                    <input type="number" step="0.01" name="taxes_commission" class="form-control" value="{{ old('taxes_commission') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">AIT</label>
                    <input type="number" step="0.01" name="ait" class="form-control" value="{{ old('ait') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Airline</label>
                    <select name="airline_id" class="form-select" required>
                        <option value="">Select Airline</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}">{{ $airline->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Route/Sector From</label>
                    <select name="from_airport_id" class="form-select">
                        <option value="">From</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Route/Sector To</label>
                    <select name="to_airport_id" class="form-select">
                        <option value="">To</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">PNR</label>
                    <input type="text" name="pnr" class="form-control" value="{{ old('pnr') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">GDS</label>
                    <input type="text" name="gds" class="form-control" value="{{ old('gds') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Extra Fee</label>
                    <input type="number" step="0.01" name="extra_fee" class="form-control" value="{{ old('extra_fee') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Class</label>
                    <input type="text" name="class" class="form-control" value="{{ old('class') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ticket Type</label>
                    <input type="text" name="ticket_type" class="form-control" value="{{ old('ticket_type') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Segment</label>
                    <input type="number" name="segment" class="form-control" value="{{ old('segment', 1) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Journey Date</label>
                    <input type="date" name="journey_date" class="form-control" value="{{ old('journey_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Return Date</label>
                    <input type="date" name="return_date" class="form-control" value="{{ old('return_date') }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tax Amount</label>
                    <input type="number" step="0.01" name="tax_amount" class="form-control" value="{{ old('tax_amount') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">7% Commission</label>
                    <input type="number" step="0.01" name="commission_7_percent" class="form-control" value="{{ old('commission_7_percent') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Client Price</label>
                    <input type="number" step="0.01" name="client_price" class="form-control" value="{{ old('client_price') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Purchase Price</label>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax BD</label>
                    <input type="number" step="0.01" name="country_tax_bd" class="form-control" value="{{ old('country_tax_bd') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax UT</label>
                    <input type="number" step="0.01" name="country_tax_ut" class="form-control" value="{{ old('country_tax_ut') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax E5</label>
                    <input type="number" step="0.01" name="country_tax_e5" class="form-control" value="{{ old('country_tax_e5') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax ES</label>
                    <input type="number" step="0.01" name="country_tax_es" class="form-control" value="{{ old('country_tax_es') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax XT</label>
                    <input type="number" step="0.01" name="country_tax_xt" class="form-control" value="{{ old('country_tax_xt') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax OW</label>
                    <input type="number" step="0.01" name="country_tax_ow" class="form-control" value="{{ old('country_tax_ow') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax QA</label>
                    <input type="number" step="0.01" name="country_tax_qa" class="form-control" value="{{ old('country_tax_qa') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax PZ</label>
                    <input type="number" step="0.01" name="country_tax_pz" class="form-control" value="{{ old('country_tax_pz') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax G4</label>
                    <input type="number" step="0.01" name="country_tax_g4" class="form-control" value="{{ old('country_tax_g4') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax P7</label>
                    <input type="number" step="0.01" name="country_tax_p7" class="form-control" value="{{ old('country_tax_p7') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax P8</label>
                    <input type="number" step="0.01" name="country_tax_p8" class="form-control" value="{{ old('country_tax_p8') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country Tax R9</label>
                    <input type="number" step="0.01" name="country_tax_r9" class="form-control" value="{{ old('country_tax_r9') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Pax & Passport Details</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Passport No</label>
                    <select name="passport_id" class="form-select">
                        <option value="">Select Passport</option>
                        @foreach($passports as $passport)
                            <option value="{{ $passport->id }}">{{ $passport->passport_no }} - {{ $passport->holder_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="passenger_name" class="form-control" value="{{ old('passenger_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pax Type</label>
                    <input type="text" name="pax_type" class="form-control" value="{{ old('pax_type') }}" placeholder="ADT/CHD/INF">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact No</label>
                    <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of issue</label>
                    <input type="date" name="passport_issue_date" class="form-control" value="{{ old('passport_issue_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of expire</label>
                    <input type="date" name="passport_expire_date" class="form-control" value="{{ old('passport_expire_date') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Flight Details (Optional)</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">From</label>
                    <input type="text" name="flight_from" class="form-control" value="{{ old('flight_from') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To</label>
                    <input type="text" name="flight_to" class="form-control" value="{{ old('flight_to') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Airline</label>
                    <input type="text" name="flight_airline" class="form-control" value="{{ old('flight_airline') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Flight No</label>
                    <input type="text" name="flight_no" class="form-control" value="{{ old('flight_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fly Date</label>
                    <input type="date" name="flight_date" class="form-control" value="{{ old('flight_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Departure Time</label>
                    <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-primary">Save Invoice</button>
    </div>
</form>
@endsection

