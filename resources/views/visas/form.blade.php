<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Passport</label>
        <select name="passport_id" class="form-select" required>
            <option value="">Select passport</option>
            @php
                $selectedPassport = old('passport_id', $visa->passport_id ?? ($selectedPassportId ?? ''));
            @endphp
            @foreach($passports as $p)
                <option value="{{ $p->id }}" @if($selectedPassport == $p->id) selected @endif>
                    {{ $p->passport_no }} - {{ $p->holder_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Country</label>
        <select name="country_id" class="form-select" required>
            <option value="">Select country</option>
            @php
                $selectedCountry = old('country_id', $visa->country_id ?? ($countryId ?? ''));
            @endphp
            @foreach($countries as $c)
                <option value="{{ $c->id }}" @if($selectedCountry == $c->id) selected @endif>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Visa Type</label>
        <select name="visa_type_id" class="form-select" required>
            <option value="">Select visa type</option>
            @php
                $selectedVisaType = old('visa_type_id', $visa->visa_type_id ?? '');
            @endphp
            @foreach($visaTypes as $type)
                <option value="{{ $type->id }}" @if($selectedVisaType == $type->id) selected @endif>
                    {{ $type->name }} @if($type->default_fee > 0) ({{ number_format($type->default_fee, 2) }}) @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', optional($visa->issue_date ?? null)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($visa->expiry_date ?? null)->format('Y-m-d')) }}" class="form-control" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Visa Fee</label>
        <input type="number" step="0.01" name="visa_fee" value="{{ old('visa_fee', $visa->visa_fee ?? 0) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Agent</label>
        <select name="agent_id" class="form-select">
            <option value="">Select agent</option>
            @php
                $selectedAgent = old('agent_id', $visa->agent_id ?? '');
            @endphp
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}" @if($selectedAgent == $agent->id) selected @endif>{{ $agent->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Visa Document</label>
        <input type="file" name="document" class="form-control">
        @if(!empty($visa->document_path))
            <div class="form-text">
                Current file:
                <a href="{{ Storage::disk('public')->url($visa->document_path) }}" target="_blank">View</a>
            </div>
        @endif
    </div>
</div>
@if(isset($visaTypes) && $visaTypes->isNotEmpty())
<div class="mb-3" id="visa-type-documents">
    <label class="form-label">Visa Type Documents</label>
    <div class="text-muted small mb-2">Upload files as per selected visa type.</div>
    @foreach($visaTypes as $type)
        @php
            $docs = $typeDocuments[$type->id] ?? collect();
        @endphp
        @if($docs->isNotEmpty())
            <div class="visa-doc-group mb-2" data-visa-type="{{ $type->id }}" style="display: none;">
                @foreach($docs as $doc)
                    <div class="mb-2">
                        <label class="form-label">{{ $doc->name }}</label>
                        <input type="file" name="type_documents[{{ $doc->id }}]" class="form-control">
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var selectEl = document.querySelector('select[name="visa_type_id"]');
        if (!selectEl) return;
        var groups = document.querySelectorAll('.visa-doc-group');
        function updateVisaDocGroups() {
            var val = selectEl.value;
            groups.forEach(function (g) {
                if (!val) {
                    g.style.display = 'none';
                } else if (g.getAttribute('data-visa-type') === val) {
                    g.style.display = '';
                } else {
                    g.style.display = 'none';
                }
            });
        }
        selectEl.addEventListener('change', updateVisaDocGroups);
        updateVisaDocGroups();
    });
</script>
@endif
