<?php

namespace App\Http\Controllers\API\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachVednorPaymentMethodRequest;
use App\Http\Requests\DeattachVednorPaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller{

    use ApiResponseTrait;

    public function index(){
        $vendor = User::find(Auth::user()->id)->vendor;
        $payment_methods = $vendor->paymentMethods;
        return $this->successResponse($payment_methods, __('messages.retrieve_success'));
    }

    public function store(AttachVednorPaymentMethodRequest $request){
        $vendor = User::find(Auth::user()->id)->vendor;
        $additionalAttributes = [
            'integration_id' => $request->integration_id ?? null,
            'identifier' => $request->identifier ?? null
        ];
        
        $vendor->paymentMethods()->attach($request->payment_method_id, $additionalAttributes);
        return $this->successResponse(null,  __('messages.save_success'));
    }

    public function update(PaymentMethod $paymentMethod, DeattachVednorPaymentMethodRequest $request){
        $vendor = User::find(Auth::user()->id)->vendor;
        $additionalAttributes = [
            'integration_id' => $request->integration_id ?? null,
            'identifier' => $request->identifier ?? null
        ];
        
        if ($vendor->paymentMethods()->where('payment_method_id', $paymentMethod->id)->exists()) {
            $vendor->paymentMethods()->updateExistingPivot($paymentMethod->id, $additionalAttributes);
            
            return $this->successResponse(null,  __('messages.update_success'));
        } else {
            return $this->errorResponse(__('messages.not_found'), 404);
        }
    }
    
    public function destroy(PaymentMethod $paymentMethod){
        $vendor = User::find(Auth::user()->id)->vendor;
        if ($vendor->paymentMethods()->where('payment_method_id', $paymentMethod->id)->exists()) {
            $vendor->paymentMethods()->detach($paymentMethod->id);
    
            return $this->successResponse([], __('messages.delete_success'));
        } else {
            return $this->errorResponse(__('messages.not_found'), 404);
        }
    }

}