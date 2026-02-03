<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'sku', 'category', 'price', 'cost', 'quantity', 'min_quantity', 'active'
    ];

    // Productos con stock bajo
    public static function lowStock()
    {
        return self::whereRaw('quantity <= min_quantity')->get();
    }
}
