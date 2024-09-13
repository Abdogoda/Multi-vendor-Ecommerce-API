<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomeFormRequest;

class UpdateProfileRequest extends CustomeFormRequest{
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
        $user = auth()->user();
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|min_digits:11|max_digits:11|unique:users,phone,'.$user->id,
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ];
    }
}