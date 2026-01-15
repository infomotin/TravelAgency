@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Transactions</h1>
    @can('transactions.create')
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">New Transaction</a>
    @endcan
</div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 130px;">Voucher No</th>
                    <th style="width: 110px;">Date</th>
                    <th style="width: 110px;">Type</th>
                    <th style="width: 160px;">Party</th>
                    <th>Description</th>
                    <th style="width: 140px;">Reference</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 140px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->voucher_no }}</td>
                        <td>{{ $transaction->date->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>{{ $transaction->party?->name }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ ucfirst($transaction->status) }}</td>
                        <td class="text-end">
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            @can('transactions.update')
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endcan
                            @can('transactions.delete')
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this transaction?')">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
