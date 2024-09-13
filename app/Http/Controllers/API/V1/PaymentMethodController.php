<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller{
    use ApiResponseTrait;
    
    public function index(Request $request){
        if($request->query('include_deactive')){
            $payment_methods = PaymentMethod::orderBy('id', 'desc')->get();
        }else{
            $payment_methods = PaymentMethod::where('status', true)->orderBy('id', 'desc')->get();
        }
        return $this->successResponse(PaymentMethodResource::collection($payment_methods), __('messages.retrieve_success'));
    }

    public function show(PaymentMethod $paymentMethod){
        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('messages.retrieve_success'));
    }
}