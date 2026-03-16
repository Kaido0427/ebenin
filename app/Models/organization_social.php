<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\social;
use App\Models\organization;

class organization_social extends Model
{
    use HasFactory;
    protected $table = "organization_socials";

    protected $fillable = ['organization_id', 'social_id', 'url'];

    public function organization()
    {
        return $this->belongsTo(organization::class, 'organization_id');
    }

    public function social()
    {
        return $this->belongsTo(social::class, 'social_id');
    }
}
