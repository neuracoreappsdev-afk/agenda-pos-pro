<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $table = 'purchase_invoices';
    
    protected $fillable = [
        'provider_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'paid_amount',
        'notes',
        'created_by'
    ];

    protected $dates = ['invoice_date', 'due_date'];

    /**
     * Get the provider
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }

    /**
     * Get items
     */
    public function items()
    {
        return $this->hasMany('App\Models\PurchaseInvoiceItem', 'purchase_invoice_id');
    }

    /**
     * Get pending amount
     */
    public function getPendingAmountAttribute()
    {
        return $this->total - $this->paid_amount;
    }

    /**
     * Check if overdue
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->due_date) return false;
        return $this->due_date->isPast() && $this->status != 'paid';
    }
}
