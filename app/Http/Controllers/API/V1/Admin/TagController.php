<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponseTrait;
use Exception;

class TagController extends Controller{
    
    use ApiResponseTrait;
    
    
    public function update(UpdateTagRequest $request, Tag $tag) {
        try {
            $data = $request->validated();
            
            $tag->update($data);
            
            return $this->successResponse(new TagResource($tag), __('messages.update_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    
    public function destroy(Tag $tag) {
        try {
            if($tag->products()->exists()){
                return $this->errorResponse(__('messages.delete_failed_related_data'), 409);
            }
            
            $tag->delete();
            
            return $this->successResponse(__('messages.delete_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}