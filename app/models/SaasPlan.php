<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $table = 'saas_plans';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 
        'billing_cycle_days', 'max_users', 'max_branches', 
        'whatsapp_integration', 'has_ai', 'is_active'
    ];

    protected $casts = [
        'whatsapp_integration' => 'boolean',
        'has_ai' => 'boolean',
        'is_active' => 'boolean',
    ];
}
