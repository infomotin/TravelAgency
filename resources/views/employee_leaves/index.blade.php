@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Employee Leave Applications</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Days</th>
            <th>Status</th>
            <th>Attachment</th>
            <th class="text-end">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->name ?? '' }}</td>
                <td>{{ $leave->policy->name ?? '' }}</td>
                <td>{{ optional($leave->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($leave->end_date)->format('Y-m-d') }}</td>
                <td>{{ $leave->days }}</td>
                <td>
                    <span class="badge @if($leave->status==='approved') bg-success @elseif($leave->status==='rejected') bg-danger @else bg-secondary @endif">
                        {{ ucfirst($leave->status) }}
                    </span>
                </td>
                <td>
                    @if($leave->attachment_path)
                        <a href="{{ asset('storage/'.$leave->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                    @endif
                </td>
                <td class="text-end">
                    <form method="post" action="{{ route('employee_leaves.update', $leave) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select form-select-sm d-inline-block w-auto me-1">
                            <option value="pending" @if($leave->status==='pending') selected @endif>Pending</option>
                            <option value="approved" @if($leave->status==='approved') selected @endif>Approved</option>
                            <option value="rejected" @if($leave->status==='rejected') selected @endif>Rejected</option>
                        </select>
                        <button class="btn btn-sm btn-primary">Update</button>
                    </form>
                    <form method="post" action="{{ route('employee_leaves.destroy', $leave) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this application?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $leaves->links() }}
@endsection

