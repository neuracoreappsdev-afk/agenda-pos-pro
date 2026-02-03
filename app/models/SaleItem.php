<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model {
    protected $table = 'sale_items';
    protected $fillable = [
        'sale_id', 'item_type', 'item_id', 'item_name', 'quantity', 'unit_price', 
        'tax_amount', 'total', 'commission_value', 'discount_value', 'specialist_id'
    ];
    public $timestamps = true;

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }

    public function specialist()
    {
        return $this->belongsTo('App\Models\Specialist', 'specialist_id');
    }
}
