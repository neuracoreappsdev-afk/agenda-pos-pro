<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    protected $table = 'specialists';

    protected $fillable = [
        'name', 'last_name', 'id_type', 'identification', 'dv',
        'country_code', 'phone', 'email', 'display_name', 'code',
        'title', 'avatar', 'category', 'active', 'pin', 'pin_reset_required',
        'services_json', 'commissions', 'schedule', 'working_hours', 'time_blocks', 'schedule_exceptions', 'mobile_user'
    ];

    protected $casts = [
        'active' => 'boolean',
        'mobile_user' => 'boolean',
        'pin_reset_required' => 'boolean',
        'schedule' => 'array',
        'working_hours' => 'array',
        'commissions' => 'array',
        'time_blocks' => 'array',
        'schedule_exceptions' => 'array',
        'services_json' => 'array',
    ];

    // Accessor para nombre completo
    public function getFullNameAttribute()
    {
        $lastName = $this->last_name ?? '';
        return trim(($this->name ?? '') . ' ' . $lastName);
    }

    public function packages()
    {
        return $this->belongsToMany('App\Models\Package', 'package_specialist', 'specialist_id', 'package_id');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment');
    }
}
