@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Ledger</h1>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <label class="form-label">Account</label>
        <select name="account_id" class="form-select form-select-sm">
            <option value="">Select account</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" @selected(request('account_id') == $account->id)>
                    {{ $account->code }} - {{ $account->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary btn-sm w-100">Filter</button>
    </div>
</form>

@if($selectedAccount)
    <div class="mb-3">
        <div class="small text-muted">Account</div>
        <div class="fw-semibold">{{ $selectedAccount->code }} - {{ $selectedAccount->name }}</div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 110px;">Date</th>
                        <th style="width: 130px;">Voucher No</th>
                        <th>Description</th>
                        <th style="width: 140px;" class="text-end">Debit</th>
                        <th style="width: 140px;" class="text-end">Credit</th>
                        <th style="width: 140px;" class="text-end">Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $balance = 0;
                    @endphp
                    @foreach($transactions as $line)
                        @php
                            $balance += $line->debit - $line->credit;
                        @endphp
                        <tr>
                            <td>{{ $line->transaction->date->format('Y-m-d') }}</td>
                            <td>{{ $line->transaction->voucher_no }}</td>
                            <td>{{ $line->transaction->description }}</td>
                            <td class="text-end">{{ number_format($line->debit, 2) }}</td>
                            <td class="text-end">{{ number_format($line->credit, 2) }}</td>
                            <td class="text-end">{{ number_format($balance, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection

