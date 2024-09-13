<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends CustomeFormRequest{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool{
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'name_en' => 'required|string|max:255|unique:products,name_en,NULL,id,vendor_id,'.Auth::user()->vendor->id,
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'product_tags' => 'nullable|array',
            'product_tags.*' => 'required|exists:tags,id',
            'product_images' => 'nullable|array',
            'product_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];
    }
}