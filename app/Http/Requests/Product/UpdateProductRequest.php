<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'uuid', 'exists:subcategories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'secondary_images' => ['nullable', 'array', 'max:20'],
            'secondary_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['uuid', 'exists:product_images,id'],
        ];
    }
}
