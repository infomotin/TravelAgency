@extends('layouts.app')

@section('content')
@php
    $invoiceUrl = request()->getSchemeAndHttpHost() . route('passports.invoice', $passport, false);
@endphp
<h1 class="h3 mb-3">Passport Invoice / Money Receipt</h1>
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="mb-2">
                    <div class="small text-muted">Invoice Number</div>
                    <div class="fw-semibold">{{ $passport->invoice_no }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Invoice Date</div>
                    <div class="fw-semibold">{{ optional($passport->invoice_date)->format('Y-m-d') }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Holder Name</div>
                    <div class="fw-semibold">{{ $passport->holder_name }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Passport Number</div>
                    <div class="fw-semibold">{{ $passport->passport_no }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Purpose</div>
                    <div class="fw-semibold">
                        @if($passport->purpose === 'visa')
                            Visa
                        @elseif($passport->purpose === 'ticket')
                            Ticket
                        @elseif($passport->purpose === 'both')
                            Visa + Ticket
                        @elseif($passport->purpose === 'other')
                            Other
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Entry Charge</div>
                    <div class="fw-semibold">{{ number_format($passport->entry_charge, 2) }}</div>
                </div>
                <div class="mt-4">
                    <h2 class="h5 mb-3">Money Receipt</h2>
                    <p class="mb-1">
                        Received with thanks from <strong>{{ $passport->holder_name }}</strong>
                        the sum of <strong>{{ number_format($passport->entry_charge, 2) }}</strong>
                        against passport number <strong>{{ $passport->passport_no }}</strong>.
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
            <a href="{{ route('passports.show', $passport) }}" class="btn btn-outline-primary">Back to Passport</a>
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
