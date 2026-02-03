<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'status',
        'start_date',
        'end_date',
        'max_uses',
        'current_uses',
        'min_purchase',
        'description'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'max_uses' => 'integer',
        'current_uses' => 'integer',
        'min_purchase' => 'decimal:2',
    ];
}
