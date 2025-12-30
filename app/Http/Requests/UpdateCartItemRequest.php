<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:99',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Please specify a quantity.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Maximum quantity is 99.',
        ];
    }
}
