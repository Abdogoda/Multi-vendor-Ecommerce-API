<?php

namespace App\Http\Requests;

class DeattachVednorPaymentMethodRequest extends CustomeFormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'integration_id' => 'nullable|string|max:255',
            'identifier' => 'nullable|string|max:255'
        ];
    }
}