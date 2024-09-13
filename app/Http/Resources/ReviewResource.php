<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'product' => new ProductResource($this->whenLoaded('product')), // Include product if it is loaded
            'vendor' => new VendorResource($this->whenLoaded('vendor')), // Include vendor if it is loaded
            'user' => new UserResource($this->whenLoaded('user')), // Include user if it is loaded
        ];
    }
}