<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomeFormRequest;

class SendOTPRequest extends CustomeFormRequest{
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
            'email' => 'required|string|email|exists:users,email'
        ];
    }
}