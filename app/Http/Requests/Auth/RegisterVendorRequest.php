<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomeFormRequest;

class RegisterVendorRequest extends CustomeFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|min_digits:11|max_digits:11|unique:users,phone',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            

            'shop_name_en' => 'required|string|max:255|unique:vendors,shop_name_en',
            'shop_name_ar' => 'nullable|string|max:255|unique:vardors,shop_name_ar',
            'shop_address' => 'nullable|string',
            'shop_phone' => 'nullable|min_digits:11|max_digits:11|unique:vendors,shop_phone',
            'shop_email' => 'nullable|string|email|max:255|unique:users,shop_email',
            'shop_website' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'description' => 'nullable|string',
        ];
    }
}