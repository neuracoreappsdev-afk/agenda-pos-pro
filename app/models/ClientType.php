<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'default_discount',
        'auto_apply_discount'
    ];

    protected $casts = [
        'default_discount' => 'decimal:2',
        'auto_apply_discount' => 'boolean',
    ];

    public function clients()
    {
        return $this->hasMany('App\Models\Customer', 'client_type_id');
    }
}
