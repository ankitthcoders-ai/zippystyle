<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use App\Support\ApiImageUploader;
use App\Support\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function shops()
    {
        $shops = Shop::all()->map(fn ($shop) => $this->withImageUrl($shop));

        return response()->json([
            'message' => 'Shops API',
            'shops' => $shops,
        ]);
    }

    public function myShops()
    {
        $shops = RoleAccess::shops()->get()->map(fn ($shop) => $this->withImageUrl($shop));

        return response()->json([
            'message' => 'My shops fetched successfully',
            'shops' => $shops,
        ]);
    }

    public function products(Request $request, $id)
    {
        $shop = Shop::find($id);

        if (!$shop) {
            return response()->json(['message' => 'Shop not found', 'products' => []], 404);
        }

        $products = $shop->products()->with(['user', 'shop', 'subCategory'])->get();

        return response()->json([
            'message' => 'Products API',
            'products' => $products,
        ]);
    }

    public function createShop(StoreShopRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->name);
        $validated['latitude'] = random_int(-9000000, 9000000) / 100000;
        $validated['longitude'] = random_int(-18000000, 18000000) / 100000;

        if ($imagePath = ApiImageUploader::store($request, 'image', 'shops')) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $shop = Shop::create($validated);

        return response()->json([
            'message' => 'Shop created successfully',
            'shop' => $this->withImageUrl($shop),
        ], 201);
    }

    public function updateShop(UpdateShopRequest $request, $id)
    {
        $shop = Shop::find($id);

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        RoleAccess::authorizeShop($shop);

        $validated = $request->validated();

        if ($request->filled('name')) {
            $validated['slug'] = Str::slug($request->name);
        }

        if ($imagePath = ApiImageUploader::store($request, 'image', 'shops')) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $shop->update($validated);

        return response()->json([
            'message' => 'Shop updated successfully',
            'shop' => $this->withImageUrl($shop->fresh()),
        ]);
    }

    public function deleteShop($id)
    {
        $shop = Shop::find($id);

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        RoleAccess::authorizeShop($shop);
        $shop->delete();

        return response()->json([
            'message' => 'Shop deleted successfully',
        ]);
    }

    private function withImageUrl(Shop $shop): Shop
    {
        $shop->setAttribute('image_url', ApiImageUploader::url($shop->image));

        return $shop;
    }
}
