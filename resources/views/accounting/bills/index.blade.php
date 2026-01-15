@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Bills</h1>
    @can('accounts.create')
    <a href="{{ route('bills.create') }}" class="btn btn-primary">New Bill</a>
    @endcan
</div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger small">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0" data-datatable="true">
                <thead class="table-light">
                <tr>
                    <th style="width: 130px;">Bill No</th>
                    <th style="width: 110px;">Date</th>
                    <th>Party</th>
                    <th style="width: 140px;" class="text-end">Total</th>
                    <th style="width: 140px;" class="text-end">Paid</th>
                    <th style="width: 140px;" class="text-end">Balance</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 140px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($bills as $bill)
                    <tr>
                        <td>{{ $bill->bill_no }}</td>
                        <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                        <td>
                            @if($bill->party)
                                <a href="{{ route('parties.show', $bill->party) }}" class="text-decoration-none">
                                    {{ $bill->party->name }}
                                </a>
                            @else
                                <span class="text-muted">{{ $bill->contact_name }}</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($bill->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($bill->paid_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($bill->balance_amount, 2) }}</td>
                        <td>{{ ucfirst($bill->status) }}</td>
                        <td class="text-end">
                            <a href="{{ route('bills.show', $bill) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            @can('accounts.update')
                            <a href="{{ route('bills.edit', $bill) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $bills->links() }}
    </div>
</div>
@endsection
