<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    protected $table = 'cash_movements';
    protected $fillable = ['type', 'concept', 'amount', 'payment_method', 'reference', 'notes', 'movement_date', 'cash_register_session_id', 'sale_id'];

    public $timestamps = true;
}
