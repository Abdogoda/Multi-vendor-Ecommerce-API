<?php

namespace App\Http\Controllers\API\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderItemsResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Services\NewOrderService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller{
    use ApiResponseTrait;

    protected $newOrderService;

    public function __construct(NewOrderService $newOrderService){
        $this->newOrderService = $newOrderService;
    }

    public function index(Request $request){
        $user = User::find(Auth::user()->id);
        if(!$user){
            return $this->errorResponse(__('messages.unauthorized_access'), 500);
        }

        $orders = $user->orders;
        if($request->query('include_items')){
            $orders->load('items');
        }

        return $this->successResponse(OrderResource::collection($orders), __('messages.retrieve_success'), 200);
    }

    public function show(Order $order, Request $request){
        $user = User::find(Auth::user()->id);
        if(!$user || !$order || $user->id != $order->user_id){
            return $this->errorResponse(__('messages.unauthorized_access'), 500);
        }

        if($request->query('include_items')){
            $order->load('items');
        }

        return $this->successResponse(new OrderResource($order), __('messages.retrieve_success'), 200);
    }

    public function store(StoreOrderRequest $request){
        try {
            $user = User::find(Auth::user()->id);
            if(!$user){
                return $this->errorResponse(__('messages.unauthorized_access'), 500);
            }
            
            $result = $this->newOrderService->placeOrder($request, $user);
            
            if ($result['success']) {
                if($result['order']->payment_method_id == 1){ // Cash on Delivery
                    return $this->successResponse(["order_id" => $result['order']], __('messages.order_placed_success'), 200);
                }
                return redirect()->route('customer.payment.checkout', ['order' => $result['order']->id, "phone_number" => $result['phone_number']]);
            }
            return $this->errorResponse($result['message'], 500);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}