@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">{{ $employee->name }} ({{ $employee->employee_code }})</h1>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<dl class="row">
    <dt class="col-sm-3">Status</dt>
    <dd class="col-sm-9">{{ $employee->status }}</dd>

    <dt class="col-sm-3">Department</dt>
    <dd class="col-sm-9">{{ $employee->department->name ?? '' }}</dd>

    <dt class="col-sm-3">Designation</dt>
    <dd class="col-sm-9">{{ $employee->designation->name ?? '' }}</dd>

    <dt class="col-sm-3">Shift</dt>
    <dd class="col-sm-9">{{ $employee->shift->name ?? '' }}</dd>

    <dt class="col-sm-3">Joining Date</dt>
    <dd class="col-sm-9">{{ optional($employee->joining_date)->format('Y-m-d') }}</dd>

    <dt class="col-sm-3">Probation End Date</dt>
    <dd class="col-sm-9">{{ optional($employee->probation_end_date)->format('Y-m-d') }}</dd>
</dl>
@endsection

