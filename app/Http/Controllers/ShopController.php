<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Support\RoleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $shops = RoleAccess::shops()->paginate(10);

        return view('content.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('content.shops.create');
    }

    public function store(StoreShopRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->name);
        $validated['latitude'] = random_int(-9000000, 9000000) / 100000;
        $validated['longitude'] = random_int(-18000000, 18000000) / 100000;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('shops', 'public');
        }

        Shop::create($validated);

        return redirect()->route('shops.index')->with('success', 'Shop added successfully!');
    }

    public function show(Shop $shop)
    {
        RoleAccess::authorizeShop($shop);
    }

    public function edit(Shop $shop)
    {
        RoleAccess::authorizeShop($shop);

        return view('content.shops.edit', compact('shop'));
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        RoleAccess::authorizeShop($shop);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('shops', 'public');
        }

        $shop->update($validated);

        return redirect()->route('shops.index')->with('success', 'Shop updated successfully!');
    }

    public function destroy(Shop $shop)
    {
        RoleAccess::authorizeShop($shop);
        $shop->delete();

        return redirect()->route('shops.index')->with('success', 'Shop deleted successfully!');
    }
}
