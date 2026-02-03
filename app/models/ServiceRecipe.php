<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecipe extends Model
{
    protected $table = 'service_recipes';
    
    protected $fillable = [
        'package_id', 'product_id', 'quantity', 'unit'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
}
