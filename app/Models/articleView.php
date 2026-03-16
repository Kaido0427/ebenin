<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\post;

class articleView extends Model
{
    use HasFactory;

    protected $table = "article_views"; // Nom de la table
    protected $fillable = ['article_id', 'ip_address', 'viewed_at']; // Champs remplissables

    /**
     * Relation avec le modèle Post
     * Une vue appartient à un article
     */
    public function post()
    {
        return $this->belongsTo(post::class, 'article_id');
    }
}
