<?php

namespace App\Http\Middleware;

use App\Models\Agency;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentAgency
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $headerSlug = $request->header('X-Agency');
        $slug = $headerSlug ?: explode('.', $host)[0];
        $agency = null;

        if ($slug) {
            $agency = Agency::where('slug', $slug)->first();
        }

        if (!$agency) {
            $agency = Agency::first();
        }

        app()->instance('currentAgency', $agency);

        return $next($request);
    }
}

