@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Visas</h1>
    <a href="{{ route('visas.create') }}" class="btn btn-primary">Add Visa</a>
</div>
<div class="mb-3">
    <a href="{{ route('visas.report') }}" class="btn btn-outline-secondary btn-sm">Visa Report</a>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Passport No</th>
            <th>Holder</th>
            <th>Country</th>
            <th>Visa Type</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Visa Fee</th>
            <th>Agent</th>
            <th>Agent Commission</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($visas as $visa)
            <tr>
                <td>{{ $visa->passport->passport_no ?? '' }}</td>
                <td>{{ $visa->passport->holder_name ?? '' }}</td>
                <td>{{ $countryNames[$visa->country_id] ?? '' }}</td>
                <td>{{ $visa->visa_type }}</td>
                <td>{{ optional($visa->issue_date)->format('Y-m-d') }}</td>
                <td>{{ optional($visa->expiry_date)->format('Y-m-d') }}</td>
                <td>{{ number_format($visa->visa_fee, 2) }}</td>
                <td>
                    @if($visa->agent_id)
                        {{ $agentNames[$visa->agent_id] ?? '' }}
                    @endif
                </td>
                <td>{{ number_format($visa->agent_commission, 2) }}</td>
                <td class="text-end">
                    <a href="{{ route('visas.edit', $visa) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('visas.destroy', $visa) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this visa record?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No visas found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $visas->links() }}
@endsection

