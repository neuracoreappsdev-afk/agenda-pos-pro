<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentForm extends Model {

    protected $table = 'consent_forms';
    protected $fillable = ['customer_id', 'title', 'content', 'signature_data', 'ip_address', 'status'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

}
