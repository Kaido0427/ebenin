<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetSessionDomain
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        $domain = str_contains($host, 'e-benin.bj') ? '.e-benin.bj' : '.e-benin.com';

        Config::set('session.domain', $domain);

        return $next($request);
    }
}
