<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'holidays';
    public $timestamps = true;
    
    protected $fillable = [
        'date', 'name', 'country_code', 'active'
    ];

    protected $dates = ['date'];

    /**
     * Get holidays for a specific month and year
     */
    public static function forMonth($month, $year)
    {
        return self::whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month)
            ->where('active', true)
            ->get();
    }
}
