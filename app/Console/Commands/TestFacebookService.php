<?php

namespace App\Console\Commands;

use App\Services\FacebookService;

use Illuminate\Console\Command;

class TestFacebookService extends Command
{

    protected $signature = 'test:facebook';
    protected $description = 'Test du service Facebook';

    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        parent::__construct();
        $this->facebookService = $facebookService;
    }

    public function handle()
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $accessToken = env('FACEBOOK_ACCESS_TOKEN');
        $message = 'Ceci est un test depuis e-benin !';

        $response = $this->facebookService->postToFacebookPage($pageId, $message, $accessToken);

        if (isset($response['error'])) {
            $this->error('Erreur : ' . $response['message']);
        } else {
            $this->info('Message publié avec succès : ' . json_encode($response));
        }
    }
}
