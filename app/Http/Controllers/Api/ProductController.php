<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Product;
use App\Models\Shop;
use App\Support\ApiImageUploader;
use App\Support\RoleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function products()
    {
        $products = Product::with(['user', 'shop', 'subCategory'])->get();

        return response()->json([
            'message' => 'Products API',
            'products' => $products,
        ]);
    }

    public function myProducts()
    {
        $products = RoleAccess::products()->with(['user', 'shop', 'subCategory'])->get();

        return response()->json([
            'message' => 'My products fetched successfully',
            'products' => $products,
        ]);
    }

    public function productsDetails($id)
    {
        $product = Product::with(['user', 'shop', 'subCategory'])->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
                'product' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Product details',
            'product' => $product,
        ]);
    }

    public function createProduct(StoreProductRequest $request)
    {
        if (!RoleAccess::isAdmin()) {
            $shop = Shop::where('id', $request->shop_id)->where('user_id', Auth::id())->first();
            if (!$shop) {
                return response()->json(['message' => 'Invalid shop selected.'], 422);
            }
        }

        $validated = $request->validated();
        unset($validated['category_id']);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->name);

        $imagePaths = ApiImageUploader::storeMany($request, 'image', 'products');
        if ($imagePaths !== []) {
            $validated['image'] = json_encode($imagePaths);
        } else {
            unset($validated['image']);
        }

        $product = Product::create($validated);
        $product->load(['user', 'shop', 'subCategory']);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    public function updateProduct(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        RoleAccess::authorizeProduct($product);

        if (!RoleAccess::isAdmin() && $request->filled('shop_id')) {
            $shop = Shop::where('id', $request->shop_id)->where('user_id', Auth::id())->first();
            if (!$shop) {
                return response()->json(['message' => 'Invalid shop selected.'], 422);
            }
        }

        $validated = $request->validated();
        unset($validated['category_id']);

        if ($request->filled('name')) {
            $validated['slug'] = Str::slug($request->name);
        }

        $imagePaths = ApiImageUploader::storeMany($request, 'image', 'products');
        if ($imagePaths !== []) {
            $validated['image'] = json_encode($imagePaths);
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->fresh(['user', 'shop', 'subCategory']),
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        RoleAccess::authorizeProduct($product);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
