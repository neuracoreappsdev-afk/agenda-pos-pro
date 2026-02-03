<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'order'
    ];

    public function specialists()
    {
        return $this->hasMany('App\Models\Specialist');
    }
}
