@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">New Leave Application</h1>

<form method="post" action="{{ route('employee_leaves.store') }}" enctype="multipart/form-data" class="card p-3">
    @csrf
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Employee</label>
            <select name="employee_id" class="form-select" required>
                <option value="">Select</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" @if(old('employee_id')==$employee->id) selected @endif>
                        {{ $employee->name }} ({{ $employee->employee_code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Leave Type</label>
            <select name="leave_policy_id" class="form-select" required>
                <option value="">Select</option>
                @foreach($policies as $policy)
                    <option value="{{ $policy->id }}" @if(old('leave_policy_id')==$policy->id) selected @endif>
                        {{ $policy->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">To</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
        </div>
        <div class="col-md-12">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Attachment (for Sick Leave / others)</label>
            <input type="file" name="attachment" class="form-control">
            <div class="form-text">Upload medical certificate or related document if needed.</div>
        </div>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary">Submit Leave Application</button>
        <a href="{{ route('employee_leaves.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection
