@extends('layouts.app')

@section('content')
@php
    $invoiceUrl = request()->getSchemeAndHttpHost() . route('tickets.invoice', $ticket, false);
@endphp
<h1 class="h3 mb-3">Air Ticket Invoice / Money Receipt</h1>
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="mb-2">
                    <div class="small text-muted">Invoice Number</div>
                    <div class="fw-semibold">{{ $ticket->invoice_no }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Sales Date</div>
                    <div class="fw-semibold">{{ optional($ticket->sales_date)->format('Y-m-d') }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Client</div>
                    <div class="fw-semibold">{{ optional($ticket->client)->name ?? '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Passenger Name</div>
                    <div class="fw-semibold">{{ $ticket->passenger_name }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Ticket Number</div>
                    <div class="fw-semibold">{{ $ticket->ticket_no }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Airline</div>
                    <div class="fw-semibold">{{ optional($ticket->airline)->name ?? '' }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Client Price</div>
                    <div class="fw-semibold">{{ number_format($ticket->client_price, 2) }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Purchase Price</div>
                    <div class="fw-semibold">{{ number_format($ticket->purchase_price, 2) }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Profit</div>
                    <div class="fw-semibold">{{ number_format($ticket->profit, 2) }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Agent Commission</div>
                    <div class="fw-semibold">{{ number_format($ticket->agent_commission_amount, 2) }}</div>
                </div>
                <div class="mt-4">
                    <h2 class="h5 mb-3">Money Receipt</h2>
                    <p class="mb-1">
                        Received with thanks from <strong>{{ $ticket->passenger_name }}</strong>
                        the sum of <strong>{{ number_format($ticket->client_price, 2) }}</strong>
                        against air ticket number <strong>{{ $ticket->ticket_no }}</strong>.
                    </p>
                    <p class="mb-0 small text-muted">
                        This receipt is generated from the system and does not require a physical signature.
                    </p>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <div id="qrcode" class="border rounded d-flex align-items-center justify-content-center mb-2" style="width: 100%; min-height: 240px;"></div>
                <div class="small text-muted text-center">Scan barcode to open this invoice</div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.print()">Print</button>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary">Back to Tickets</a>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    (function(){
        var url = "{{ $invoiceUrl }}";
        var el = document.getElementById('qrcode');
        new QRCode(el, {
            text: url,
            width: 220,
            height: 220,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.M
        });
    })();
</script>
@endsection

