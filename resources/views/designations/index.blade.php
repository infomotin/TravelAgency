@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Designations</h1>
    <a href="{{ route('designations.create') }}" class="btn btn-primary">Add Designation</a>
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
                    <a href="{{ route('designations.edit', $designation) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('designations.destroy', $designation) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this designation?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $designations->links() }}
@endsection

