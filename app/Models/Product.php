<?php

namespace App\Models;

use App\Support\ApiImageUploader;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'gender', 'price', 'discount_price', 'quantity', 'sku', 'barcode',
        'weight', 'size', 'material', 'color', 'slug', 'description',
        'image', 'video', 'status', 'sub_category_id', 'shop_id', 'user_id'
    ];

    protected $appends = [
        'image_urls',
        'image_url',
    ];

    public function imagePaths(): array
    {
        return self::parseImageValue($this->attributes['image'] ?? null);
    }

    public function imageUrls(): array
    {
        return ApiImageUploader::urls($this->imagePaths());
    }

    public function firstImagePath(): ?string
    {
        return $this->imagePaths()[0] ?? null;
    }

    public function firstImageUrl(): ?string
    {
        return $this->imageUrls()[0] ?? null;
    }

    public function getImageUrlsAttribute(): array
    {
        return $this->imageUrls();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->firstImageUrl();
    }

    public static function parseImageValue(mixed $value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        if (! is_string($value)) {
            return [];
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return [$value];
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchasedProducts()
    {
        return $this->hasMany(PurchasedProduct::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
