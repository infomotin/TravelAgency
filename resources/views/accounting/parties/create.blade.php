@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Add Party</h1>
    <a href="{{ route('parties.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('parties.store') }}" method="post" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="customer" @selected(old('type') == 'customer')>Customer</option>
                    <option value="vendor" @selected(old('type') == 'vendor')>Vendor</option>
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Tax Number</label>
                <input type="text" name="tax_number" value="{{ old('tax_number') }}" class="form-control @error('tax_number') is-invalid @enderror">
                @error('tax_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="form-control @error('address_line1') is-invalid @enderror">
                @error('address_line1')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Address Line 2</label>
                <input type="text" name="address_line2" value="{{ old('address_line2') }}" class="form-control @error('address_line2') is-invalid @enderror">
                @error('address_line2')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror">
                @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ old('country') }}" class="form-control @error('country') is-invalid @enderror">
                @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Opening Balance</label>
                <input type="number" step="0.01" name="opening_balance" value="{{ old('opening_balance', 0) }}" class="form-control @error('opening_balance') is-invalid @enderror">
                <div class="form-text">Positive for debit (receivable), negative for credit (payable).</div>
                @error('opening_balance')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Create Party</button>
            </div>
        </form>
    </div>
</div>
@endsection
