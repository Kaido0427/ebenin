<?php

namespace App\Observers;

use App\Models\post;

use App\Services\FacebookService;
use Illuminate\Support\Facades\Log;


class PostObserver
{

    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }
    /**
     * Handle the post "created" event.
     */
    public function created(post $post): void
    {
        $accessToken = env('FACEBOOK_ACCESS_TOKEN');
        $pageId = env('FACEBOOK_PAGE_ID');

        $message = $post->user->organization->organization_name . " - " . $post->libelle .
            "\nPour en savoir plus : " . route('single-post', [
                'organization' => $post->user->organization->subdomain,
                'id' => $post->id,
            ]);

        $result = $this->facebookService->postToFacebookPage($pageId, $message, $accessToken);

        if (isset($result['error'])) {
            Log::error('Erreur lors de la publication sur Facebook', $result);
        } else {
            Log::info('Article publié sur Facebook avec succès', $result);
        } 
    }

    /**
     * Handle the post "updated" event.
     */
    public function updated(post $post): void
    {
        //
    }

    /**
     * Handle the post "deleted" event.
     */
    public function deleted(post $post): void
    {
        //
    }

    /**
     * Handle the post "restored" event.
     */
    public function restored(post $post): void
    {
        //
    }

    /**
     * Handle the post "force deleted" event.
     */
    public function forceDeleted(post $post): void
    {
        //
    }
}
