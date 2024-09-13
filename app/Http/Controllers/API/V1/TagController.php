<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TagController extends Controller{
    
    use ApiResponseTrait;

    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        
        $tags = Tag::paginate($perPage);
        return $this->successResponse(TagResource::collection($tags), __('messages.retrieve_success'));
    }

    public function show(Tag $tag, Request $request) {
        if ($request->query('include_products')) {
            $tag->load('products');
        }
        
        return $this->successResponse(new TagResource($tag), __('messages.retrieve_success'));
    }
}