<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookAuthController extends Controller
{
    private string $appId     = '607829245588464';
    private string $appSecret = '9a3ac50a8f5c3b3ebe3c9a824eaeef82';
    private string $pageId    = '100089144914919';
    private string $redirectUri = 'https://e-benin.com/fb-auth/callback';

    public function redirect()
    {
        $url = 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query([
            'client_id'     => $this->appId,
            'redirect_uri'  => $this->redirectUri,
            'scope'         => 'pages_manage_posts,pages_read_engagement,pages_show_list',
            'response_type' => 'code',
        ]);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return response('Autorisation refusée : ' . $request->error_description, 400);
        }

        $code = $request->input('code');

        // Échanger le code contre un user token
        $tokenRes = Http::get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
        ])->json();

        if (isset($tokenRes['error'])) {
            return response('Erreur token : ' . json_encode($tokenRes['error']), 400);
        }

        $userToken = $tokenRes['access_token'];

        // Échanger contre un token long-lived
        $longRes = Http::get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->appId,
            'client_secret'     => $this->appSecret,
            'fb_exchange_token' => $userToken,
        ])->json();

        $longToken = $longRes['access_token'] ?? $userToken;

        // Récupérer le token de la page e-Bénin
        $pagesRes = Http::get("https://graph.facebook.com/v19.0/me/accounts", [
            'access_token' => $longToken,
            'fields'       => 'id,name,access_token',
        ])->json();

        $pageToken = null;
        $pageName  = null;
        foreach ($pagesRes['data'] ?? [] as $page) {
            if ($page['id'] === $this->pageId) {
                $pageToken = $page['access_token'];
                $pageName  = $page['name'];
                break;
            }
        }

        if (!$pageToken) {
            return view('fb-auth-result', [
                'success'   => false,
                'message'   => 'Page e-Bénin non trouvée dans les pages accessibles.',
                'pages'     => $pagesRes['data'] ?? [],
                'userToken' => $longToken,
            ]);
        }

        // Sauvegarder le token dans .env
        $this->updateEnv('FB_PAGE_TOKEN', $pageToken);
        $this->updateEnv('FB_PAGE_ID', $this->pageId);

        return view('fb-auth-result', [
            'success'  => true,
            'message'  => "Token de la page \"$pageName\" sauvegardé avec succès !",
            'pages'    => $pagesRes['data'] ?? [],
        ]);
    }

    private function updateEnv(string $key, string $value): void
    {
        $path    = base_path('.env');
        $content = file_get_contents($path);

        if (str_contains($content, "$key=")) {
            $content = preg_replace("/^$key=.*/m", "$key=$value", $content);
        } else {
            $content .= "\n$key=$value";
        }

        file_put_contents($path, $content);
    }
}
