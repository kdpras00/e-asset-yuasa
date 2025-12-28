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
        'warranty_proof',
        'status',
        'type',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function disposals()
    {
        return $this->hasOne(AssetDisposal::class); // Usually an asset is disposed once
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($asset) {
            if (empty($asset->sap_code)) {
                $asset->sap_code = self::generateUniqueSapCode();
            }
            if (empty($asset->code)) {
                $asset->code = self::generateUniqueCode();
            }
            // Set initial status to pending for approval workflow
            if (empty($asset->status)) {
                $asset->status = 'pending';
            }
        });
    }

    /**
     * Generate a unique SAP Code.
     * 
     * @return string
     */
    protected static function generateUniqueSapCode()
    {
        do {
            $code = 'SAP-' . mt_rand(100000, 999999);
        } while (self::where('sap_code', $code)->exists());

        return $code;
    }

    /**
     * Generate a unique Asset Code.
     * 
     * @return string
     */
    protected static function generateUniqueCode()
    {
        do {
            // AST-YYYYMMDD-XXXX
            $code = 'AST-' . date('Ymd') . '-' . mt_rand(1000, 9999);
        } while (self::where('code', $code)->exists());

        return $code;
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
