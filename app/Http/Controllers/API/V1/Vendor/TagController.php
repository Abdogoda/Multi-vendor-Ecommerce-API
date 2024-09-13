<?php

namespace App\Http\Controllers\API\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponseTrait;
use Exception;

class TagController extends Controller{
    
    use ApiResponseTrait;

    public function store(StoreTagRequest $request) {
        try {
            $data = $request->validated();
            
            $tag = Tag::create($data);
            
            return $this->successResponse(new TagResource($tag), __('messages.create_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}