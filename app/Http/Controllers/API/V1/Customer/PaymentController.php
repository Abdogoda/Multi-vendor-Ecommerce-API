<?php

namespace App\Http\Controllers\API\V1\Customer;

use App\Http\Controllers\Controller;
use App\Mail\PlaceOrderPayment;
use App\Models\Order;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller{  
    use ApiResponseTrait;
    
    public function checkout(Request $request, Order $order) {
        $integration_id = env('PAYMOB_INTEGRATION_ID');
        $identifier = env("PAYMOB_IFRAME_ID");
        if($order->payment_method_id == 2){ // Mobile wallet
            $integration_id = env('PAYMOB_MOBILE_WALLET');
            $identifier = $request->phone_number;
            $identifier = "01010101010";
        }elseif($order->payment_method_id == 3){ // Visa Card
            $integration_id = env('PAYMOB_INTEGRATION_ID');
            $identifier = env("PAYMOB_IFRAME_ID");
        }
        
        $token = $this->getToken();
        $created_order = $this->registerPaymobOrder($token, $order);
        $paymentToken = $this->generatePaymentKey($created_order->id, $order, $integration_id, $token);

        return $this->paymentMethod($order->payment_method_id, $identifier, $paymentToken);
    }

    public function getToken() {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);
        return $response->object()->token;
    }

    public function registerPaymobOrder($token, $order) {
        $items=[];
        foreach ($order->items as $item) {
            $items[] = [
                "name"=> $item->name_en,
                "amount_cents"=> $item->pivot->price * 100,
                "description"=> $item->description,
                "quantity"=> $item->pivot->quantity
            ];
        }

        $data = [
            "auth_token" =>   $token,
            "delivery_needed" =>"false",
            "amount_cents"=> $order->total_amount * 100,
            "currency"=> "EGP",
            "items"=> $items,

        ];
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->object();
    }

    public function generatePaymentKey($created_order_id, $order, $integration_id, $token){
        $user = $order->user;
        $billingData = [
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "phone_number" => $user->phone ?? "NA",
            "apartment" => 'NA',
            "floor" => 'NA',
            "street" => "NA",
            "building" => "NA",
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "NA",
            "country" => "NA",
            "state" => "NA"
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => $order->total_amount * 100,
            "expiration" => 3600,
            "order_id" => $created_order_id,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $integration_id
        ];
        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);
        return $response->object()->token;
    }

    public function paymentMethod($payment_method_id, $identifier, $payment_token){
        if ($payment_method_id == 2) { // wallet
            $walletData = [
                'source' => [
                    'identifier' => $identifier,
                    'subtype' => 'WALLET',
                ],
                'payment_token' => $payment_token,
            ];
            $response = Http::withHeaders([ 'content-type' => 'application/json'])->post('https://accept.paymob.com/api/acceptance/payments/pay',$walletData);
            if($response->json()['pending'] == true || $response->json()['success'] == true){
                return redirect($response->json()['redirect_url']);
            }else{
                return redirect($response->json()['redirection_url']);
            }
        }else {
            return Redirect::away('https://accept.paymob.com/api/acceptance/iframes/'.$identifier.'?payment_token='.$payment_token);
        }
    }

    public function callback(Request $request){
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if(in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ( $hased == $hmac) {
            $user = Auth::user()->id;
            $todayDate = Carbon::now();
            $order = Order::where('user_id',$user)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();

            $status = $data['success'];
            $pending = $data['pending'];
            if ( $status == "true"  && $order) {
                $order->update(['payment_status' => 'paid']);
                try {
                    Mail::to($user->email)->send(new PlaceOrderPayment($order));
                }catch(\Exception $e){
                    $this->errorResponse(__($e->getMessage()));
                }
                return $this->successResponse($order, __('messages.order_placed_success'));
                
            }else {
                return $this->errorResponse("Something Went Wrong Please Try Again");
            }
            
        }else {
            return $this->errorResponse("Something Went Wrong Please Try Again");
        }
    }
}