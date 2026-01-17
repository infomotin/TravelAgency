@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Air Ticket Invoices</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">New Air Ticket Invoice</a>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Invoice No</th>
            <th>Ticket No</th>
            <th>Client</th>
            <th>Passenger</th>
            <th>Airline</th>
            <th>Sales Date</th>
            <th>Client Price</th>
            <th>Purchase Price</th>
            <th>Profit</th>
            <th class="text-end">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tickets as $ticket)
            <tr>
                <td>{{ $ticket->invoice_no }}</td>
                <td>{{ $ticket->ticket_no }}</td>
                <td>{{ optional($ticket->client)->name ?? '-' }}</td>
                <td>{{ $ticket->passenger_name }}</td>
                <td>{{ optional($ticket->airline)->name ?? '' }}</td>
                <td>{{ optional($ticket->sales_date)->format('Y-m-d') }}</td>
                <td>{{ number_format($ticket->client_price, 2) }}</td>
                <td>{{ number_format($ticket->purchase_price, 2) }}</td>
                <td>{{ number_format($ticket->profit, 2) }}</td>
                <td class="text-end">
                    <a href="{{ route('tickets.invoice', $ticket) }}" class="btn btn-sm btn-success">Invoice</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No air ticket invoices found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $tickets->links() }}
@endsection
