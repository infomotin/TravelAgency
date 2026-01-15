@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3">Agencies</h1>
    @can('agencies.create')
    <a href="{{ route('agencies.create') }}" class="btn btn-primary">Create Agency</a>
    @endcan
    </div>
<div class="table-responsive mt-3">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Currency</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($agencies as $agency)
            <tr>
                <td>{{ $agency->name }}</td>
                <td>{{ $agency->slug }}</td>
                <td>{{ $agency->status }}</td>
                <td>{{ $agency->currency }}</td>
                <td class="text-end">
                    <a href="{{ route('agencies.show', $agency) }}" class="btn btn-sm btn-outline-secondary">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $agencies->links() }}
@endsection
