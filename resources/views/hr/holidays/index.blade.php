@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Holidays</h1>
    <div class="d-flex align-items-center gap-2">
        <form method="get" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 small">Year</label>
            <input type="number" name="year" value="{{ $year }}" class="form-control form-control-sm" style="width: 100px;">
            <button class="btn btn-sm btn-outline-secondary">Go</button>
        </form>
        @can('hr_setup.create')
        <a href="{{ route('holidays.create') }}" class="btn btn-primary btn-sm">Add Holiday</a>
        @endcan
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th style="width: 130px;">Date</th>
            <th>Name</th>
            <th style="width: 160px;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($holidays as $holiday)
            <tr>
                <td>{{ $holiday->date->format('Y-m-d') }}</td>
                <td>{{ $holiday->name }}</td>
                <td class="text-end">
                    @can('hr_setup.update')
                    <a href="{{ route('holidays.edit', $holiday) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @endcan
                    @can('hr_setup.delete')
                    <form action="{{ route('holidays.destroy', $holiday) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this holiday?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $holidays->appends(['year' => $year])->links() }}
@endsection
