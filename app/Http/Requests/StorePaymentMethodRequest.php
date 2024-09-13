<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends CustomeFormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'name' => 'required|string|min:3|max:50|unique:payment_methods,name',
            'description' => 'nullable|max:500|string',
            'status' => 'nullable|boolean'
        ];
    }
}