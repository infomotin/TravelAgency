@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Visa Report</h1>
<form method="get" action="{{ route('visas.report') }}" class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Country</label>
                <select name="country_id" class="form-select">
                    <option value="">All</option>
                    @php
                        $countryId = $filters['country_id'] ?? '';
                    @endphp
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" @if($countryId == $c->id) selected @endif>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Agent</label>
                <select name="agent_id" class="form-select">
                    <option value="">All</option>
                    @php
                        $agentId = $filters['agent_id'] ?? '';
                    @endphp
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}" @if($agentId == $a->id) selected @endif>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Visa Type</label>
                <input type="text" name="visa_type" value="{{ $filters['visa_type'] ?? '' }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('visas.report') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
        <a href="{{ route('visas.index') }}" class="btn btn-outline-secondary">Back to Visas</a>
    </div>
</form>
<div class="mb-3">
    <strong>Total Visa Fee:</strong> {{ number_format($totalVisaFee, 2) }}
    <span class="ms-3">
        <strong>Total Agent Commission:</strong> {{ number_format($totalAgentCommission, 2) }}
    </span>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Issue Date</th>
            <th>Passport No</th>
            <th>Holder</th>
            <th>Country</th>
            <th>Visa Type</th>
            <th>Visa Fee</th>
            <th>Agent</th>
            <th>Agent Commission</th>
        </tr>
        </thead>
        <tbody>
        @forelse($visas as $visa)
            <tr>
                <td>{{ optional($visa->issue_date)->format('Y-m-d') }}</td>
                <td>{{ $visa->passport->passport_no ?? '' }}</td>
                <td>{{ $visa->passport->holder_name ?? '' }}</td>
                <td>{{ $countryNames[$visa->country_id] ?? '' }}</td>
                <td>{{ $visa->visa_type }}</td>
                <td>{{ number_format($visa->visa_fee, 2) }}</td>
                <td>
                    @if($visa->agent_id)
                        {{ $agentNames[$visa->agent_id] ?? '' }}
                    @endif
                </td>
                <td>{{ number_format($visa->agent_commission, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No visas found for the selected filters.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

