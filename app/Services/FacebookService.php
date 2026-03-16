<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FacebookService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'https://graph.facebook.com/v21.0/';
    }

    /**
     * Publie un message sur une page Facebook.
     *
     * @param string $pageId
     * @param string $message
     * @param string $accessToken
     * @return array
     */
    public function postToFacebookPage(string $pageId, string $message, string $accessToken): array
    {
        try {
            $response = $this->client->post("{$this->baseUrl}{$pageId}/feed", [
                'form_params' => [
                    'message' => $message,
                    'access_token' => $accessToken,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Renvoyer les détails de l'erreur pour le traitement
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }
}
