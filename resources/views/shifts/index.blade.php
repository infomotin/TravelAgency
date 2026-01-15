@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Shifts</h1>
    @can('hr_setup.create')
    <a href="{{ route('shifts.create') }}" class="btn btn-primary">Add Shift</a>
    @endcan
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Grace (min)</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($shifts as $shift)
            <tr>
                <td>{{ $shift->name }}</td>
                <td>{{ $shift->start_time }}</td>
                <td>{{ $shift->end_time }}</td>
                <td>{{ $shift->grace_minutes }}</td>
                <td class="text-end">
                    @can('hr_setup.update')
                    <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @endcan
                    @can('hr_setup.delete')
                    <form action="{{ route('shifts.destroy', $shift) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this shift?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $shifts->links() }}
@endsection
