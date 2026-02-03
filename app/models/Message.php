<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'specialist_id', 'sender_type', 'sender_name', 'message', 'message_type', 'file_path', 'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function specialist()
    {
        return $this->belongsTo(\App\Models\Specialist::class, 'specialist_id');
    }
}
