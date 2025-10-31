<?php

namespace App\Http\Requests;

class UpdateProductRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'base_price' => 'nullable|integer|min:0',
            'status' => 'nullable|in:publish,hide',
        ];
    }
}
