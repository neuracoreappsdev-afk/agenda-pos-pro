<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialPlan extends Model
{
    protected $table = 'commercial_plans';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 
        'validity_days', 'max_uses', 'benefits_json', 'is_active'
    ];

    protected $casts = [
        'benefits_json' => 'array',
        'is_active' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (!$plan->slug) {
                $plan->slug = str_slug($plan->name);
            }
        });
    }
}
