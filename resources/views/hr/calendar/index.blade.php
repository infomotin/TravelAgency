@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Calendar</h1>
    <form class="d-flex align-items-center gap-2" method="get">
        <input type="number" name="year" value="{{ $year }}" class="form-control form-control-sm" style="width: 100px;">
        <select name="month" class="form-select form-select-sm" style="width: 140px;">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($m === $month)>{{ \Illuminate\Support\Carbon::create(2000, $m, 1)->format('F') }}</option>
            @endfor
        ></select>
        <button class="btn btn-sm btn-outline-secondary">Show</button>
    ></form>
></div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif

<div class="card mb-3">
    <div class="card-header small fw-semibold">Generate Calendar (Full Year)</div>
    <div class="card-body">
        <form method="post" action="{{ route('calendar.generate') }}" class="row g-3">
            @csrf
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <input type="number" name="year" value="{{ $year }}" class="form-control form-control-sm @error('year') is-invalid @enderror">
                @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            ></div>
            <div class="col-md-3">
                <label class="form-label">Weekly Holiday Day</label>
                <select name="weekly_day" class="form-select form-select-sm @error('weekly_day') is-invalid @enderror">
                    @foreach(['sunday','monday','tuesday','wednesday','thursday','friday','saturday'] as $day)
                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                    @endforeach
                ></select>
                @error('weekly_day')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            ></div>
            <div class="col-md-7 d-flex align-items-end justify-content-end">
                <button class="btn btn-primary btn-sm">Generate Year</button>
            ></div>
        ></form>
        <div class="small text-muted mt-2">
            Status codes: WD = Working Day, HD = Weekly Holiday, GHD = Government Holiday (Bangladesh), OHD = Other Holiday.
        ></div>
    ></div>
</div>

<div class="card">
    <div class="card-header small fw-semibold">
        {{ \Illuminate\Support\Carbon::create($year, $month, 1)->format('F Y') }}
    ></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th style="width: 110px;">Date</th>
                    <th style="width: 120px;">Day</th>
                    <th style="width: 120px;">Status</th>
                    <th>Remarks</th>
                    <th style="width: 220px;"></th>
                ></tr>
                ></thead>
                <tbody>
                @forelse($dates as $cd)
                    <tr>
                        <td>{{ $cd->date->format('Y-m-d') }}</td>
                        <td>{{ $cd->date->format('l') }}</td>
                        <td>
                            <span class="badge
                                {{ $cd->status === 'WD' ? 'text-bg-secondary' : '' }}
                                {{ $cd->status === 'HD' ? 'text-bg-info' : '' }}
                                {{ $cd->status === 'GHD' ? 'text-bg-warning' : '' }}
                                {{ $cd->status === 'OHD' ? 'text-bg-success' : '' }}
                            ">
                                {{ $cd->status }}
                            ></span>
                        ></td>
                        <td>{{ $cd->remarks }}</td>
                        <td class="text-end">
                            @can('hr_setup.update')
                            <form method="post" action="{{ route('calendar.update', $cd) }}" class="d-inline d-flex gap-2 align-items-center justify-content-end">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                    @foreach(['WD','HD','GHD','OHD'] as $st)
                                        <option value="{{ $st }}" @selected($cd->status === $st)>{{ $st }}</option>
                                    @endforeach
                                ></select>
                                <input type="text" name="remarks" value="{{ $cd->remarks }}" class="form-control form-control-sm" placeholder="Remarks" style="width: 200px;">
                                <button class="btn btn-sm btn-outline-primary">Save</button>
                            ></form>
                            @endcan
                        ></td>
                    ></tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted small">No records. Generate calendar above.</td>
                    ></tr>
                @endforelse
                ></tbody>
            ></table>
        ></div>
    ></div>
</div>
@endsection

