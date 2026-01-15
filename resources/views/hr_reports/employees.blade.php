@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Employee Summary</h1>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <label class="form-label">Department</label>
        <select name="department_id" class="form-select">
            <option value="">All</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @if(($filters['department_id'] ?? '')==$department->id) selected @endif>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">All</option>
            <option value="active" @if(($filters['status'] ?? '')==='active') selected @endif>Active</option>
            <option value="inactive" @if(($filters['status'] ?? '')==='inactive') selected @endif>Inactive</option>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100">Filter</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Shift</th>
            <th>Status</th>
            <th>Joining</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee_code }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->department->name ?? '' }}</td>
                <td>{{ $employee->designation->name ?? '' }}</td>
                <td>{{ $employee->shift->name ?? '' }}</td>
                <td>{{ $employee->status }}</td>
                <td>{{ optional($employee->joining_date)->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $employees->links() }}
@endsection

