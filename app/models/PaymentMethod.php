<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'active',
        'requires_approval',
        'order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'requires_approval' => 'boolean',
    ];
}
