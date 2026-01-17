<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            if (str_contains($ability, '.') && method_exists($user, 'hasPermission')) {
                if ($user->hasPermission($ability)) {
                    return true;
                }
            }

            return null;
        });

        Gate::define('view-agencies', function ($user) {
            return $user->hasPermission('agencies.view');
        });

        Gate::define('view-employees', function ($user) {
            return $user->hasPermission('employees.view');
        });

        Gate::define('view-security', function ($user) {
            return $user->hasPermission('security.view');
        });

        Gate::define('view-payroll', function ($user) {
            return $user->hasPermission('payroll.view');
        });

        Gate::define('view-hr-setup', function ($user) {
            return $user->hasPermission('hr_setup.view');
        });

        Gate::define('view-hr-reports', function ($user) {
            return $user->hasPermission('hr_reports.view');
        });
    }
}
