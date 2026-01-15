@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Leave Policies</h1>
    @can('hr_setup.create')
    <a href="{{ route('leave_policies.create') }}" class="btn btn-primary">Add Policy</a>
    @endcan
></div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Name</th>
            <th>Annual Quota</th>
            <th>Carry Forward</th>
            <th></th>
        ></tr>
        ></thead>
        <tbody>
        @foreach($policies as $policy)
            <tr>
                <td>{{ $policy->name }}</td>
                <td>{{ $policy->annual_quota }}</td>
                <td>{{ $policy->carry_forward ? 'Yes' : 'No' }}</td>
                <td class="text-end">
                    @can('hr_setup.update')
                    <a href="{{ route('leave_policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @endcan
                    @can('hr_setup.delete')
                    <form action="{{ route('leave_policies.destroy', $policy) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this policy?')">Delete</button>
                    ></form>
                    @endcan
                ></td>
            ></tr>
        @endforeach
        ></tbody>
    ></table>
></div>
{{ $policies->links() }}
@endsection

