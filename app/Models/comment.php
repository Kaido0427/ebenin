<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model 
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = ['reader_name', 'reader_mail', 'comments', 'post_id'];


    public function post()
    {
        $this->belongsTo(Post::class, 'post_id');
    }
}
