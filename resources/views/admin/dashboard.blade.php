@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Admin Dashboard - {{ $currentAgency->name ?? '' }}</h1>
    <form method="get" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 me-2">Month</label>
        <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" style="width:auto;">
        <button class="btn btn-sm btn-outline-secondary">Apply</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <div class="card-title h6 mb-1">Employees</div>
                <div class="display-6">{{ $employeeCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
            <div class="card-title h6 mb-1">Tickets</div>
            <div class="display-6">{{ $ticketCount }}</div>
          </div>
        </div>
      </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <div class="card-title h6 mb-1">Approved Payslips ({{ $month }})</div>
                <div class="display-6">{{ $approvedPayslips }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <div class="card-title h6 mb-1">Ticket Profit ({{ $month }})</div>
                <div class="display-6">{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        Quick Links
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <a href="{{ route('agencies.index') }}" class="btn btn-outline-primary w-100">Agencies</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-primary w-100">Employees</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('departments.index') }}" class="btn btn-outline-primary w-100">HR Setup</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary w-100">Air Ticket Invoices</a>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-md-3">
                <a href="{{ route('visas.index') }}" class="btn btn-outline-primary w-100">Visa Invoices</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('passports.index') }}" class="btn btn-outline-primary w-100">Passport Entries</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">Service Products</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('parties.index') }}" class="btn btn-outline-secondary w-100">Customers / Parties</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                Daily Tickets ({{ $month }})
            </div>
            <div class="card-body">
                <canvas id="adminDailyTicketsChart" height="160"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                Tickets by Airline ({{ $month }})
            </div>
            <div class="card-body">
                <canvas id="adminAirlineTicketsChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        var dailyLabels = @json($dailyLabels);
        var dailyTicketCounts = @json($dailyTicketCounts);
        var airlineLabels = @json($airlineLabels);
        var airlineTicketCounts = @json($airlineTicketCounts);

        var dailyCanvas = document.getElementById('adminDailyTicketsChart');
        if (dailyCanvas && typeof Chart !== 'undefined') {
            new Chart(dailyCanvas, {
                type: 'bar',
                data: {
                    labels: dailyLabels,
                    datasets: [
                        {
                            label: 'Tickets',
                            data: dailyTicketCounts,
                            backgroundColor: 'rgba(13,110,253,0.6)',
                            borderColor: 'rgba(13,110,253,1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        var airlineCanvas = document.getElementById('adminAirlineTicketsChart');
        if (airlineCanvas && typeof Chart !== 'undefined') {
            var palette = [
                'rgba(13,110,253,0.7)',
                'rgba(25,135,84,0.7)',
                'rgba(255,193,7,0.7)',
                'rgba(220,53,69,0.7)',
                'rgba(102,16,242,0.7)',
                'rgba(32,201,151,0.7)'
            ];

            new Chart(airlineCanvas, {
                type: 'pie',
                data: {
                    labels: airlineLabels,
                    datasets: [
                        {
                            data: airlineTicketCounts,
                            backgroundColor: airlineLabels.map(function (_, index) {
                                return palette[index % palette.length];
                            }),
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    });
</script>
@endsection
