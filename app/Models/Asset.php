<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'sap_code',
        'category',
        'group',
        'description',
        'purchase_date',
        'price',
        'quantity',
        'location',
        'department',
        'section',
        'pic',
        'image',
        'status',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
