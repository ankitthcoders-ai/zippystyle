<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);

        return view('content.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $types = $this->paymentTypes();

        return view('content.payment-methods.create', compact('types'));
    }

    public function store(StorePaymentMethodRequest $request)
    {
        PaymentMethod::create([
            ...$request->validated(),
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method added successfully!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $types = $this->paymentTypes();

        return view('content.payment-methods.edit', compact('paymentMethod', 'types'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->purchases()->exists()) {
            return redirect()->route('payment-methods.index')
                ->with('error', 'This payment method is used in orders. Set status to deactive instead of deleting.');
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted successfully!');
    }

    private function paymentTypes(): array
    {
        return [
            'cash' => 'Cash',
            'cod' => 'Cash on Delivery',
            'upi' => 'UPI',
            'card' => 'Card',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'wallet' => 'Wallet',
            'netbanking' => 'Net Banking',
            'other' => 'Other',
        ];
    }
}
