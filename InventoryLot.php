<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_number',
        'product_name',
        'original_quantity',
        'remaining_quantity',
        'unit_cost',
        'total_cost',
        'purchase_date',
        'supplier_id',
        'purchase_invoice_id',
    ];

    protected $casts = [
        'original_quantity' => 'decimal:3',
        'remaining_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function salesInvoiceItems()
    {
        return $this->hasMany(SalesInvoiceItem::class, 'inventory_lot_id');
    }
}
