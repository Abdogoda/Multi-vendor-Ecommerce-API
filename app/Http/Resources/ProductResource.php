<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource{
    public function toArray(Request $request): array{
        $locale = app()->getLocale();

        if ($request->query('with_langs')) {
            $name = [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ];
        } else {
            $name = ($locale === 'ar') ? ($this->name_ar ?? $this->name_en) : $this->name_en;
        }

        $data = [
            'id' => $this->id,
            'name' => $name,
            'price' => $this->price,
            'slug' => $this->slug,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'vendor_id' => $this->vendor_id,
            'discount_price' => $this->discount_price,
            'stock' => $this->stock,
            'status' => $this->status,
            'main_image' => $this->main_image ? asset('storage/'. $this->main_image) : null,
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'images' => ProducImageResource::collection($this->whenLoaded('images')),
        ];

        return $data;
    }
}