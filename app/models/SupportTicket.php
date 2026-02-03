<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';
    protected $fillable = ['business_id', 'subject', 'description', 'priority', 'status', 'assigned_to'];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id');
    }
}
