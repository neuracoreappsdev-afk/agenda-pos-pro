<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceSource extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function clients()
    {
        return $this->hasMany('App\Models\Customer', 'reference_source_id');
    }
}
