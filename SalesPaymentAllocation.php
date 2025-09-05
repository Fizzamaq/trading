<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'sales_invoice_id',
        'allocated_amount',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(SalesPayment::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }
}
