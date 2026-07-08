<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasedProduct;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RoleAccess
{
    public static function user(): ?User
    {
        return Auth::user();
    }

    public static function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function products(): Builder
    {
        $query = Product::query();

        if (!self::isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function shops(): Builder
    {
        $query = Shop::query();

        if (!self::isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function merchantOrders(): Builder
    {
        $query = Purchase::query();

        if (!self::isAdmin()) {
            $query->whereHas('purchasedProducts.product', fn ($q) => $q->where('user_id', Auth::id()));
        }

        return $query;
    }

    public static function merchantSales(): Builder
    {
        $query = PurchasedProduct::query();

        if (!self::isAdmin()) {
            $query->whereHas('product', fn ($q) => $q->where('user_id', Auth::id()));
        }

        return $query;
    }

    public static function authorizeProduct(Product $product): void
    {
        if (!self::isAdmin() && (int) $product->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }
    }

    public static function authorizeShop(Shop $shop): void
    {
        if (!self::isAdmin() && (int) $shop->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this shop.');
        }
    }
}
