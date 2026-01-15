@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Passport Report</h1>
<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('passports.report') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From date</label>
                <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To date</label>
                <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Purpose</label>
                <select name="purpose" class="form-select">
                    @foreach($purposes as $value => $label)
                        <option value="{{ $value }}" @if(($filters['purpose'] ?? '') === $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Local agent</label>
                <select name="local_agent_name" class="form-select">
                    <option value="">All</option>
                    @foreach($localAgents as $agent)
                        <option value="{{ $agent }}" @if(($filters['local_agent_name'] ?? '') === $agent) selected @endif>{{ $agent }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Country</label>
                <select name="country_id" class="form-select">
                    <option value="">All</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @if(($filters['country_id'] ?? '') == $country->id) selected @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('passports.report.pdf', request()->all()) }}" class="btn btn-outline-secondary">Download PDF</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th>Country</th>
                    <th>Local agent</th>
                    <th>Purpose</th>
                    <th class="text-end">Passports</th>
                    <th class="text-end">Entry charge</th>
                    <th class="text-end">Agent commission</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $sumPassports = 0;
                    $sumEntry = 0;
                    $sumCommission = 0;
                @endphp
                @forelse($rows as $row)
                    @php
                        $sumPassports += $row->total_passports;
                        $sumEntry += $row->total_entry_charge;
                        $sumCommission += $row->total_agent_commission;
                        $purposeKey = $row->purpose ?? '';
                    @endphp
                    <tr>
                        <td>{{ $row->country_name ?: 'N/A' }}</td>
                        <td>{{ $row->local_agent_name ?: 'N/A' }}</td>
                        <td>{{ $purposes[$purposeKey] ?? ($purposeKey ?: 'N/A') }}</td>
                        <td class="text-end">{{ $row->total_passports }}</td>
                        <td class="text-end">{{ number_format($row->total_entry_charge, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_agent_commission, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No data for given filters.</td>
                    </tr>
                @endforelse
                </tbody>
                @if($rows->count())
                    <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">{{ $sumPassports }}</th>
                        <th class="text-end">{{ number_format($sumEntry, 2) }}</th>
                        <th class="text-end">{{ number_format($sumCommission, 2) }}</th>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

