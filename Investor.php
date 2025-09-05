<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'investment_amount', 'total_shares', 'profit_percentage',
        'grace_period_start', 'bank_account_number', 'bank_name',
        'account_holder_name', 'onboarding_completed', 'declaration_signed',
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'grace_period_start' => 'date',
        'onboarding_completed' => 'boolean',
        'declaration_signed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profitDistributions()
    {
        return $this->hasMany(InvestorProfitDistribution::class);
    }

    public function requests()
    {
        return $this->hasMany(InvestorRequest::class);
    }
}
