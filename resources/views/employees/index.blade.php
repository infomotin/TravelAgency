@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Status</th>
            <th>Joining Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee_code }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->department->name ?? '' }}</td>
                <td>{{ $employee->designation->name ?? '' }}</td>
                <td>{{ $employee->status }}</td>
                <td>{{ optional($employee->joining_date)->format('Y-m-d') }}</td>
                <td class="text-end">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this employee?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $employees->links() }}
@endsection

