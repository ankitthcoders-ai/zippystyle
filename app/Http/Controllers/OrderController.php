<?php

namespace App\Http\Controllers;

use App\Support\RoleAccess;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = RoleAccess::merchantOrders()
            ->with([
                'user',
                'address',
                'paymentMethod',
                'purchasedProducts.product',
            ])
            ->latest()
            ->paginate(10);

        return view('content.orders.index', compact('orders'));
    }
}
