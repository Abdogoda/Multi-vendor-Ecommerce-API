<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImagesRequest extends CustomeFormRequest{
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
            'product_images' => 'required|array',
            'product_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];
    }
}