<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        $withLang = $request->query('with_langs'); // Check if 'with_langs' query parameter is present

        if ($withLang) {
            $name = [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ];
        } else {
            $name = ($locale === 'ar') ? ($this->name_ar ?? $this->name_en) : $this->name_en;
        }
        return [
            'id' => $this->id,
            'name' => $name,
            'products' => ProductResource::collection($this->whenLoaded('products')), // Include products if they are loaded
        ];
    }
}