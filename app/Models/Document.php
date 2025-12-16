<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'type',
        'file_path',
        'status',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
