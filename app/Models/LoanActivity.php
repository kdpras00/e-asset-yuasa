<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_loan_id',
        'user_id',
        'action',
        'description',
    ];

    public function loan()
    {
        return $this->belongsTo(AssetLoan::class, 'asset_loan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
