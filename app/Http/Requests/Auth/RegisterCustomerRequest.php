<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomeFormRequest;

class RegisterCustomerRequest extends CustomeFormRequest{

    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|min_digits:11|max_digits:11|unique:users,phone',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ];
    }
}