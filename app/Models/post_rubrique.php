<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_rubrique extends Model
{
    use HasFactory;

    protected $table="post_rubrique";

    protected $fillable=['post_id','rubrique_id'];
}
