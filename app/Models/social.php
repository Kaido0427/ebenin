<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\organization;

class social extends Model
{
    use HasFactory;

    protected $table = "socials";

    protected $fillable = ['nom', 'url', 'organization_id'];


    public function organizations()
    {
        return $this->belongsToMany(organization::class, 'organization_socials', 'social_id', 'organization_id')
                    ->withPivot('url');
    }
}
