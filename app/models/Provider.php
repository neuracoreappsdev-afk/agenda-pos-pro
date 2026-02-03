<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';
    
    protected $fillable = [
        'company_name',
        'contact_name',
        'nit',
        'email',
        'phone',
        'address',
        'city',
        'notes',
        'payment_terms',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get all purchase invoices for this provider
     */
    public function purchaseInvoices()
    {
        return $this->hasMany('App\Models\PurchaseInvoice', 'provider_id');
    }

    /**
     * Get total purchases amount
     */
    public function getTotalPurchasesAttribute()
    {
        return $this->purchaseInvoices()->sum('total');
    }

    /**
     * Get pending balance
     */
    public function getPendingBalanceAttribute()
    {
        return $this->purchaseInvoices()
                    ->whereIn('status', ['pending', 'partial'])
                    ->selectRaw('SUM(total - paid_amount) as balance')
                    ->first()
                    ->balance ?? 0;
    }
}
