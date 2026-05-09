<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReaderAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Accept reader, web (blogger) or advertiser
        if (Auth::guard('reader')->check() ||
            Auth::guard('web')->check() ||
            Auth::guard('advertiser')->check() ||
            Auth::guard('admin')->check()) {
            return $next($request);
        }

        return redirect()->route('reader.login');
    }
}
