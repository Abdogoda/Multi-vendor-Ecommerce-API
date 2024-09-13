<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;

class CategoryController extends Controller {
    use ApiResponseTrait, FileControlTrait;

    public function destroy(Category $category) {
        try {
            $this->deleteFile($category->icon);
            $this->deleteFile($category->image);
            $category->delete();
            
            return $this->successResponse(__('messages.delete_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}