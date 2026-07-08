<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return response()->json([
            'message' => 'Payment methods fetched successfully',
            'payment_methods' => $paymentMethods,
        ]);
    }
}
