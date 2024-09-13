<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    use ApiResponseTrait, FileControlTrait;

    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        
        $categories = Category::paginate($perPage);
        return $this->successResponse(CategoryResource::collection($categories), __('messages.retrieve_success'));
    }

    public function show(Category $category, Request $request) {
        if ($request->query('include_products')) {
            $category->load('products');
        }

        if ($request->query('include_children')) {
            $category->load('children');
        }
        
        return $this->successResponse(new CategoryResource($category), __('messages.retrieve_success'));
    }
}