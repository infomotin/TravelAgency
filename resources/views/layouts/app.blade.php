<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TravelAgency ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.0.8/datatables.min.css">
    <style>
        body {
            background-color: #f3f4f6;
            color: #0f172a;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .app-shell {
            min-height: 100vh;
        }
        .app-navbar {
            background-color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid rgba(148,163,184,.4);
            box-shadow: 0 4px 12px rgba(15,23,42,.06);
        }
        .app-navbar .navbar-brand {
            letter-spacing: .08em;
            font-size: .9rem;
            text-transform: uppercase;
            color: #111827;
        }
        .app-navbar .navbar-brand span {
            font-weight: 700;
        }
        .app-navbar .badge {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: #eff6ff;
        }
        .app-navbar .btn {
            border-radius: 999px;
        }
        .app-sidebar {
            background-color: #ffffff;
            border-right: 1px solid rgba(148,163,184,.4);
            position: sticky;
            top: 0;
            height: calc(100vh - 56px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        .app-sidebar .list-group-item {
            background-color: transparent;
            color: #4b5563;
        }
        .app-sidebar .list-group-item-action {
            border: 0;
            border-radius: .6rem;
            margin-inline: .5rem;
            margin-block: .1rem;
            padding-block: .5rem;
            padding-inline: .75rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 500;
            font-size: .85rem;
            transition: background-color .15s ease, color .15s ease, box-shadow .15s ease;
        }
        .app-sidebar .list-group-item-action i {
            font-size: 1rem;
            color: #9ca3af;
        }
        .app-sidebar .list-group-item-action.active {
            background-color: #0f172a;
            color: #f9fafb;
            box-shadow: 0 8px 18px rgba(15,23,42,.22);
        }
        .app-sidebar .list-group-item-action.active i {
            color: #e5e7eb;
        }
        .app-sidebar .list-group-item-action:hover {
            background-color: #e5f0ff;
            color: #111827;
        }
        .app-sidebar .section-title {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .16em;
            color: #9ca3af;
            padding-inline: .75rem;
            padding-top: .85rem;
            cursor: pointer;
        }
        .app-main {
            background: radial-gradient(circle at top left, rgba(191,219,254,.45), #f3f4f6);
        }
        .app-main-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding-inline: .75rem;
        }
        .app-content-card {
            background-color: #ffffff;
            border-radius: 1.1rem;
            box-shadow:
                0 18px 45px rgba(15,23,42,.08),
                0 0 0 1px rgba(148,163,184,.2);
            padding: 1.5rem 1.5rem 2rem;
            margin-top: 1.25rem;
        }
        .app-content-card h1,
        .app-content-card h2,
        .app-content-card h3,
        .app-content-card h4,
        .app-content-card h5,
        .app-content-card h6 {
            color: #111827;
        }
        .nav-user-badge {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 0, #eff6ff, #bfdbfe);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #1e3a8a;
        }
        .nav-user-meta {
            line-height: 1.2;
        }
        .nav-user-meta .name {
            font-size: .9rem;
            color: #111827;
        }
        .nav-user-meta .agency {
            font-size: .75rem;
            color: #6b7280;
        }
        .card {
            border-radius: 1rem;
            border: 1px solid rgba(209,213,219,.8);
            background-color: #ffffff;
            box-shadow: 0 12px 30px rgba(15,23,42,.06);
        }
        .card-header {
            border-bottom-color: rgba(229,231,235,1);
            background: linear-gradient(90deg, #f9fafb, #eff6ff);
            color: #111827;
        }
        .table {
            color: #111827;
        }
        .table thead th {
            border-bottom-color: rgba(209,213,219,1);
            color: #6b7280;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: #f9fafb;
        }
        .table-striped>tbody>tr:nth-of-type(even)>* {
            background-color: #ffffff;
        }
        .form-control,
        .form-select {
            border-radius: .6rem;
            border-color: rgba(209,213,219,1);
            background-color: #ffffff;
            color: #111827;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 .12rem rgba(37,99,235,.25);
        }
        .list-group-item.text-bg-light {
            background: linear-gradient(90deg, #f9fafb, #eff6ff);
            border-bottom: 1px solid rgba(209,213,219,1);
            color: #111827;
        }
        .badge {
            border-radius: 999px;
            padding-inline: .6rem;
            padding-block: .25rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
<nav class="navbar app-navbar navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            <span>TravelAgency</span>
            <span class="badge rounded-pill text-bg-light text-uppercase">ERP</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            @auth
                <div class="d-flex align-items-center gap-2 text-white small">
                    <div class="nav-user-badge">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="text-end nav-user-meta">
                        <div class="fw-semibold name">{{ auth()->user()->name }}</div>
                        <div class="agency">{{ app('currentAgency')->name ?? 'Agency' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="post" class="mb-0">
                    @csrf
                    <button class="btn btn-outline-light btn-sm px-3">Logout</button>
                </form>
            @endauth
            @guest
                <a class="btn btn-outline-light btn-sm" href="{{ route('login.form') }}">Login</a>
            @endguest
        </div>
    </div>
</nav>
<div class="container-fluid app-shell">
    <div class="row">
        <aside class="col-md-2 col-lg-2 app-sidebar p-0">
            <div class="list-group list-group-flush small">
                <div class="list-group-item text-bg-light fw-semibold d-flex align-items-center justify-content-between border-0">
                    {{ app('currentAgency')->name ?? 'Agency' }}
                </div>
                <a href="{{ auth()->user() && auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('agency.dashboard') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') || request()->routeIs('agency.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                @can('agencies.view')
                <a href="{{ route('agencies.index') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('agencies.*') ? 'active' : '' }}">
                    <i class="bi bi-building me-2"></i>Agencies
                </a>
                @endcan
                @can('employees.view')
                <a href="{{ route('employees.index') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>Employees
                </a>
                @endcan
                @can('security.view')
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="security">
                    Security
                </div>
                <div class="module-items" data-module="security">
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('roles.*') ? 'active' : '' }}">Roles</a>
                    <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('permissions.*') ? 'active' : '' }}">Permissions</a>
                </div>
                @endcan
                @can('payroll.view')
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="payroll">
                    Payroll
                </div>
                <div class="module-items" data-module="payroll">
                    <a href="{{ route('payroll.salary_structures.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('payroll.salary_structures.*') ? 'active' : '' }}">Salary Structures</a>
                    <a href="{{ route('payroll.advances.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('payroll.advances.*') ? 'active' : '' }}">Advances</a>
                    <a href="{{ route('payroll.payslips.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('payroll.payslips.*') ? 'active' : '' }}">Payslips</a>
                </div>
                @endcan
                @can('accounts.view')
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="accounting">
                    Accounting
                </div>
                <div class="module-items" data-module="accounting">
                    <a href="{{ route('parties.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('parties.*') ? 'active' : '' }}">Parties</a>
                    <a href="{{ route('accounts.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('accounts.*') ? 'active' : '' }}">Chart of Accounts</a>
                    <a href="{{ route('transactions.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('transactions.*') ? 'active' : '' }}">Transactions</a>
                    <a href="{{ route('bills.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('bills.*') ? 'active' : '' }}">Bills</a>
                    @can('accounting_reports.view')
                    <a href="{{ route('accounting.reports.ledger') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('accounting.reports.ledger') ? 'active' : '' }}">Ledger</a>
                    <a href="{{ route('accounting.reports.trial_balance') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('accounting.reports.trial_balance') ? 'active' : '' }}">Trial Balance</a>
                    <a href="{{ route('accounting.reports.profit_loss') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('accounting.reports.profit_loss') ? 'active' : '' }}">Profit &amp; Loss</a>
                    @endcan
                </div>
                @endcan
                @if(auth()->user() && auth()->user()->hasRole('admin'))
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="travel">
                    Travel
                </div>
                <div class="module-items" data-module="travel">
                    <a href="{{ route('passports.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('passports.index') || request()->routeIs('passports.show') || request()->routeIs('passports.edit') || request()->routeIs('passports.create') ? 'active' : '' }}">Passports</a>
                    <a href="{{ route('passports.setup') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('passports.setup') ? 'active' : '' }}">Passport Setup</a>
                    <a href="{{ route('passports.setup') }}#local-agents" class="list-group-item list-group-item-action ps-4">Local Agent Setup</a>
                    <a href="{{ route('passports.report') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('passports.report') ? 'active' : '' }}">Passport Report</a>
                </div>
                @endif
                @can('hr_setup.view')
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="hr-setup">
                    HR Setup
                </div>
                <div class="module-items" data-module="hr-setup">
                    <a href="{{ route('departments.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('departments.*') ? 'active' : '' }}">Departments</a>
                    <a href="{{ route('designations.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('designations.*') ? 'active' : '' }}">Designations</a>
                    <a href="{{ route('shifts.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('shifts.*') ? 'active' : '' }}">Shifts</a>
                    <a href="{{ route('leave_policies.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('leave_policies.*') ? 'active' : '' }}">Leave Policies</a>
                    <a href="{{ route('holidays.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('holidays.*') ? 'active' : '' }}">Holidays</a>
                    <a href="{{ route('calendar.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('calendar.*') ? 'active' : '' }}">Calendar</a>
                </div>
                @endcan
                @can('hr_reports.view')
                <div class="list-group-item text-muted fw-semibold section-title module-toggle" data-module="hr-reports">
                    HR
                </div>
                <div class="module-items" data-module="hr-reports">
                    <a href="{{ route('hr_reports.employees') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('hr_reports.employees') ? 'active' : '' }}">Employee Summary</a>
                    <a href="{{ route('hr_reports.attendance') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('hr_reports.attendance') ? 'active' : '' }}">Attendance</a>
                    <a href="{{ route('employee_leaves.index') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('employee_leaves.*') ? 'active' : '' }}">Leave Applications</a>
                    <a href="{{ route('hr_reports.leaves') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('hr_reports.leaves') ? 'active' : '' }}">Leave Report</a>
                </div>
                @endcan
            </div>
        </aside>
        <main class="col-md-10 col-lg-10 py-4 app-main">
            <div class="app-main-inner">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="app-content-card">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3fp9pRkz6N0yGv0JxCqGSIbY8ZqOPVIT5p7MT2N0Ztk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.8/datatables.min.js"></script>
<script>
    $(function () {
        $('table[data-datatable="true"]').each(function () {
            $(this).DataTable({
                pageLength: 25,
                order: [],
                lengthChange: true
            });
        });

        $('.module-items').hide();

        var initialModule = null;
        $('.module-items').each(function () {
            if ($(this).find('.active').length) {
                initialModule = $(this).data('module');
            }
        });

        if (initialModule) {
            $('.module-items[data-module="' + initialModule + '"]').show();
        }

        $('.module-toggle').on('click', function () {
            var module = $(this).data('module');

            if ($('.module-items[data-module="' + module + '"]').is(':visible')) {
                $('.module-items[data-module="' + module + '"]').slideUp(150);
            } else {
                $('.module-items').slideUp(150);
                $('.module-items[data-module="' + module + '"]').slideDown(150);
            }
        });
    });
</script>
</body>
</html>
