<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TravelAgency ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top left, #eef2ff, #f9fafb);
        }
        .app-shell {
            min-height: 100vh;
        }
        .app-navbar {
            background: linear-gradient(90deg, #0d6efd, #0b4ba8);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .app-sidebar {
            background-color: #ffffff;
            box-shadow: 1px 0 0 rgba(15,23,42,.06);
        }
        .app-sidebar .list-group-item-action {
            border: 0;
            border-radius: 0;
            transition: background-color .15s ease, color .15s ease;
        }
        .app-sidebar .list-group-item-action.active,
        .app-sidebar .list-group-item-action:hover {
            background-color: #0d6efd;
            color: #fff;
        }
        .app-sidebar .section-title {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .app-main {
            background-color: #f9fafb;
        }
        .app-main-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-user-badge {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
<nav class="navbar app-navbar navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">TravelAgency ERP</a>
        <div class="d-flex align-items-center gap-3">
            @auth
                <div class="d-flex align-items-center gap-2 text-white small">
                    <div class="nav-user-badge">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="opacity-75">{{ app('currentAgency')->name ?? 'Agency' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="post" class="mb-0">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Logout</button>
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
        <aside class="col-md-2 col-lg-2 app-sidebar border-end p-0">
            <div class="list-group list-group-flush small">
                <div class="list-group-item text-bg-light fw-semibold d-flex align-items-center justify-content-between">
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
                <div class="list-group-item text-muted fw-semibold section-title">Security</div>
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action ps-4">Users</a>
                <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action ps-4">Roles</a>
                <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action ps-4">Permissions</a>
                @endcan
                @can('payroll.view')
                <div class="list-group-item text-muted fw-semibold section-title">Payroll</div>
                <a href="{{ route('payroll.salary_structures.index') }}" class="list-group-item list-group-item-action ps-4">Salary Structures</a>
                <a href="{{ route('payroll.advances.index') }}" class="list-group-item list-group-item-action ps-4">Advances</a>
                <a href="{{ route('payroll.payslips.index') }}" class="list-group-item list-group-item-action ps-4">Payslips</a>
                @endcan
                @can('accounts.view')
                <div class="list-group-item text-muted fw-semibold section-title">Accounting</div>
                <a href="{{ route('accounts.index') }}" class="list-group-item list-group-item-action ps-4">Chart of Accounts</a>
                <a href="{{ route('transactions.index') }}" class="list-group-item list-group-item-action ps-4">Transactions</a>
                <a href="{{ route('accounting.reports.ledger') }}" class="list-group-item list-group-item-action ps-4">Ledger</a>
                <a href="{{ route('accounting.reports.trial_balance') }}" class="list-group-item list-group-item-action ps-4">Trial Balance</a>
                @endcan
                @can('hr_setup.view')
                <div class="list-group-item text-muted fw-semibold section-title">HR Setup</div>
                <a href="{{ route('departments.index') }}" class="list-group-item list-group-item-action ps-4">Departments</a>
                <a href="{{ route('designations.index') }}" class="list-group-item list-group-item-action ps-4">Designations</a>
                <a href="{{ route('shifts.index') }}" class="list-group-item list-group-item-action ps-4">Shifts</a>
                <a href="{{ route('leave_policies.index') }}" class="list-group-item list-group-item-action ps-4">Leave Policies</a>
                @endcan
                @can('hr_reports.view')
                <div class="list-group-item text-muted fw-semibold section-title">HR Reports</div>
                <a href="{{ route('hr_reports.employees') }}" class="list-group-item list-group-item-action ps-4">Employee Summary</a>
                <a href="{{ route('hr_reports.attendance') }}" class="list-group-item list-group-item-action ps-4">Attendance</a>
                <a href="{{ route('hr_reports.leaves') }}" class="list-group-item list-group-item-action ps-4">Leaves</a>
                @endcan
            </div>
        </aside>
        <main class="col-md-10 col-lg-10 py-4 app-main">
            <div class="app-main-inner px-2 px-md-3">
                @yield('content')
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
