@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Salary Advances</h1>
    @can('payroll.create')
    <a href="{{ route('payroll.advances.create') }}" class="btn btn-primary">Add Advance</a>
    @endcan
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>Amount</th>
            <th>Note</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($advances as $advance)
            <tr>
                <td>{{ optional($advance->date)->format('Y-m-d') }}</td>
                <td>{{ $advance->employee->name ?? '' }}</td>
                <td>{{ number_format($advance->amount, 2) }}</td>
                <td>{{ $advance->note }}</td>
                <td class="text-end">
                    @can('payroll.delete')
                    <form action="{{ route('payroll.advances.destroy', $advance) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this advance?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $advances->links() }}
@endsection
