<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $table = 'waitlist';

    protected $fillable = [
        'customer_id',
        'package_id',
        'specialist_id',
        'date_from',
        'date_to',
        'time_preference',
        'priority',
        'status',
        'notes',
        'notified_at',
        'responded_at'
    ];

    protected $dates = [
        'date_from',
        'date_to',
        'notified_at',
        'responded_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Relación con el cliente
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * Relación con el servicio/paquete
     */
    public function package()
    {
        return $this->belongsTo('App\Models\Package');
    }

    /**
     * Relación con el especialista (preferencia)
     */
    public function specialist()
    {
        return $this->belongsTo('App\Models\Specialist');
    }

    /**
     * Scope para obtener clientes en espera activa
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope para buscar clientes que coincidan con un slot liberado
     */
    public function scopeMatchingSlot($query, $date, $specialistId = null)
    {
        $query->where('status', 'waiting')
              ->where('date_from', '<=', $date)
              ->where('date_to', '>=', $date);

        if ($specialistId) {
            $query->where(function($q) use ($specialistId) {
                $q->whereNull('specialist_id')
                  ->orWhere('specialist_id', $specialistId);
            });
        }

        return $query->orderBy('priority', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     * Marcar como notificado
     */
    public function markAsNotified()
    {
        $this->status = 'notified';
        $this->notified_at = now();
        $this->save();
    }

    /**
     * El cliente aceptó el nuevo horario
     */
    public function markAsAccepted()
    {
        $this->status = 'accepted';
        $this->responded_at = now();
        $this->save();
    }

    /**
     * El cliente rechazó, pasar al siguiente
     */
    public function markAsPassed()
    {
        $this->status = 'passed';
        $this->responded_at = now();
        $this->save();
    }
}
