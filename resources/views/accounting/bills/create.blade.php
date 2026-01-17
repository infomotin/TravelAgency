@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">New Other Invoice</h1>
    <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<form action="{{ route('bills.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="card mb-3">
        <div class="card-header">Invoice Header</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Select Client</label>
                    <div class="input-group input-group-sm">
                        <select name="party_id" class="form-select @error('party_id') is-invalid @enderror" required>
                            <option value="">Select client</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->id }}" @selected(old('party_id') == $party->id)>{{ $party->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('parties.create') }}" class="btn btn-outline-secondary" target="_blank">+</a>
                    </div>
                    @error('party_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales By</label>
                    <select name="employee_id" class="form-select form-select-sm @error('employee_id') is-invalid @enderror">
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice No</label>
                    <input type="text" class="form-control form-control-sm" value="Auto" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales Date</label>
                    <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" class="form-control form-control-sm @error('bill_date') is-invalid @enderror">
                    @error('bill_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control form-control-sm @error('due_date') is-invalid @enderror">
                    @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" class="form-control form-control-sm @error('reference') is-invalid @enderror">
                    @error('reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Passport Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Passport No</label>
                    <select name="passport_id" class="form-select form-select-sm @error('passport_id') is-invalid @enderror">
                        <option value="">Select passport</option>
                        @foreach($passports as $passport)
                            <option value="{{ $passport->id }}" @selected(old('passport_id') == $passport->id)>
                                {{ $passport->passport_no }} - {{ $passport->holder_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('passport_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pax Name</label>
                    <input type="text" name="billing_pax_name" value="{{ old('billing_pax_name') }}" class="form-control form-control-sm @error('billing_pax_name') is-invalid @enderror">
                    @error('billing_pax_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pax Type</label>
                    <input type="text" name="pax_type" value="{{ old('pax_type') }}" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ticket Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Ticket No</label>
                    <input type="text" name="ticket_no" value="{{ old('ticket_no') }}" class="form-control form-control-sm @error('ticket_no') is-invalid @enderror">
                    @error('ticket_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">PNR</label>
                    <input type="text" name="pnr" value="{{ old('pnr') }}" class="form-control form-control-sm @error('pnr') is-invalid @enderror">
                    @error('pnr')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Route</label>
                    <input type="text" name="route_text" value="{{ old('route_text') }}" class="form-control form-control-sm @error('route_text') is-invalid @enderror">
                    @error('route_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Reference No</label>
                    <input type="text" name="ticket_reference" value="{{ old('ticket_reference') }}" class="form-control form-control-sm @error('ticket_reference') is-invalid @enderror">
                    @error('ticket_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Journey Date</label>
                    <input type="date" name="journey_date" value="{{ old('journey_date') }}" class="form-control form-control-sm @error('journey_date') is-invalid @enderror">
                    @error('journey_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Return Date</label>
                    <input type="date" name="return_date" value="{{ old('return_date') }}" class="form-control form-control-sm @error('return_date') is-invalid @enderror">
                    @error('return_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Airline</label>
                    <select name="airline_id" class="form-select form-select-sm @error('airline_id') is-invalid @enderror">
                        <option value="">Select airline</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}" @selected(old('airline_id') == $airline->id)>{{ $airline->name }}</option>
                        @endforeach
                    </select>
                    @error('airline_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Hotel Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel Name</label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name') }}" class="form-control form-control-sm @error('hotel_name') is-invalid @enderror">
                    @error('hotel_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference No</label>
                    <input type="text" name="hotel_reference" value="{{ old('hotel_reference') }}" class="form-control form-control-sm @error('hotel_reference') is-invalid @enderror">
                    @error('hotel_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Check In Date</label>
                    <input type="date" name="hotel_check_in" value="{{ old('hotel_check_in') }}" class="form-control form-control-sm @error('hotel_check_in') is-invalid @enderror">
                    @error('hotel_check_in')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Check Out Date</label>
                    <input type="date" name="hotel_check_out" value="{{ old('hotel_check_out') }}" class="form-control form-control-sm @error('hotel_check_out') is-invalid @enderror">
                    @error('hotel_check_out')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Type</label>
                    <input type="text" name="room_type" value="{{ old('room_type') }}" class="form-control form-control-sm @error('room_type') is-invalid @enderror">
                    @error('room_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Transport Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Transport Type</label>
                    <select name="transport_type_id" class="form-select form-select-sm @error('transport_type_id') is-invalid @enderror">
                        <option value="">Select type</option>
                        @foreach($transportTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('transport_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('transport_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales By</label>
                    <input type="text" name="transport_sales_by" value="{{ old('transport_sales_by') }}" class="form-control form-control-sm @error('transport_sales_by') is-invalid @enderror">
                    @error('transport_sales_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference No</label>
                    <input type="text" name="transport_reference" value="{{ old('transport_reference') }}" class="form-control form-control-sm @error('transport_reference') is-invalid @enderror">
                    @error('transport_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pickup Place</label>
                    <input type="text" name="pickup_place" value="{{ old('pickup_place') }}" class="form-control form-control-sm @error('pickup_place') is-invalid @enderror">
                    @error('pickup_place')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Pickup Time</label>
                    <input type="time" name="pickup_time" value="{{ old('pickup_time') }}" class="form-control form-control-sm @error('pickup_time') is-invalid @enderror">
                    @error('pickup_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Drop Off Place</label>
                    <input type="text" name="dropoff_place" value="{{ old('dropoff_place') }}" class="form-control form-control-sm @error('dropoff_place') is-invalid @enderror">
                    @error('dropoff_place')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Drop Off Time</label>
                    <input type="time" name="dropoff_time" value="{{ old('dropoff_time') }}" class="form-control form-control-sm @error('dropoff_time') is-invalid @enderror">
                    @error('dropoff_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Billing Information</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="billing_description" value="{{ old('billing_description') }}" class="form-control form-control-sm @error('billing_description') is-invalid @enderror">
                @error('billing_description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @include('accounting.bills.form-lines')
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Attachments</div>
        <div class="card-body">
            <div class="mb-3">
                <input type="file" name="attachments[]" class="form-control form-control-sm @error('attachments') is-invalid @enderror" multiple>
                @error('attachments')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @foreach($errors->get('attachments.*') as $error)
                    <div class="invalid-feedback d-block">{{ $error[0] }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Money Receipt</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <input type="text" name="payment_method" value="{{ old('payment_method') }}" class="form-control form-control-sm @error('payment_method') is-invalid @enderror">
                    @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Account</label>
                    <select name="payment_account_id" class="form-select form-select-sm @error('payment_account_id') is-invalid @enderror">
                        <option value="">Select account</option>
                        @foreach($paymentAccounts as $account)
                            <option value="{{ $account->id }}" @selected(old('payment_account_id') == $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                        @endforeach
                    </select>
                    @error('payment_account_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="payment_amount" value="{{ old('payment_amount') }}" class="form-control form-control-sm @error('payment_amount') is-invalid @enderror">
                    @error('payment_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="payment_discount" value="{{ old('payment_discount') }}" class="form-control form-control-sm @error('payment_discount') is-invalid @enderror">
                    @error('payment_discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="form-control form-control-sm @error('payment_date') is-invalid @enderror">
                    @error('payment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-9">
                    <label class="form-label">Note</label>
                    <input type="text" name="payment_note" value="{{ old('payment_note') }}" class="form-control form-control-sm @error('payment_note') is-invalid @enderror">
                    @error('payment_note')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-3">
        <button class="btn btn-primary btn-sm">Save Invoice</button>
    </div>
</form>

@endsection
