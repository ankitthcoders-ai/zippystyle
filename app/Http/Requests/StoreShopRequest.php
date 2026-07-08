<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize URL fields so users can enter values without a scheme.
     */
    protected function prepareForValidation(): void
    {
        $urlFields = ['website', 'facebook', 'twitter', 'instagram', 'linkedin'];
        $updates = [];

        foreach ($urlFields as $field) {
            $value = trim((string) $this->input($field));

            if ($value !== '' && ! preg_match('#^https?://#i', $value)) {
                $updates[$field] = 'https://' . $value;
            }
        }

        if ($updates !== []) {
            $this->merge($updates);
        }
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
            'description' => 'nullable|string',
            'image' => [
                'nullable',
                Rule::when($this->hasFile('image'), ['file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240']),
            ],
            'shop_number' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'status' => 'required|string|in:active,deactive'
        ];
    }
}
