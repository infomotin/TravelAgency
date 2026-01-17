@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Passport Details</h1>
<div class="card mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Holder Name</dt>
            <dd class="col-sm-9">{{ $passport->holder_name }}</dd>
            <dt class="col-sm-3">Mobile</dt>
            <dd class="col-sm-9">{{ $passport->mobile ?? '-' }}</dd>
            <dt class="col-sm-3">Address</dt>
            <dd class="col-sm-9">{{ $passport->address ?? '-' }}</dd>
            <dt class="col-sm-3">Passport Number</dt>
            <dd class="col-sm-9">{{ $passport->passport_no }}</dd>
            <dt class="col-sm-3">Issue Date</dt>
            <dd class="col-sm-9">{{ optional($passport->issue_date)->format('Y-m-d') }}</dd>
            <dt class="col-sm-3">Expiry Date</dt>
            <dd class="col-sm-9">{{ optional($passport->expiry_date)->format('Y-m-d') }}</dd>
            <dt class="col-sm-3">Purpose</dt>
            <dd class="col-sm-9">
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
            </dd>
            <dt class="col-sm-3">Entry Charge</dt>
            <dd class="col-sm-9">{{ number_format($passport->entry_charge, 2) }}</dd>
            <dt class="col-sm-3">Person Commission</dt>
            <dd class="col-sm-9">{{ number_format($passport->person_commission, 2) }}</dd>
            <dt class="col-sm-3">Passport Charge</dt>
            <dd class="col-sm-9">{{ $passport->is_free ? 'Free' : 'Charged' }}</dd>
            <dt class="col-sm-3">Local Agent</dt>
            <dd class="col-sm-9">
                @if($passport->local_agent_name)
                    {{ $passport->local_agent_name }}
                @else
                    <span class="text-muted">Not set</span>
                @endif
            </dd>
            <dt class="col-sm-3">Agent Commission</dt>
            <dd class="col-sm-9">
                @if($passport->local_agent_commission_type)
                    @if($passport->local_agent_commission_type === 'percentage')
                        {{ number_format($passport->local_agent_commission_value, 2) }}% of charge
                    @else
                        Fixed {{ number_format($passport->local_agent_commission_value, 2) }}
                    @endif
                    (Amount: {{ number_format($passport->local_agent_commission_amount, 2) }})
                @else
                    <span class="text-muted">No agent commission</span>
                @endif
            </dd>
            <dt class="col-sm-3">Invoice Number</dt>
            <dd class="col-sm-9">
                @if($passport->invoice_no)
                    {{ $passport->invoice_no }}
                @else
                    <span class="text-muted">Not generated</span>
                @endif
            </dd>
            <dt class="col-sm-3">Invoice Date</dt>
            <dd class="col-sm-9">
                @if($passport->invoice_date)
                    {{ optional($passport->invoice_date)->format('Y-m-d') }}
                @else
                    <span class="text-muted">Not set</span>
                @endif
            </dd>
            <dt class="col-sm-3">Document</dt>
            <dd class="col-sm-9">
                @if($passport->document_path)
                    <a href="{{ Storage::disk('public')->url($passport->document_path) }}" target="_blank">View Document</a>
                @else
                    <span class="text-muted">Not uploaded</span>
                @endif
            </dd>
        </dl>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">Attachments</div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($passport->attachments as $att)
                <div class="col-md-6 d-flex align-items-center justify-content-between border rounded p-2">
                    <div>
                        <div class="fw-semibold text-capitalize">{{ $att->type }}</div>
                        <a href="{{ Storage::disk('public')->url($att->file_path) }}" target="_blank">{{ $att->file_name }}</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted">No attachments.</div>
            @endforelse
        </div>
    </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Visas</span>
        <a href="{{ route('visas.create', ['passport_id' => $passport->id]) }}" class="btn btn-sm btn-primary">Add Visa</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>Country</th>
                    <th>Visa Type</th>
                    <th>Issue Date</th>
                    <th>Expiry Date</th>
                    <th>Visa Fee</th>
                    <th>Agent Commission</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($passport->visas as $visa)
                    <tr>
                        <td>{{ $countryNames[$visa->country_id] ?? '' }}</td>
                        <td>{{ $visa->visa_type }}</td>
                        <td>{{ optional($visa->issue_date)->format('Y-m-d') }}</td>
                        <td>{{ optional($visa->expiry_date)->format('Y-m-d') }}</td>
                        <td>{{ number_format($visa->visa_fee, 2) }}</td>
                        <td>{{ number_format($visa->agent_commission, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('visas.edit', $visa) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No visas added for this passport.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<a href="{{ route('passports.edit', $passport) }}" class="btn btn-primary">Edit</a>
<a href="{{ route('passports.invoice', $passport) }}" class="btn btn-success">Invoice / Receipt</a>
<a href="{{ route('passports.barcode', $passport) }}" class="btn btn-outline-primary">Barcode</a>
<a href="{{ route('passports.index') }}" class="btn btn-outline-secondary">Back to list</a>
@endsection
