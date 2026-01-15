@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Departments</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
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
        @foreach($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
                <td class="text-end">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('departments.destroy', $department) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this department?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $departments->links() }}
@endsection

