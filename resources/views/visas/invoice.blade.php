@extends('layouts.app')

@section('content')
@php
    $invoiceUrl = request()->getSchemeAndHttpHost() . route('visas.invoice', $visa, false);
@endphp
<h1 class="h3 mb-3">Visa Invoice / Money Receipt</h1>
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="mb-2">
                    <div class="small text-muted">Invoice Number</div>
                    <div class="fw-semibold">{{ $visa->invoice_no }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Invoice Date</div>
                    <div class="fw-semibold">{{ optional($visa->invoice_date)->format('Y-m-d') }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Holder Name</div>
                    <div class="fw-semibold">{{ $visa->passport->holder_name ?? '' }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Passport Number</div>
                    <div class="fw-semibold">{{ $visa->passport->passport_no ?? '' }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Visa Type</div>
                    <div class="fw-semibold">{{ $visa->visa_type }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Visa Fee</div>
                    <div class="fw-semibold">{{ number_format($visa->visa_fee, 2) }}</div>
                </div>
                <div class="mb-2">
                    <div class="small text-muted">Agent Commission</div>
                    <div class="fw-semibold">{{ number_format($visa->agent_commission, 2) }}</div>
                </div>
                <div class="mt-4">
                    <h2 class="h5 mb-3">Money Receipt</h2>
                    <p class="mb-1">
                        Received with thanks from <strong>{{ $visa->passport->holder_name ?? '' }}</strong>
                        the sum of <strong>{{ number_format($visa->visa_fee, 2) }}</strong>
                        against visa application for <strong>{{ $visa->visa_type }}</strong>.
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
            @if($visa->passport)
                <a href="{{ route('passports.show', $visa->passport) }}" class="btn btn-outline-primary">Back to Passport</a>
            @else
                <a href="{{ route('visas.index') }}" class="btn btn-outline-primary">Back to Visas</a>
            @endif
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

