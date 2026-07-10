<?php

namespace App\Observers;

use App\Models\post as Post;
use App\Services\FacebookService;

class PostObserver
{
    public function __construct(private FacebookService $facebook) {}

    public function created(Post $post): void
    {
        // Les articles créés via le dashboard blogueur sont publiés directement (null = published par défaut)
        if (in_array($post->editorial_status, ['published', null])) {
            $this->shareArticle($post);
        }
    }

    public function updated(Post $post): void
    {
        // Articles passant explicitement à "published" via le panel admin
        if (
            $post->wasChanged('editorial_status') &&
            $post->editorial_status === 'published'
        ) {
            $this->shareArticle($post);
        }
    }

    private function shareArticle(Post $post): void
    {
        $title   = $post->libelle ?? '';
        $excerpt = $post->sous_titre
            ? $post->sous_titre
            : mb_substr(strip_tags($post->description ?? ''), 0, 200);

        // URL correcte avec le subdomain de l'organisation
        $subdomain = $post->user->organization->subdomain ?? null;
        $baseDomain = 'e-benin.com';
        $url = $subdomain
            ? "https://{$subdomain}.{$baseDomain}/post/{$post->id}"
            : url("/post/{$post->id}");

        // Image de l'article si disponible
        $imageUrl = null;
        if ($post->image) {
            $imageUrl = "https://{$baseDomain}/{$post->image}";
        } elseif ($post->image_url) {
            $imageUrl = $post->image_url;
        }

        $message = "📰 {$title}";
        if ($excerpt) {
            $message .= "\n\n{$excerpt}";
        }
        $message .= "\n\n👉 Lire sur e-Bénin : {$url}";

        $this->facebook->postToPage($message, $url, $imageUrl);
    }
}
