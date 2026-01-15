@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Designations</h1>
    @can('hr_setup.create')
    <a href="{{ route('designations.create') }}" class="btn btn-primary">Add Designation</a>
    @endcan
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($designations as $designation)
            <tr>
                <td>{{ $designation->name }}</td>
                <td class="text-end">
                    @can('hr_setup.update')
                    <a href="{{ route('designations.edit', $designation) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @endcan
                    @can('hr_setup.delete')
                    <form action="{{ route('designations.destroy', $designation) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this designation?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $designations->links() }}
@endsection
