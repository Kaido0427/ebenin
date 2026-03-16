<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = explode('.', $request->getHost())[0];

        $organization = Organization::where('organization_name', $subdomain)->first();

        // Enregistrer l'organisation dans la session ou un autre mécanisme de stockage
        session(['organization' => $organization]);

        return $next($request);
    }
}
