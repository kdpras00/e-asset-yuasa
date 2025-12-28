<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'reason',
        'disposal_date',
        'method',
        'notes',
        'approved_by',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
