<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorProfitDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_profit_id',
        'investor_id',
        'shares_held',
        'share_of_profit',
        'investor_portion',
        'owner_portion',
        'status', // available, pending, withdrawn, reinvested
    ];

    protected $casts = [
        'share_of_profit' => 'decimal:2',
        'investor_portion' => 'decimal:2',
        'owner_portion' => 'decimal:2',
    ];

    public function monthlyProfit()
    {
        return $this->belongsTo(MonthlyProfit::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }
}
