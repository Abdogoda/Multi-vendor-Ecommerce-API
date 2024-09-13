<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        if ($request->query('with_langs')) {
            $shop_name = [
                'en' => $this->shop_name_en,
                'ar' => $this->shop_name_ar,
            ];
        } else {
            $shop_name = (app()->getLocale() == 'ar') ? ($this->shop_name_ar ?? $this->shop_name_en) : $this->shop_name_en;
        }
        return [
            'shop_id' => $this->id,
            'shop_name' => $shop_name,
            'shop_address' => $this->shop_address,
            'shop_phone' => $this->shop_phone,
            'shop_website' => $this->shop_website,
            'shop_logo' => $this->shop_logo ? asset('storage/' . $this->shop_logo) : null,
            'shop_description' => $this->description,
            'owner' => new ProductResource($this->whenLoaded('user')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}