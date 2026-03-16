<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class user_organization extends Model
{
    use HasFactory; 

    protected $table = "user_organizations";

    protected $fillable = ['user_id', 'organization_id'];
}
