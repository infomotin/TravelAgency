@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Transaction {{ $transaction->voucher_no }}</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="small text-muted">Date</div>
        <div class="fw-semibold">{{ $transaction->date->format('Y-m-d') }}</div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Type</div>
        <div class="fw-semibold">{{ ucfirst($transaction->type) }}</div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Reference</div>
        <div class="fw-semibold">{{ $transaction->reference }}</div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Status</div>
        <div class="fw-semibold">{{ ucfirst($transaction->status) }}</div>
    </div>
    <div class="col-12">
        <div class="small text-muted">Description</div>
        <div>{{ $transaction->description }}</div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Account</th>
                    <th style="width: 160px;" class="text-end">Debit</th>
                    <th style="width: 160px;" class="text-end">Credit</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @foreach($transaction->lines as $line)
                    @php
                        $totalDebit += $line->debit;
                        $totalCredit += $line->credit;
                    @endphp
                    <tr>
                        <td>{{ $line->account->code }} - {{ $line->account->name }}</td>
                        <td class="text-end">{{ number_format($line->debit, 2) }}</td>
                        <td class="text-end">{{ number_format($line->credit, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="table-light">
                <tr>
                    <th class="text-end">Total</th>
                    <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                    <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

