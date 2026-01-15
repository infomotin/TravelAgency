@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">{{ $party->name }}</h1>
        <span class="badge {{ $party->type === 'customer' ? 'text-bg-info' : 'text-bg-warning' }}">
            {{ ucfirst($party->type) }}
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('parties.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
        @can('parties.update')
        <a href="{{ route('parties.edit', $party) }}" class="btn btn-primary btn-sm">Edit Party</a>
        @endcan
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title text-muted small text-uppercase">Contact Info</h5>
                <p class="mb-1"><strong>Email:</strong> {{ $party->email ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $party->phone ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Tax No:</strong> {{ $party->tax_number ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title text-muted small text-uppercase">Address</h5>
                <p class="mb-0">
                    {{ $party->address_line1 }}<br>
                    @if($party->address_line2) {{ $party->address_line2 }}<br> @endif
                    {{ $party->city }} {{ $party->country }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-primary">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h5 class="card-title text-muted small text-uppercase">Current Balance</h5>
                <h2 class="display-6 fw-bold {{ ($party->opening_balance + $party->bills->sum('balance_amount')) > 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($party->opening_balance + $party->bills->sum('balance_amount'), 2) }}
                </h2>
                <small class="text-muted">Opening: {{ number_format($party->opening_balance, 2) }}</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0 card-title h6">Ledger / Transaction History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Bill No</th>
                        <th>Reference</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($party->bills as $bill)
                    <tr>
                        <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('bills.show', $bill) }}" class="fw-semibold text-decoration-none">
                                {{ $bill->bill_no }}
                            </a>
                        </td>
                        <td>{{ $bill->reference }}</td>
                        <td class="text-end">{{ number_format($bill->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($bill->paid_amount, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($bill->balance_amount, 2) }}</td>
                        <td>
                            @if($bill->status == 'paid')
                                <span class="badge text-bg-success">Paid</span>
                            @elseif($bill->status == 'partial')
                                <span class="badge text-bg-warning">Partial</span>
                            @else
                                <span class="badge text-bg-danger">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('bills.show', $bill) }}" class="btn btn-sm btn-link p-0">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No transactions found for this party.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
