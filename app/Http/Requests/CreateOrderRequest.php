<?php

namespace App\Http\Requests;

class CreateOrderRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_ids' => ['required', 'array', 'min:1'],
            'cart_ids.*' => ['integer', 'exists:carts,id'],
            'payment_method' => ['required', 'string', 'max:100'],
        ];
    }
}

