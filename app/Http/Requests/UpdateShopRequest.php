<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => [
                'nullable',
                Rule::when($this->hasFile('image'), ['file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240']),
            ],
            'shop_number' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'zip' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'status' => 'sometimes|required|string|in:active,deactive',
        ];
    }
}
