<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Shop;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Support\RoleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = RoleAccess::products()->with('subCategory', 'shop')->paginate(10);

        return view('content.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::where('status', 'active')->get();
        $shops = RoleAccess::shops()->where('status', 'active')->get();

        return view('content.form-layout.form-layouts-vertical', compact('categories', 'subCategories', 'shops'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->name);

        if (!RoleAccess::isAdmin()) {
            $shop = Shop::where('id', $request->shop_id)->where('user_id', Auth::id())->first();
            if (!$shop) {
                return back()->withInput()->with('error', 'Invalid shop selected.');
            }
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function show(Product $product)
    {
        RoleAccess::authorizeProduct($product);
    }

    public function edit(Product $product)
    {
        RoleAccess::authorizeProduct($product);
        $product->load('subCategory');

        $categories = Category::all();
        $subCategories = SubCategory::where('status', 'active')->get();
        $shops = RoleAccess::shops()->where('status', 'active')->get();

        return view('content.products.edit', compact('product', 'categories', 'subCategories', 'shops'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        RoleAccess::authorizeProduct($product);

        $validated = $request->validated();
        unset($validated['category_id']);

        if ($request->filled('name')) {
            $validated['slug'] = Str::slug($request->name);
        }

        if (!RoleAccess::isAdmin() && $request->filled('shop_id')) {
            $shop = Shop::where('id', $request->shop_id)->where('user_id', Auth::id())->first();
            if (!$shop) {
                return back()->withInput()->with('error', 'Invalid shop selected.');
            }
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        RoleAccess::authorizeProduct($product);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
