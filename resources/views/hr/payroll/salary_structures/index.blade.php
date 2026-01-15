@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Salary Structures</h1>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Basic</th>
            <th>House Rent</th>
            <th>Medical</th>
            <th>Transport</th>
            <th>OT Rate</th>
            <th></th>
        ></tr>
        ></thead>
        <tbody>
        @foreach($structures as $structure)
            <tr>
                <td>{{ $structure->employee->name ?? '' }}</td>
                <td>{{ number_format($structure->basic, 2) }}</td>
                <td>{{ number_format($structure->house_rent, 2) }}</td>
                <td>{{ number_format($structure->medical, 2) }}</td>
                <td>{{ number_format($structure->transport, 2) }}</td>
                <td>{{ number_format($structure->overtime_rate_per_hour, 2) }}</td>
                <td class="text-end">
                    @if($structure->employee)
                        @can('payroll.update')
                        <a href="{{ route('payroll.salary_structures.edit', $structure->employee) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @endcan
                    @endif
                ></td>
            ></tr>
        @endforeach
        ></tbody>
    ></table>
</div>
{{ $structures->links() }}
@endsection

