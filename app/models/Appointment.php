<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'customer_id',
        'package_id',
        'specialist_id',
        'appointment_type', // Corresponde al package_id
        'appointment_datetime',
        'notes',
        'status',
        'color',
        'confirm_token',
        'duration',
        'sale_id',
        'additional_services'
    ];

    protected $casts = [
        'additional_services' => 'array'
    ];

    protected $dates = [
        'appointment_datetime',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Relación con el cliente
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * Relación con el paquete (appointment_type)
     */
    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'appointment_type');
    }

    /**
     * Relación con el especialista
     */
    public function specialist()
    {
        return $this->belongsTo('App\Models\Specialist');
    }
}
