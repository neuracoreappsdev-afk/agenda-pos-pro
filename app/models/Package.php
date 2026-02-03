<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model{
  	protected $table = 'packages';
  	protected $fillable = [
        'package_name', 'sku', 'package_price', 'package_time', 'package_description', 
        'category', 'commission_percentage', 'active', 'show_in_reservations',
        'custom_commission', 'block_qty_pos', 'require_deposit', 'extended_schedule',
        'enable_loyalty', 'prices_json', 'products_json', 'discounts_json', 'display_order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'show_in_reservations' => 'boolean',
        'custom_commission' => 'boolean',
        'block_qty_pos' => 'boolean',
        'require_deposit' => 'boolean',
        'enable_loyalty' => 'boolean',
        'prices_json' => 'array',
        'products_json' => 'array',
        'discounts_json' => 'array',
    ];

    public function specialists()
    {
        return $this->belongsToMany('App\Models\Specialist', 'package_specialist', 'package_id', 'specialist_id');
    }

    public function recipes()
    {
        return $this->hasMany('App\Models\ServiceRecipe', 'package_id');
    }
}
