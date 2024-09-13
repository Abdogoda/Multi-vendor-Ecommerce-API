<?php

namespace App\Http\Controllers\API\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;

class CategoryController extends Controller {
    use ApiResponseTrait, FileControlTrait;
    public function store(StoreCategoryRequest $request) {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('icon')) {
                $data['icon'] = $this->uploadFile($request->file('icon'), 'categories/icons');
            }
            
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFile($request->file('image'), 'categories/images');
            }
            $category = Category::create($data);
            
            return $this->successResponse(new CategoryResource($category), __('messages.create_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category) {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('icon')) {
                $this->deleteFile($category->icon);
                $data['icon'] = $this->uploadFile($request->file('icon'), 'categories/icons');
            }
            
            if ($request->hasFile('image')) {
                $this->deleteFile($category->image);
                $data['image'] = $this->uploadFile($request->file('image'), 'categories/images');
            }
            
            $category->update($data);
            
            return $this->successResponse(new CategoryResource($category), __('messages.update_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}