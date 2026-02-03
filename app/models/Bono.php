<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bono extends Model
{
    protected $table = 'bonos';

    protected $fillable = [
        'code', 'customer_id', 'buyer_name', 'recipient_name', 
        'recipient_email', 'recipient_phone', 'message', 
        'amount', 'balance', 'status', 'expiry_date', 'payment_id'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
