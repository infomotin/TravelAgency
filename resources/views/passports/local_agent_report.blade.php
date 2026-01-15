@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Local Agent Commission Report</h1>
    <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm">Back to Passports</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('passports.local_agent_report') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Generate</button>
                <button type="submit" name="pdf" value="1" class="btn btn-danger">Download PDF</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($reportData->isEmpty())
            <div class="alert alert-info">No data found for the selected period.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Local Agent</th>
                            <th>Country</th>
                            <th>Month</th>
                            <th class="text-end">Count</th>
                            <th class="text-end">Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $agentId => $agent)
                            @php $agentFirst = true; @endphp
                            @foreach($agent['countries'] as $countryId => $country)
                                @php $countryFirst = true; @endphp
                                @foreach($country['months'] as $month => $data)
                                    <tr>
                                        @if($agentFirst && $countryFirst)
                                            <td rowspan="{{ $agent['countries']->sum(fn($c) => $c['months']->count()) + $agent['countries']->count() }}" class="align-middle fw-bold bg-light">{{ $agent['name'] }}</td>
                                            @php $agentFirst = false; @endphp
                                        @endif
                                        
                                        @if($countryFirst)
                                            <td rowspan="{{ $country['months']->count() + 1 }}" class="align-middle">{{ $country['name'] }}</td>
                                            @php $countryFirst = false; @endphp
                                        @endif
                                        
                                        <td>{{ $month }}</td>
                                        <td class="text-end">{{ $data['count'] }}</td>
                                        <td class="text-end">{{ number_format($data['total_commission'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-light fw-bold">
                                    <td colspan="2" class="text-end">Total for {{ $country['name'] }}</td>
                                    <td class="text-end">{{ number_format($country['total_commission'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-secondary fw-bold border-top-2">
                                <td colspan="4" class="text-end">Total for {{ $agent['name'] }}</td>
                                <td class="text-end">{{ number_format($agent['total_commission'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
