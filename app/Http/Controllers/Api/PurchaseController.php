<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\Models\PurchasedProduct;
use App\Models\UserAddress;
use App\Support\RoleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::where('user_id', Auth::id())
            ->with([
                'address',
                'paymentMethod',
                'purchasedProducts.product.shop',
                'purchasedProducts.product.subCategory',
            ])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Purchases fetched successfully',
            'purchases' => $purchases,
        ]);
    }

    public function merchantOrders()
    {
        $orders = RoleAccess::merchantOrders()
            ->with([
                'user',
                'address',
                'paymentMethod',
                'purchasedProducts.product.shop',
                'purchasedProducts.product.subCategory',
            ])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Merchant orders fetched successfully',
            'orders' => $orders,
        ]);
    }

    public function store(StorePurchaseRequest $request)
    {
        $address = UserAddress::where('user_id', Auth::id())
            ->where('id', $request->address_id)
            ->first();

        if (!$address) {
            return response()->json([
                'message' => 'Address not found for this user.',
            ], 422);
        }

        $products = $request->products;
        $totalPrice = collect($products)->sum('price');
        $totalPaidPrice = collect($products)->sum('paid_price');

        $purchase = DB::transaction(function () use ($request, $totalPrice, $totalPaidPrice, $products) {
            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'address_id' => $request->address_id,
                'payment_method_id' => $request->payment_method_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total_price' => $totalPrice,
                'total_paid_price' => $totalPaidPrice,
            ]);

            foreach ($products as $item) {
                PurchasedProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'paid_price' => $item['paid_price'],
                ]);
            }

            return $purchase;
        });

        $purchase->load([
            'address',
            'paymentMethod',
            'purchasedProducts.product.shop',
            'purchasedProducts.product.subCategory',
        ]);

        return response()->json([
            'message' => 'Purchase saved successfully',
            'purchase' => $purchase,
        ], 201);
    }
}
