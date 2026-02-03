<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $table = 'survey_responses';
    protected $fillable = ['survey_id', 'customer_id', 'appointment_id', 'answers_json', 'rating', 'comments'];

    protected $casts = [
        'answers_json' => 'array'
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey', 'survey_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment', 'appointment_id');
    }
}
