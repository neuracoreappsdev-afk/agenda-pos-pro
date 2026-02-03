<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialistAdvance extends Model
{
    protected $table = 'specialist_advances';
    
    protected $fillable = [
        'specialist_id', 'amount', 'type', 'reason', 'status', 
        'date', 'deducted_date', 'notes', 'created_by'
    ];

    protected $dates = ['date', 'deducted_date'];

    public function specialist()
    {
        return $this->belongsTo('App\Models\Specialist');
    }

    // Get pending advances for a specialist
    public static function pendingForSpecialist($specialistId)
    {
        return self::where('specialist_id', $specialistId)
            ->where('status', 'pending')
            ->orderBy('date')
            ->get();
    }

    // Get total pending amount for a specialist
    public static function pendingAmountForSpecialist($specialistId)
    {
        return self::where('specialist_id', $specialistId)
            ->where('status', 'pending')
            ->sum('amount');
    }
}
