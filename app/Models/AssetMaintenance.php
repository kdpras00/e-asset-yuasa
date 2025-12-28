<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'description',
        'cost',
        'warranty_status',
        'room',
        'image',
        'start_date',
        'completion_date',
        'status',
        'performed_by',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
