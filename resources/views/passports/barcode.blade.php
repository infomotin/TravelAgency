@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Passport Barcode</h1>
<div class="card">
    <div class="card-body">
        @php
            $passportUrl = request()->getSchemeAndHttpHost() . route('passports.show', $passport, false);
        @endphp
        <div class="row g-3">
            <div class="col-md-6">
                <div id="qrcode" class="border rounded d-flex align-items-center justify-content-center" style="width: 100%; min-height: 320px;"></div>
                <div class="small text-muted mt-2">Scan QR to open: {{ $passportUrl }}</div>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Holder</dt>
                    <dd class="col-sm-8">{{ $passport->holder_name }}</dd>
                    <dt class="col-sm-4">Passport No</dt>
                    <dd class="col-sm-8">{{ $passport->passport_no }}</dd>
                    <dt class="col-sm-4">Expiry</dt>
                    <dd class="col-sm-8">{{ optional($passport->expiry_date)->format('Y-m-d') }}</dd>
                </dl>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-outline-secondary" onclick="window.print()">Print</button>
            <a class="btn btn-primary" href="{{ route('passports.show', $passport) }}">View Passport</a>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-B0gHFnGqN1zB4mPexNnH4dWEuTQix4Yx1XSeFZ8bH6mNbFbhH1P1B5vYd9I6lFozx2SxOmqC9IkbmdvzQJs63g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    (function(){
        var url = "{{ $passportUrl }}";
        var el = document.getElementById('qrcode');
        new QRCode(el, {
            text: url,
            width: 300,
            height: 300,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.M
        });
    })();
</script>
@endsection

