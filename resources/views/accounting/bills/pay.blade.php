@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Record Payment for {{ $bill->bill_no }}</h1>
    <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline-secondary btn-sm">Back to bill</a>
</div>

<div class="mb-3">
    <div class="small text-muted">Balance</div>
    <div class="fw-semibold text-danger">{{ number_format($bill->balance_amount, 2) }}</div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('bills.pay.store', $bill) }}" method="post" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Payment Date</label>
                <input type="date" name="paid_at" value="{{ old('paid_at', date('Y-m-d')) }}" class="form-control form-control-sm @error('paid_at') is-invalid @enderror">
                @error('paid_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Amount</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $bill->balance_amount) }}" class="form-control form-control-sm @error('amount') is-invalid @enderror">
                @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Deposit To (Cash / Bank)</label>
                <select name="account_id" class="form-select form-select-sm @error('account_id') is-invalid @enderror">
                    <option value="">Select account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <input type="text" name="description" value="{{ old('description') }}" class="form-control form-control-sm @error('description') is-invalid @enderror">
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-success btn-sm">Save Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection

