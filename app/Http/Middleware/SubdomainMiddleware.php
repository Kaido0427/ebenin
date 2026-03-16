<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request; 
use Symfony\Component\HttpFoundation\Response;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       // Récupérer le sous-domaine depuis l'URL
       $subdomain = $request->route('organization');

       // Je verifie si le sous-domaine existe dans la base de données
       if ($subdomain) {
           $organization = \App\Models\Organization::where('organization_name', $subdomain)->first();
           if ($organization) {
               // Mettre l'organisation dans l'instance de l'application
               app()->instance('currentOrganization', $organization);
           } else {
               return abort(404); // Sous-domaine non trouvé
           }
       }

       return $next($request);
    }
}
