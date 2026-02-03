<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {

    protected $table = 'sales';
    protected $fillable = [
        'total',
        'subtotal',
        'discount',
        'payment_method',
        'customer_id',
        'customer_name',
        'user_id',
        'cash_register_session_id',
        'notes',
        'items_json',
        'sale_date',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    // Relaciones
    public function user()
    {
        return $this->belongsTo('App\Models\Admin', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\CashRegisterSession', 'cash_register_session_id');
    }

    public function specialist()
    {
        return $this->belongsTo('App\Models\Specialist', 'specialist_id');
    }

    public function saleItems()
    {
        return $this->hasMany('App\Models\SaleItem', 'sale_id');
    }

    public function getItemsAttribute()
    {
        return json_decode($this->items_json, true) ?? [];
    }
}
