@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Departments</h1>
    @can('hr_setup.create')
    <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
    @endcan
></div>
<div class="table-responsive">
    <table class="table table-striped align-middle" data-datatable="true">
        <thead>
        <tr>
            <th>Name</th>
            <th></th>
        ></tr>
        ></thead>
        <tbody>
        @foreach($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
                <td class="text-end">
                    @can('hr_setup.update')
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @endcan
                    @can('hr_setup.delete')
                    <form action="{{ route('departments.destroy', $department) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this department?')">Delete</button>
                    ></form>
                    @endcan
                ></td>
            ></tr>
        @endforeach
        ></tbody>
    ></table>
></div>
{{ $departments->links() }}
@endsection

