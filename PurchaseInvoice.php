<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'invoice_date',
        'due_date',
        'credit_days',
        'subtotal',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'settled_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'settled_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function inventoryLots()
    {
        return $this->hasMany(InventoryLot::class);
    }
}