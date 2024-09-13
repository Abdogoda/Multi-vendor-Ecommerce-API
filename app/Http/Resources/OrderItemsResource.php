<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource{
    public function toArray(Request $request): array{
        $data = [
            'order_quantity' => $this->pivot->quantity,
            'order_price' => $this->pivot->price,
        ];
        if(!$request->query('include_items_product')){
            $data['product_id'] = $this->id;
        }else{
            $data['product'] = new ProductResource($this);
        }

        return $data;
    }
}