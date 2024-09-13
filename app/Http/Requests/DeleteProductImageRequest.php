<?php

namespace App\Http\Requests;


class DeleteProductImageRequest extends CustomeFormRequest{

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
            'product_images.*' => 'required|exists:product_images,id',
        ];
    }
}