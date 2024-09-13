<?php

namespace App\Http\Requests;

class UpdateProductTagsRequest extends CustomeFormRequest{
    
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
            'product_tags' => 'nullable|array',
            'product_tags.*' => 'required|exists:tags,id',
        ];
    }
}