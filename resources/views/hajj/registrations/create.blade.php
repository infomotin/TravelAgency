@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Hajj Registration</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('hajj.registrations.store') }}" method="post">
    @csrf

    <div class="card mb-3">
        <div class="card-header">Invoice Header</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Select Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">Select client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sales By</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Group Name</label>
                    <input type="text" name="group_name" class="form-control" value="{{ old('group_name') }}">
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
                        <option value="">Select agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(old('agent_id') == $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Hajj Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="pilgrim_name" class="form-control" value="{{ old('pilgrim_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tracking No</label>
                    <input type="text" name="tracking_no" class="form-control" value="{{ old('tracking_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pre Reg. Year</label>
                    <input type="text" name="pre_reg_year" class="form-control" value="{{ old('pre_reg_year') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">NID</label>
                    <input type="text" name="nid" class="form-control" value="{{ old('nid') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Voucher No</label>
                    <input type="text" name="voucher_no" class="form-control" value="{{ old('voucher_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Serial No</label>
                    <input type="text" name="serial_no" class="form-control" value="{{ old('serial_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        @php $genderValue = old('gender'); @endphp
                        <option value="">Select gender</option>
                        <option value="male" @if($genderValue === 'male') selected @endif>Male</option>
                        <option value="female" @if($genderValue === 'female') selected @endif>Female</option>
                        <option value="other" @if($genderValue === 'other') selected @endif>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Maharam</label>
                    <input type="text" name="maharam" class="form-control" value="{{ old('maharam') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Possible Hajj Year</label>
                    <input type="text" name="possible_hajj_year" class="form-control" value="{{ old('possible_hajj_year') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Billing Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select">
                        <option value="">Select product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pax Name</label>
                    <input type="text" name="pax_name" class="form-control" value="{{ old('pax_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', 0) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount', 0) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Service Charge</label>
                    <input type="number" step="0.01" name="service_charge" class="form-control" value="{{ old('service_charge', 0) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">VAT / Tax</label>
                    <input type="number" step="0.01" name="vat_tax" class="form-control" value="{{ old('vat_tax', 0) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Agent Commission</label>
                    <input type="number" step="0.01" name="agent_commission" class="form-control" value="{{ old('agent_commission', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-select">
                        <option value="">Select vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Money Receipt</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Account</label>
                    <select name="account_id" class="form-select">
                        <option value="">Select account</option>
                        @foreach($paymentAccounts as $account)
                            <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="payment_amount" class="form-control" value="{{ old('payment_amount', 0) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="payment_discount" class="form-control" value="{{ old('payment_discount', 0) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Receipt No</label>
                    <input type="text" name="receipt_no" class="form-control" value="{{ old('receipt_no') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Note</label>
                    <input type="text" name="payment_note" class="form-control" value="{{ old('payment_note') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
@endsection

