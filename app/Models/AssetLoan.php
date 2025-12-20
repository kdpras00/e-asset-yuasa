<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrower_name',
        'borrower_position',
        'asset_id',
        'loan_date',
        'return_date',
        'status',
        'notes',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function activities()
    {
        return $this->hasMany(LoanActivity::class)->latest();
    }
}
