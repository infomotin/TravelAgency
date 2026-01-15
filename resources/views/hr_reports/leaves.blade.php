@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Leave Report</h1>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from" value="{{ $filters['from'] }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to" value="{{ $filters['to'] }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Policy</label>
        <select name="leave_policy_id" class="form-select">
            <option value="">All</option>
            @foreach($policies as $policy)
                <option value="{{ $policy->id }}" @if(($filters['leave_policy_id'] ?? '')==$policy->id) selected @endif>{{ $policy->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">All</option>
            <option value="pending" @if(($filters['status'] ?? '')==='pending') selected @endif>Pending</option>
            <option value="approved" @if(($filters['status'] ?? '')==='approved') selected @endif>Approved</option>
            <option value="rejected" @if(($filters['status'] ?? '')==='rejected') selected @endif>Rejected</option>
        </select>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100">Go</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Policy</th>
            <th>Start</th>
            <th>End</th>
            <th>Days</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->name ?? '' }}</td>
                <td>{{ $leave->policy->name ?? '' }}</td>
                <td>{{ optional($leave->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($leave->end_date)->format('Y-m-d') }}</td>
                <td>
                    @if($leave->start_date && $leave->end_date)
                        {{ $leave->end_date->diffInDays($leave->start_date) + 1 }}
                    @endif
                </td>
                <td>{{ ucfirst($leave->status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $leaves->links() }}
@endsection

