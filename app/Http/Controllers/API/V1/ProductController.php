<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Illuminate\Http\Request;

class ProductController extends Controller {
    use ApiResponseTrait, FileControlTrait;

    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        
        $products = Product::orderBy('created_at', 'desc')->paginate($perPage);
        
        return $this->successResponse(ProductResource::collection($products), __('messages.retrieve_success'));
    }
    public function show(Product $product, Request $request) {
        if ($request->query('include_category')) {
            $product->load('category');
        }

        if ($request->query('include_tags')) {
            $product->load('tags');
        }

        if ($request->query('include_vendor')) {
            $product->load('vendor');
        }

        if ($request->query('include_reviews')) {
            $product->load('reviews');
        }

        if ($request->query('include_images')) {
            $product->load('images');
        }
        
        return $this->successResponse(new ProductResource($product), __('messages.retrieve_success'));
    }

}