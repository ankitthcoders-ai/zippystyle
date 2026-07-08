<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Addresses fetched successfully',
            'addresses' => $addresses,
        ]);
    }

    public function show($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->find($id);

        if (!$address) {
            return response()->json([
                'message' => 'Address not found',
                'address' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Address fetched successfully',
            'address' => $address,
        ]);
    }

    public function store(StoreUserAddressRequest $request)
    {
        if ($request->boolean('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address = UserAddress::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Address saved successfully',
            'address' => $address,
        ], 201);
    }

    public function update(StoreUserAddressRequest $request, $id)
    {
        $address = UserAddress::where('user_id', Auth::id())->find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        if ($request->boolean('is_default')) {
            UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($request->validated());

        return response()->json([
            'message' => 'Address updated successfully',
            'address' => $address->fresh(),
        ]);
    }

    public function destroy($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        if ($address->purchases()->exists()) {
            return response()->json([
                'message' => 'This address is used in an order and cannot be deleted.',
            ], 422);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextDefault = UserAddress::where('user_id', Auth::id())->latest()->first();
            $nextDefault?->update(['is_default' => true]);
        }

        return response()->json([
            'message' => 'Address deleted successfully',
        ]);
    }
}
