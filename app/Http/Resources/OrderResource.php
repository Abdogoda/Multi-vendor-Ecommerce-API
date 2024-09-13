<?php

namespace App\Http\Resources;

use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{

    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'order_date' => $this->order_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'shipping_address' => $this->shipping_address,
            'user' => new UserResource($this->whenLoaded('user')),
            'items' => OrderItemsResource::collection($this->whenLoaded('items')),
        ];
    }
}