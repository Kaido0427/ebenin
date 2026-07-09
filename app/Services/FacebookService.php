<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    private string $pageId;
    private string $pageToken;
    private string $apiVersion = 'v19.0';

    public function __construct()
    {
        $this->pageId    = config('services.facebook.page_id', env('FB_PAGE_ID', ''));
        $this->pageToken = config('services.facebook.page_token', env('FB_PAGE_TOKEN', ''));
    }

    public function postToPage(string $message, ?string $link = null): bool
    {
        if (empty($this->pageToken) || empty($this->pageId)) {
            Log::warning('Facebook auto-post skipped: FB_PAGE_TOKEN or FB_PAGE_ID not set.');
            return false;
        }

        $params = ['message' => $message, 'access_token' => $this->pageToken];
        if ($link) {
            $params['link'] = $link;
        }

        $res = Http::asForm()->post(
            "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/feed",
            $params
        );

        if ($res->successful() && isset($res->json()['id'])) {
            Log::info('Facebook post published', ['fb_id' => $res->json()['id']]);
            return true;
        }

        Log::error('Facebook post failed', ['response' => $res->json()]);
        return false;
    }
}
