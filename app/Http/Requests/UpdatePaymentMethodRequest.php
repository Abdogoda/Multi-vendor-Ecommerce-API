<?php

namespace App\Http\Requests;

class UpdatePaymentMethodRequest extends CustomeFormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'name' => 'sometimes|string|min:3|max:50|unique:payment_methods,name,'.$this->paymentMethod->id,
            'description' => 'nullable|max:500|string',
            'status' => 'nullable|boolean'
        ];
    }
}