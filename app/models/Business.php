<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = [
        'name', 'slug', 'owner_name', 'email', 'phone', 'location', 'plan_type', 'status', 'logo', 'app_id'
    ];
}
