@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">My Leaves</h1>
    <a href="{{ route('my_leaves.create') }}" class="btn btn-primary">Apply Leave</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Days</th>
            <th>Status</th>
            <th>Attachment</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaves as $leave)
            <tr>
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
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $leaves->links() }}
@endsection

