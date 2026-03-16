<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationPrefix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  /*  public function handle($request, Closure $next)
    {
        // Récupérer le nom de l'organisation (vous pouvez adapter cette partie selon vos besoins)
        $organization = auth()->user()->organization->name; 

        // Ajouter le préfixe au routeur
        \Route::prefix($organization)->group(function () {
            // Les routes ici seront préfixées par le nom de l'organisation
        });

        return $next($request);
    }*/
}
