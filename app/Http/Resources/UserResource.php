<?php

namespace App\Http\Resources;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'role' => $this->role,
            'status' => $this->status,
            'orders' => OrderResource::collection($this->whenLoaded('orders')), // Include orders if they are loaded
        ];
    }
}