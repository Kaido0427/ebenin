<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\rubrique;
use App\Models\articleView;

class post extends Model
{
    use HasFactory;
 
    protected $table = 'posts';

    protected $fillable = ['description', 'libelle', 'image', 'audio','sous_titre', 'video', 'image_url', 'data_url', 'slug', 'user_id','video_url'];

    public function comments()
    {
       return $this->hasMany(comment::class,'post_id');
    }

     public function rubriques()
    {
        return $this->belongsToMany(rubrique::class, 'post_rubrique', 'post_id', 'rubrique_id');
    }

  

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function views()
    {
        return $this->hasMany(ArticleView::class, 'article_id');
    }
}
