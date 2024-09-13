<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AttachVednorPaymentMethodRequest extends CustomeFormRequest{

    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        $vendor = User::find(Auth::user()->id)->vendor;
        return [
            'payment_method_id' => ['required', 'exists:payment_methods,id', Rule::unique('vendor_payment_methods')->where(function ($query) use ($vendor) {
                return $query->where('vendor_id', $vendor->id);
            })],
            'integration_id' => 'nullable|string|max:255',
            'identifier' => 'nullable|string|max:255'
        ];
    }
}