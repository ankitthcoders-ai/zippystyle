<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'price',
        'paid_price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'paid_price' => 'decimal:2',
        ];
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
