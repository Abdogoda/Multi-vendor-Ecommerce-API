<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomeFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends CustomeFormRequest{

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
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6'
        ];
    }
}