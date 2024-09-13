<?php

namespace App\Http\Controllers\API\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteProductImageRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreProductImagesRequest;
use App\Http\Requests\UpdateProductTagsRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller {
    use ApiResponseTrait, FileControlTrait;

    public function index(PaginationRequest $request) {
        $vendor = Vendor::find(Auth::user()->vendor->id);
        $perPage = $request->per_page ?? 10;
        
        $products = $vendor->products->orderBy('created_at', 'desc')->paginate($perPage);
        
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

    public function store(StoreProductRequest $request) {
        try {
            DB::beginTransaction();
            $vendor = Vendor::find(Auth::user()->vendor->id);
            if($vendor){
                $data = $request->validated();
                
                $data['vendor_id'] = $vendor->id;
                if ($request->hasFile('main_image')) {
                    $data['main_image'] = $this->uploadFile($request->file('main_image'), 'products');
                }
                $product = Product::create($data);
                
                if ($request->hasFile('product_images')) {
                    foreach ($request->file('product_images') as $image) {
                        $imagePath = $this->uploadFile($image, 'products');
                        $product->images()->create(['image' => $imagePath]);
                    }
                }
                
                if ($request->has('product_tags')) {
                    $product->tags()->attach($request->input('product_tags'));
                }
                
                DB::commit();
                return $this->successResponse(new ProductResource($product), __('messages.create_success'));
            }else{
                return $this->errorResponse(__('messages.unauthorized_access'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, Product $product) {
        try {
            $user = User::find(Auth::user()->id);
            if(!$user || $user->id != $product->vendor->user_id){
                return $this->errorResponse(__('messages.unauthorized_access'), 403);
            }
            
            DB::beginTransaction();
            $data = $request->validated();
            
            if ($request->hasFile('main_image')) {
                $this->deleteFile($product->main_image);
                $data['main_image'] = $this->uploadFile($request->file('main_image'), 'products');
            }
            
            $product->update($data);
            
            DB::commit();
            return $this->successResponse(new ProductResource($product), __('messages.update_success'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(Product $product) {
        try {
            $user = User::find(Auth::user()->id);
            if(!$user || $user->id != $product->vendor->user_id){
                return $this->errorResponse(__('messages.unauthorized_access'), 403);
            }
            
            $this->deleteFile($product->main_image);
            foreach ($product->images as $product_image) {
                $this->deleteFile($product_image->image);
                $product_image->delete();
            }
            
            $product->tags()->detach();

            $product->delete();
            
            return $this->successResponse(__('messages.delete_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function addProductImages(Product $product, StoreProductImagesRequest $request) {
        try {
            $user = User::find(Auth::user()->id);
            if(!$user || $user->id != $product->vendor->user_id){
                return $this->errorResponse(__('messages.unauthorized_access'), 403);
            }
            
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $imagePath = $this->uploadFile($image, 'products');
                    $product->images()->create(['image' => $imagePath]);
                }
            }
            
            return $this->successResponse(__('messages.save_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function deleteProductImages(Product $product, DeleteProductImageRequest $request) {
        try {
            $user = User::find(Auth::user()->id);
            if(!$user || $user->id != $product->vendor->user_id){
                return $this->errorResponse(__('messages.unauthorized_access'), 403);
            }
            
            if ($request->has('product_images')) {
                $product_image_ids = $request->input('product_images');
                
                DB::transaction(function () use ($product_image_ids, $product) {
                    $product_images = ProductImage::whereIn('id', $product_image_ids)
                                                ->where('product_id', $product->id)
                                                ->get();
                
                    foreach ($product_images as $product_image) {
                        $this->deleteFile($product_image->image);
                        $product_image->delete();
                    }
                });
            }
            
            return $this->successResponse(__('messages.delete_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateProductTags(Product $product, UpdateProductTagsRequest $request) {
        try {
            $user = User::find(Auth::user()->id);
            if(!$user || $user->id != $product->vendor->user_id){
                return $this->errorResponse(__('messages.unauthorized_access'), 403);
            }
            
            $product->tags()->sync($request->input('product_tags'));
            
            return $this->successResponse(__('messages.delete_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}