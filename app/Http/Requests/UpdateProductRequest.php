<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|in:male,female',
            'price' => 'sometimes|required|numeric',
            'discount_price' => 'nullable|numeric',
            'quantity' => 'sometimes|required|integer',
            'sku' => 'sometimes|required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'size' => 'sometimes|required|string|max:255',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'image.*' => [
                'nullable',
                Rule::when(
                    fn () => $this->hasFile('image') || is_array($this->file('image')),
                    ['file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240']
                ),
            ],
            'video' => 'nullable|url',
            'category_id' => 'sometimes|required|exists:categories,id',
            'sub_category_id' => 'sometimes|required|exists:sub_categories,id',
            'shop_id' => 'sometimes|required|exists:shops,id',
            'status' => 'sometimes|required|string|in:active,deactive',
        ];
    }
}
