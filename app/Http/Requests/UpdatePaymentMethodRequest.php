<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255|in:cash,upi,card,debit_card,credit_card,wallet,netbanking,cod,other',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,deactive',
        ];
    }
}
