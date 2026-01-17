<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Holder Name</label>
        <input type="text" name="holder_name" value="{{ old('holder_name', $passport->holder_name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Mobile</label>
        <input type="text" name="mobile" value="{{ old('mobile', $passport->mobile ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Passport Number</label>
        <input type="text" name="passport_no" value="{{ old('passport_no', $passport->passport_no ?? '') }}" class="form-control" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" value="{{ old('address', $passport->address ?? '') }}" class="form-control">
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', optional($passport->issue_date ?? null)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($passport->expiry_date ?? null)->format('Y-m-d')) }}" class="form-control" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <label class="form-label">Entry Charge</label>
        <input type="number" step="0.01" name="entry_charge" value="{{ old('entry_charge', $passport->entry_charge ?? 0) }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Person Commission</label>
        <input type="number" step="0.01" name="person_commission" value="{{ old('person_commission', $passport->person_commission ?? 0) }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Passport Charge</label>
        <select name="is_free" class="form-select">
            <option value="0" @if(old('is_free', $passport->is_free ?? false) == false) selected @endif>Charged</option>
            <option value="1" @if(old('is_free', $passport->is_free ?? false) == true) selected @endif>Free</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Purpose</label>
        <select name="purpose" class="form-select">
            @php
                $purposeValue = old('purpose', $passport->purpose ?? '');
            @endphp
            <option value="">Select purpose</option>
            <option value="visa" @if($purposeValue === 'visa') selected @endif>Visa</option>
            <option value="ticket" @if($purposeValue === 'ticket') selected @endif>Ticket</option>
            <option value="both" @if($purposeValue === 'both') selected @endif>Visa + Ticket</option>
            <option value="other" @if($purposeValue === 'other') selected @endif>Other</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Local Agent</label>
        <select name="local_agent_id" class="form-select">
            <option value="">Select local agent</option>
            @php $localAgentId = old('local_agent_id', $passport->local_agent_id ?? ''); @endphp
            @foreach(($localAgents ?? []) as $agent)
                <option value="{{ $agent->id }}" @if($localAgentId == $agent->id) selected @endif>{{ $agent->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Passport Status</label>
        <select name="passport_status_id" class="form-select">
            <option value="">Select passport status</option>
            @php $statusId = old('passport_status_id', $passport->passport_status_id ?? ''); @endphp
            @foreach(($passportStatuses ?? []) as $status)
                <option value="{{ $status->id }}" @if($statusId == $status->id) selected @endif>{{ $status->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <select name="country_id" class="form-select">
            <option value="">Select country</option>
            @php $countryId = old('country_id', $passport->country_id ?? ''); @endphp
            @foreach(($countries ?? []) as $c)
                <option value="{{ $c->id }}" @if($countryId == $c->id) selected @endif>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Airport</label>
        <select name="airport_id" class="form-select">
            <option value="">Select airport</option>
            @php $airportId = old('airport_id', $passport->airport_id ?? ''); @endphp
            @foreach(($airports ?? []) as $a)
                <option value="{{ $a->id }}" @if($airportId == $a->id) selected @endif>{{ $a->name }} @if($a->iata_code) ({{ $a->iata_code }}) @endif</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Airline</label>
        <select name="airline_id" class="form-select">
            <option value="">Select airline</option>
            @php $airlineId = old('airline_id', $passport->airline_id ?? ''); @endphp
            @foreach(($airlines ?? []) as $al)
                <option value="{{ $al->id }}" @if($airlineId == $al->id) selected @endif>{{ $al->name }} ({{ $al->iata_code }})</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Ticket Agency</label>
        <select name="ticket_agency_id" class="form-select">
            <option value="">Select ticket agency</option>
            @php $taId = old('ticket_agency_id', $passport->ticket_agency_id ?? ''); @endphp
            @foreach(($ticketAgencies ?? []) as $ta)
                <option value="{{ $ta->id }}" @if($taId == $ta->id) selected @endif>{{ $ta->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Currency</label>
        <select name="currency_id" class="form-select">
            <option value="">Select currency</option>
            @php $currencyId = old('currency_id', $passport->currency_id ?? ''); @endphp
            @foreach(($currencies ?? []) as $cr)
                <option value="{{ $cr->id }}" @if($currencyId == $cr->id) selected @endif>{{ $cr->code }} - {{ $cr->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Passport Document</label>
    <input type="file" name="document" class="form-control">
    @if(!empty($passport->document_path))
        <div class="form-text">
            Current file:
            <a href="{{ Storage::disk('public')->url($passport->document_path) }}" target="_blank">View</a>
        </div>
    @endif
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Passport Front Page</label>
        <input type="file" name="front" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Passport Back Page</label>
        <input type="file" name="back" class="form-control">
    </div>
    <div class="col-md-6 mt-3">
        <label class="form-label">Endorsement Pages</label>
        <input type="file" name="endorsement[]" class="form-control" multiple>
    </div>
    <div class="col-md-6 mt-3">
        <label class="form-label">Visa Pages</label>
        <input type="file" name="visa[]" class="form-control" multiple>
    </div>
</div>
@if(isset($passport) && $passport->exists)
<div class="card mb-3">
    <div class="card-header">Existing Attachments</div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($passport->attachments as $att)
                <div class="col-md-6 d-flex align-items-center justify-content-between border rounded p-2">
                    <div>
                        <div class="fw-semibold text-capitalize">{{ $att->type }}</div>
                        <a href="{{ Storage::disk('public')->url($att->file_path) }}" target="_blank">{{ $att->file_name }}</a>
                    </div>
                    <form action="{{ route('passport_attachments.destroy', $att) }}" method="post" class="ms-3">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            @endforeach
            @if($passport->attachments->isEmpty())
                <div class="col-12 text-muted">No attachments uploaded.</div>
            @endif
        </div>
    </div>
</div>
@endif
