<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitDistribution extends Model
{
    // Specify the database table if it does not follow Laravel naming convention
    protected $table = 'profit_distributions';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'amount',
        'admin_user_id',
        'notes',
        'created_at',
        'updated_at',
        // Add other relevant fields as per your database schema
    ];

    /**
     * Relationship to the admin user who performed the profit distribution
     *
     * @return BelongsTo
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
