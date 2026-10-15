<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock',
        'status',
        'featured',
        'bestseller',
        'new_arrival',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'featured' => 'boolean',
        'bestseller' => 'boolean',
        'new_arrival' => 'boolean',
    ];

    protected $appends = [
        'effective_price',
        'discount_percent',
        'primary_image_url',
        'hover_image_url',
        'is_in_stock',
    ];

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeBestseller(Builder $query): Builder
    {
        return $query->where('bestseller', true);
    }

    public function scopeNewArrival(Builder $query): Builder
    {
        return $query->where('new_arrival', true);
    }

    // Accessors
    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price !== null && (float) $this->sale_price > 0 && (float) $this->sale_price < (float) $this->price) {
            return (float) $this->sale_price;
        }

        return (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->sale_price !== null && (float) $this->sale_price > 0 && (float) $this->sale_price < (float) $this->price) {
            return (int) round((((float) $this->price - (float) $this->sale_price) / (float) $this->price) * 100);
        }

        return 0;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary) {
            return $primary->url;
        }

        return 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=800&q=80';
    }

    public function getHoverImageUrlAttribute(): string
    {
        $images = $this->images;
        if ($images->count() > 1) {
            return $images->get(1)->url;
        }

        return $this->primary_image_url;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
