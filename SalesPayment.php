<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'customer_id',
        'payment_date',
        'payment_amount',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
