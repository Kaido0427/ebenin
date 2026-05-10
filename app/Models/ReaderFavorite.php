<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReaderFavorite extends Model
{
    protected $table    = 'reader_favorites';
    protected $fillable = ['user_type', 'user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(post::class, 'post_id');
    }
}
