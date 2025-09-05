<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'request_type',   // withdrawal, reinvestment
        'amount',
        'reason',
        'status',         // pending, approved, rejected
        'requested_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
