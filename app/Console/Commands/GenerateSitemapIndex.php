<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSitemapIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère un index des sitemaps';
 
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemaps = Storage::disk('public')->files('sitemaps');
        
        $content = view('sitemap-index', compact('sitemaps'))->render();
        
        // Assurez-vous que le contenu est bien formé
        $content = $this->sanitizeXml($content);

        $content = trim($content);
        
        Storage::disk('public')->put('sitemap.xml', $content);
        
        // Ajoutez les bons en-têtes
        $path = Storage::disk('public')->path('sitemap.xml');
        $this->setCorrectHeaders($path);
        
        $this->info('Index des sitemaps généré avec succès !');
    }

    private function sanitizeXml($content)
    {
        // Supprimez les caractères non valides en XML
        $content = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $content);
        
        // Assurez-vous que l'encodage est correct
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . trim($content);
    }

    private function setCorrectHeaders($path)
    {
        // Définissez le bon type MIME
        header('Content-Type: application/xml; charset=UTF-8');
        
        // Permettez l'accès depuis n'importe quelle origine
        header('Access-Control-Allow-Origin: *');
        
        // Désactivez la mise en cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }
}
