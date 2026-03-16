<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
   /* public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Vérifier si le champ `updated_at` a dépassé 2 heures
            if ($user->updated_at->diffInHours(now()) > 2) {
                // Redirection vers la page de réabonnement
                return redirect()->route('subscription');
            }
        }
        // Continuer avec la requête si les conditions ne sont pas remplies
        return $next($request);
    }*/
}
