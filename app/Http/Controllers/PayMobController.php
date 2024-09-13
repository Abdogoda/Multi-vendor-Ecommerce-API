<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class PayMobController extends Controller{
    public function credit(Request $request) {
        $integration_id = env('PAYMOB_INTEGRATION_ID');
        $identifier = env("PAYMOB_IFRAME_ID");
        if($request->payment_method == 'mobile_wallet'){
            $integration_id = env('PAYMOB_MOBILE_WALLET');
            $identifier = '01010101010';
        }
        
        $tokens = $this->getToken();
        $order = $this->createOrder($tokens);
        $paymentToken = $this->getPaymentToken($order, $integration_id, $tokens);
        return $this->paymentMethod($request->payment_method, $identifier, $paymentToken);
    }

    public function getToken() {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);
        return $response->object()->token;
    }

    public function createOrder($tokens) {
        $total = 1000;
        $items = [
            [ "name"=> "ASC1515",
                "amount_cents"=> "500000",
                "description"=> "Smart Watch",
                "quantity"=> "1"
            ],
            [
                "name"=> "ERT6565",
                "amount_cents"=> "200000",
                "description"=> "Power Bank",
                "quantity"=> "1"
            ]
        ];

        $data = [
            "auth_token" =>   $tokens,
            "delivery_needed" =>"false",
            "amount_cents"=> $total*100,
            "currency"=> "EGP",
            "items"=> $items,

        ];
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->object();
    }

    public function getPaymentToken($order, $integration_id, $token){
        $billingData = [
            "apartment" => '45', //example $dataa->appartment
            "email" => "newmail@gmai.com", //example $dataa->email
            "floor" => '5',
            "first_name" => 'maher',
            "street" => "NA",
            "building" => "NA",
            "phone_number" => '0123456789',
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "cairo",
            "country" => "NA",
            "last_name" => "fared",
            "state" => "NA"
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => 100*100,
            "expiration" => 3600,
            "order_id" => $order->id, // this order id created by paymob
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $integration_id
        ];
        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);
        return $response->object()->token;
    }

    public function paymentMethod($payment_method, $identifier, $payment_token){
        if ($payment_method == 'mobile_wallet') {
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
            //this below data used to get the last order created by the customer and check if its exists to 
            // $todayDate = Carbon::now();
            // $datas = Order::where('user_id',Auth::user()->id)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();
            $status = $data['success'];
            // $pending = $data['pending'];

            if ( $status == "true" ) {

                //here we checked that the success payment is true and we updated the data base and empty the cart and redirct the customer to thankyou page

                // Cart::where('user_id',auth()->user()->id)->delete();
                // $datas->update([
                //     'payment_id' => $data['id'],
                //     'payment_status' => "Compeleted"
                // ]);
                // try {
                //     $order = Order::find($datas->id);
                //     Mail::to('maherfared@gmail.com')->send(new PlaceOrderMailable($order));
                // }catch(\Exception $e){
                // }
                return "Thank you for your order";
                
            }else {
                // $datas->update([
                //     'payment_id' => $data['id'],
                //     'payment_status' => "Failed"
                // ]);
                return "Something Went Wrong Please Try Again";
            }
            
        }else {
            return "Something Went Wrong Please Try Again";
        }
    }
}