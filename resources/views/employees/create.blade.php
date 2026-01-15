@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Employee</h1>
<form method="post" action="{{ route('employees.store') }}" class="row g-3">
    @csrf
    <div class="col-md-3">
        <label class="form-label">Code</label>
        <input name="employee_code" class="form-control" value="{{ old('employee_code') }}">
    </div>
    <div class="col-md-5">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" @if(old('status','active')==='active') selected @endif>active</option>
            <option value="inactive" @if(old('status')==='inactive') selected @endif>inactive</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Department</label>
        <select name="department_id" class="form-select">
            <option value="">Select</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @if(old('department_id')==$department->id) selected @endif>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Designation</label>
        <select name="designation_id" class="form-select">
            <option value="">Select</option>
            @foreach($designations as $designation)
                <option value="{{ $designation->id }}" @if(old('designation_id')==$designation->id) selected @endif>{{ $designation->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Shift</label>
        <select name="shift_id" class="form-select">
            <option value="">Select</option>
            @foreach($shifts as $shift)
                <option value="{{ $shift->id }}" @if(old('shift_id')==$shift->id) selected @endif>{{ $shift->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Joining Date</label>
        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Probation End Date</label>
        <input type="date" name="probation_end_date" class="form-control" value="{{ old('probation_end_date') }}">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

