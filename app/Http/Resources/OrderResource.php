<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Order $resource
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->name,
            'email_address' => $this->resource->email_address,
            'phone_number' => $this->resource->phone_number,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'items' => OrderItemResource::collection($this->resource->orderItems),
            'user' => $this->resource->user ?? null,
        ];
    }
}
