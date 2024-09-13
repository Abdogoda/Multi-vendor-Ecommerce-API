<?php

namespace App\Http\Requests;

use App\Rules\StockAvailable;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends CustomeFormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'shipping_address' => 'nullable|string|max:255',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'phone_number' => [
                'required_if:payment_method_id,2', // Required if payment_method_id is 2 (mobile wallet)
                'regex:/^0[0125][0-9]{9}$/'
            ],
            'items' => 'required|array',
            // 'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_id' => [
                'required',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $quantity = $this->input("items.{$index}.quantity");
                    if (!app(StockAvailable::class, ['quantity' => $quantity])->passes($attribute, $value)) {
                        $fail(__('messages.product_out_of_stock')  . $value);
                    }
                },
            ],
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}