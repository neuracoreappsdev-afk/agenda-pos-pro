<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';
    protected $fillable = ['title', 'description', 'questions_json', 'active', 'trigger_event', 'delay_minutes'];
    
    protected $casts = [
        'questions_json' => 'array',
        'active' => 'boolean'
    ];

    public function responses()
    {
        return $this->hasMany('App\Models\SurveyResponse', 'survey_id');
    }
}
