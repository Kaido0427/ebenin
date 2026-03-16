<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\organization;
use App\Models\post;
use Illuminate\Support\Facades\Storage;

class GenerateSitemaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemaps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère des sitemaps pour chaque organisation de e-benin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         // Vérifie si le dossier 'sitemaps' existe, sinon le crée
         if (!Storage::disk('public')->exists('sitemaps')) { 
            Storage::disk('public')->makeDirectory('sitemaps');
        }

        $organizations = organization::all();

        foreach ($organizations as $organization) {
            $this->generateSitemapForOrganization($organization);
        }

        $this->info('Sitemaps générés avec succès !');
    }

    protected function generateSitemapForOrganization($organization)
    {
         // Récupérer le premier utilisateur lié à l'organisation
         $user = $organization->users->first();

         if ($user) {
             // Récupérer les posts de cet utilisateur
             $posts = post::where('user_id', $user->id)->get();
 
             // Générer le contenu du sitemap avec la vue Blade
             $sitemapContent = view('sitemap-template', compact('organization', 'posts'))->render();
 
             // Créer un nom de fichier pour le sitemap correspondant au sous-domaine de l'organisation
             $fileName = 'sitemaps/sitemap-' . $organization->subdomain . '.xml';
 
             // Sauvegarder le sitemap généré dans le disque public
             Storage::disk('public')->put($fileName, $sitemapContent);
         } else {
             $this->error('Aucun utilisateur trouvé pour l\'organisation : ' . $organization->organization_name);
         }
    }
    
}
