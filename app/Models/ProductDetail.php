<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_type_id',
        'duration',
        'price',
        'notes',
        'is_deleted',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_deleted' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    // Format price to Rupiah
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
