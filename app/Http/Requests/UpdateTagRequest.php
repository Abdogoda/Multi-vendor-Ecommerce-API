<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest{

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
            'name_en' => 'required|string|max:255|unique:tags,name_en,'.$this->tag->id,
            'name_ar' => 'required|string|max:255|unique:tags,name_ar,'.$this->tag->id,
        ];
    }
}