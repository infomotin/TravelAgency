@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Passports</h1>
    <a href="{{ route('passports.create') }}" class="btn btn-primary">Add Passport</a>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Holder</th>
            <th>Mobile</th>
            <th>Passport No</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Purpose</th>
            <th>Entry Charge</th>
            <th>Commission</th>
            <th>Charge Type</th>
            <th>Local Agent</th>
        </tr>
        </thead>
        <tbody>
        @forelse($passports as $passport)
            <tr>
                <td>{{ $passport->holder_name }}</td>
                <td>{{ $passport->mobile }}</td>
                <td>{{ $passport->passport_no }}</td>
                <td>{{ optional($passport->issue_date)->format('Y-m-d') }}</td>
                <td>{{ optional($passport->expiry_date)->format('Y-m-d') }}</td>
                <td>{{ $passport->purpose }}</td>
                <td>{{ number_format($passport->entry_charge, 2) }}</td>
                <td>{{ number_format($passport->person_commission, 2) }}</td>
                <td>{{ $passport->is_free ? 'Free' : 'Charged' }}</td>
                <td>{{ $passport->local_agent_name }}</td>
                <td class="text-end">
                    <a href="{{ route('passports.show', $passport) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    <a href="{{ route('passports.edit', $passport) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('passports.destroy', $passport) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this passport?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No passports found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $passports->links() }}
@endsection
