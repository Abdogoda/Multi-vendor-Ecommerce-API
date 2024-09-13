<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource{
    

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        if ($request->query('with_langs')) {
            $name = [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ];
        } else {
            $name = (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
        }
        
        return [
            'id' => $this->id,
            'name' => $name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon ? asset('storage/' . $this->icon) : null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'parent_id' => $this->parent_id,
            'children' => CategoryResource::collection($this->whenLoaded('children')), // Include children if they are loaded
            'products' => ProductResource::collection($this->whenLoaded('products')), // Include products if they are loaded
        ];
    }
}