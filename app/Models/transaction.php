<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";

    protected $fillable = [
        'phone',
        'amount',
        'status',
        'token',
        'payment_method',
        'organization_id'
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
