<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Reader extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'reader';

    protected $fillable = ['name', 'email', 'password', 'avatar', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_active' => 'boolean'];
}
