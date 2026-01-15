@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Bill {{ $bill->bill_no }}</h1>
    <div class="d-flex gap-2">
        @if($bill->balance_amount > 0)
        <a href="{{ route('bills.pay', $bill) }}" class="btn btn-success btn-sm">Record Payment</a>
        @endif
        <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger small">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="small text-muted">Bill Date</div>
        <div class="fw-semibold">{{ $bill->bill_date->format('Y-m-d') }}</div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Due Date</div>
        <div class="fw-semibold">{{ optional($bill->due_date)->format('Y-m-d') }}</div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Customer / Party</div>
        <div class="fw-semibold">
            @if($bill->party)
                <a href="{{ route('parties.show', $bill->party) }}" class="text-decoration-none">
                    {{ $bill->party->name }}
                </a>
            @else
                {{ $bill->contact_name }}
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="small text-muted">Status</div>
        <div class="fw-semibold">{{ ucfirst($bill->status) }}</div>
    </div>
    <div class="col-md-4">
        <div class="small text-muted">Total</div>
        <div class="fw-semibold">{{ number_format($bill->total_amount, 2) }}</div>
    </div>
    <div class="col-md-4">
        <div class="small text-muted">Paid</div>
        <div class="fw-semibold text-success">{{ number_format($bill->paid_amount, 2) }}</div>
    </div>
    <div class="col-md-4">
        <div class="small text-muted">Balance</div>
        <div class="fw-semibold text-danger">{{ number_format($bill->balance_amount, 2) }}</div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header small fw-semibold">Lines</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Account</th>
                    <th>Description</th>
                    <th class="text-end" style="width: 80px;">Qty</th>
                    <th class="text-end" style="width: 140px;">Unit Price</th>
                    <th class="text-end" style="width: 160px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bill->lines as $line)
                    <tr>
                        <td>{{ $line->account->code }} - {{ $line->account->name }}</td>
                        <td>{{ $line->description }}</td>
                        <td class="text-end">{{ number_format($line->quantity, 2) }}</td>
                        <td class="text-end">{{ number_format($line->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format($line->amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header small fw-semibold">Attachments</div>
    <div class="card-body">
        @if($bill->attachments->count() > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach($bill->attachments as $attachment)
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-light btn-sm border d-flex align-items-center gap-2">
                        <i class="bi bi-paperclip text-muted"></i>
                        <span>{{ $attachment->file_name }}</span>
                        <span class="text-muted small">({{ round($attachment->file_size / 1024, 1) }} KB)</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-muted small">No attachments found.</div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header small fw-semibold">Payments</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 110px;">Date</th>
                    <th style="width: 130px;">Voucher</th>
                    <th>Description</th>
                    <th class="text-end" style="width: 160px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bill->payments as $payment)
                    <tr>
                        <td>{{ $payment->paid_at->format('Y-m-d') }}</td>
                        <td>{{ $payment->transaction->voucher_no }}</td>
                        <td>{{ $payment->transaction->description }}</td>
                        <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted small">No payments yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

