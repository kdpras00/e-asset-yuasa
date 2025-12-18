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
        'description', // group removed
        'purchase_date',
        'price',
        'quantity',
        'stock', // stock added
        'location',
        'department',
        'section',
        'pic',
        'image',
        'status',
        'type',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
            ->orWhere('code', $value)
            ->orWhere('sap_code', $value)
            ->firstOrFail();
    }
}
