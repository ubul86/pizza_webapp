<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderItem;

/**
 * @property OrderItem $resource
 */
class OrderItemResource extends JsonResource
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
            'order_id' => $this->resource->order_id,
            'product_id' => $this->resource->product_id,
            'price' => $this->resource->price,
            'quantity' => $this->resource->quantity,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'product' => $this->resource->product ? new ProductResource($this->resource->product) : null,
        ];
    }
}
