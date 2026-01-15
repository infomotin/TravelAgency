<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TravelAgency ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">TravelAgency ERP</a>
        <div class="d-flex">
            @auth
                <span class="navbar-text me-3">{{ auth()->user()->name }}</span>
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
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-2 col-lg-2 bg-white border-end min-vh-100 p-0">
            <div class="list-group list-group-flush small">
                <div class="list-group-item text-bg-light fw-semibold">
                    {{ app('currentAgency')->name ?? 'Agency' }}
                </div>
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                @can('view-agencies')
                <a href="{{ route('agencies.index') }}" class="list-group-item list-group-item-action">Agencies</a>
                @endcan
                @can('view-employees')
                <a href="{{ route('employees.index') }}" class="list-group-item list-group-item-action">Employees</a>
                @endcan
                @can('view-security')
                <div class="list-group-item text-muted fw-semibold">Security</div>
                <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action ps-4">Roles</a>
                <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action ps-4">Permissions</a>
                @endcan
                @can('view-payroll')
                <div class="list-group-item text-muted fw-semibold">Payroll</div>
                <a href="{{ route('payroll.salary_structures.index') }}" class="list-group-item list-group-item-action ps-4">Salary Structures</a>
                <a href="{{ route('payroll.advances.index') }}" class="list-group-item list-group-item-action ps-4">Advances</a>
                <a href="{{ route('payroll.payslips.index') }}" class="list-group-item list-group-item-action ps-4">Payslips</a>
                @endcan
                @can('view-hr-setup')
                <div class="list-group-item text-muted fw-semibold">HR Setup</div>
                <a href="{{ route('departments.index') }}" class="list-group-item list-group-item-action ps-4">Departments</a>
                <a href="{{ route('designations.index') }}" class="list-group-item list-group-item-action ps-4">Designations</a>
                <a href="{{ route('shifts.index') }}" class="list-group-item list-group-item-action ps-4">Shifts</a>
                <a href="{{ route('leave_policies.index') }}" class="list-group-item list-group-item-action ps-4">Leave Policies</a>
                @endcan
                @can('view-hr-reports')
                <div class="list-group-item text-muted fw-semibold">HR Reports</div>
                <a href="{{ route('hr_reports.employees') }}" class="list-group-item list-group-item-action ps-4">Employee Summary</a>
                <a href="{{ route('hr_reports.attendance') }}" class="list-group-item list-group-item-action ps-4">Attendance</a>
                <a href="{{ route('hr_reports.leaves') }}" class="list-group-item list-group-item-action ps-4">Leaves</a>
                @endcan
            </div>
        </aside>
        <main class="col-md-10 col-lg-10 py-4">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
