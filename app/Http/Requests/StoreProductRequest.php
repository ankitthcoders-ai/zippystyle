<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'sku' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'size' => 'required|string|max:255',
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
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'shop_id' => 'required|exists:shops,id',
            'status' => 'required|string|in:active,deactive'
        ];
    }
}
