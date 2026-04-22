<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\Admin;
 
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
        'organization_id',
        'source',
        'reference',
        'paid_at',
        'months_awarded',
        'admin_id',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'months_awarded' => 'integer',
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
