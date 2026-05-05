<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertiserAccess
{
    public function handle(Request $request, Closure $next)
    {
        $advertiser = Auth::guard('advertiser')->user();

        if (!$advertiser) {
            return redirect()->route('advertiser.login');
        }

        if (!$advertiser->hasActiveAccess()) {
            return redirect()->route('advertiser.subscribe')
                ->with('warning', 'Votre période d\'essai a expiré. Abonnez-vous pour continuer.');
        }

        return $next($request);
    }
}
