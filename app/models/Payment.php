<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $table = 'payments';
    protected $fillable = ['sale_id', 'payment_method_id', 'amount', 'tip'];
}
