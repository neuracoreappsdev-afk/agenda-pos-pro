<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientZone extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function clients()
    {
        return $this->hasMany('App\Models\Customer', 'zone_id');
    }
}
