<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id',
        'inventory_lot_id',
        'product_name',
        'quantity_sold',
        'unit_selling_price',
        'unit_cost_price',
        'line_total',
        'line_cost',
        'line_profit',
    ];

    protected $casts = [
        'quantity_sold' => 'decimal:3',
        'unit_selling_price' => 'decimal:2',
        'unit_cost_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'line_cost' => 'decimal:2',
        'line_profit' => 'decimal:2',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function inventoryLot()
    {
        return $this->belongsTo(InventoryLot::class, 'inventory_lot_id');
    }
}