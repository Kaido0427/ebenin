<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
          Carbon::setLocale('fr');

          // Détection dynamique du domaine pour les sessions
          $host = request()->getHost();
        
          if (str_contains($host, 'e-benin.bj')) {
              config(['session.domain' => '.e-benin.bj']);
          } elseif (str_contains($host, 'e-benin.com')) {
              config(['session.domain' => '.e-benin.com']);
          }
          
          // Si vous utilisez HTTPS, assurez-vous que les URL sont générées avec HTTPS
          if (env('APP_ENV') !== 'local') {
              URL::forceScheme('https');
          }
       
    }
}
