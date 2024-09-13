<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller{
    use ApiResponseTrait;
    
    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        
        $customers = User::where('role', 'customer')->paginate($perPage);
        return $this->successResponse(UserResource::collection($customers), __('messages.retrieve_success'));
    }
    
    public function show(User $customer, Request $request){
        if($customer->role != 'customer'){
            return $this->errorResponse(__('messages.not_found'), 404);
        }

        if ($request->query('include_orders')) {
            $customer->load('orders');
        }
        return $this->successResponse(new UserResource($customer), __('messages.retrieve_success'));
    }
}