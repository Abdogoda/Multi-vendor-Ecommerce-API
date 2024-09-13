<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Traits\ApiResponseTrait;

class PaymentMethodController extends Controller{
    use ApiResponseTrait;
    
    Public function store(StorePaymentMethodRequest $request){
        $paymentMethod = PaymentMethod::create($request->validated());
        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('messages.create_success'));
    }

    Public function update(PaymentMethod $paymentMethod, UpdatePaymentMethodRequest $request){
        $paymentMethod->update($request->validated());
        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('messages.update_success'));
    }

    Public function destroy(PaymentMethod $paymentMethod){
        $paymentMethod->delete();
        return $this->successResponse(null, __('messages.delete_success'));
    }

}