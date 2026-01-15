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
<a href="{{ route('passports.edit', $passport) }}" class="btn btn-primary">Edit</a>
<a href="{{ route('passports.barcode', $passport) }}" class="btn btn-outline-primary">Barcode</a>
<a href="{{ route('passports.index') }}" class="btn btn-outline-secondary">Back to list</a>
@endsection
