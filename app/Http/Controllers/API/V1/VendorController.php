<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VendorController extends Controller{
    use ApiResponseTrait;
    
    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        $vendors = Vendor::orderBy('created_at', 'desc')->paginate($perPage);
        
        if ($request->query('include_products')) {
            $vendors->load('products');
        }
        if ($request->query('include_user')) {
            $vendors->load('user');
        }

        return $this->successResponse(VendorResource::collection($vendors), __('messages.retrieve_success'));
    }
    
    public function show(Vendor $vendor, Request $request){
        if ($request->query('include_products')) {
            $vendor->load('products');
        }
        if ($request->query('include_user')) {
            $vendor->load('user');
        }
        return $this->successResponse(new VendorResource($vendor), __('messages.retrieve_success'));
    }
    
    
    public function showProducts(Vendor $vendor, PaginationRequest $request){
        $perPage = $request->per_page ?? 10;
        $products = $vendor->products()->paginate($perPage);

        return $this->successResponse( ProductResource::collection($products), __('messages.retrieve_success'));
    }
    
    public function showPaymentMethods(Vendor $vendor){
        $payment_methods = $vendor->paymentMethods;

        return $this->successResponse( $payment_methods, __('messages.retrieve_success'));
    }
}