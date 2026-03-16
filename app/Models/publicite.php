<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class publicite extends Model
{
    use HasFactory;

    protected $table = "publicites";
    protected $fillable = ['image', 'url', 'space'];
}
