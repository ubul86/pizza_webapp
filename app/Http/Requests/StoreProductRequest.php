<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'description' => 'string',
            'price' => 'numeric',
            'category_id' => 'integer|required|exists:categories,id'
        ];
    }
}
