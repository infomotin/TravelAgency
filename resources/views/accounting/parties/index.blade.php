@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Parties</h1>
    @can('parties.create')
    <a href="{{ route('parties.create') }}" class="btn btn-primary btn-sm">Add Party</a>
    @endcan
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $party->name }}</div>
                            <small class="text-muted">{{ $party->city }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $party->type === 'customer' ? 'text-bg-info' : 'text-bg-warning' }}">
                                {{ ucfirst($party->type) }}
                            </span>
                        </td>
                        <td>{{ $party->phone }}</td>
                        <td>{{ $party->email }}</td>
                        <td>
                            <!-- Placeholder for balance calculation -->
                            {{ number_format($party->opening_balance + $party->bills->sum('balance_amount'), 2) }}
                        </td>
                        <td>
                            @if($party->status === 'active')
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('parties.show', $party) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            @can('parties.update')
                            <a href="{{ route('parties.edit', $party) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endcan
                            @can('parties.delete')
                            <form action="{{ route('parties.destroy', $party) }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No parties found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $parties->links() }}
</div>
@endsection
