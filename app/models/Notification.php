<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['type', 'title', 'message', 'is_read', 'reference_type', 'reference_id', 'created_at'];

    public $timestamps = false;

    public static function unread()
    {
        return self::where('is_read', 0)->orderBy('created_at', 'desc')->get();
    }
}
