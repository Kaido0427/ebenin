<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\post;

class rubrique extends Model
{
    use HasFactory;

    protected $table='rubriques';

    protected $fillable = ['name', 'description','titre'];

    public function posts()
    {
        return $this->belongsToMany(post::class, 'post_rubrique', 'rubrique_id', 'post_id');
    }

    
}
