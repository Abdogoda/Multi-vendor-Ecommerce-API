<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVednorProfile extends CustomeFormRequest{

    public function authorize(): bool{
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        $user = User::find(Auth::user()->id);
        return [
            'shop_name_en' => 'sometimes|string|max:255|unique:vendors,shop_name_en,'.$user->vendor_id,
            'shop_name_ar' => 'nullable|string|max:255|unique:vardors,shop_name_ar,'.$user->vendor_id,
            'shop_address' => 'nullable|string',
            'shop_phone' => 'nullable|min_digits:11|max_digits:11|unique:vendors,shop_phone',
            'shop_email' => 'nullable|string|email|max:255|unique:users,shop_email',
            'shop_website' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'description' => 'nullable|string',
        ];
    }
}