<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegisterSession extends Model {

    protected $table = 'cash_register_sessions';
    protected $fillable = [
        'user_id',
        'opening_amount',
        'closing_amount',
        'calculated_amount',
        'difference',
        'closing_notes',
        'status',
        'opened_at',
        'closed_at',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // Scope para buscar sesión abierta
    public function scopeOpen($query, $userId)
    {
        return $query->where('user_id', $userId)->where('status', 'open');
    }
}
