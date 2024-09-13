<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends CustomeFormRequest{

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
            'name_en' => 'sometimes|string|max:255|unique:categories,name_en,' . $this->category->id,
            'name_ar' => 'sometimes|string|max:255|unique:categories,name_ar,' . $this->category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,ico|max:5120',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg,ico|max:5120',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}