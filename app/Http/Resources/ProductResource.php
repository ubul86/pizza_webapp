<?php

namespace App\Http\Resources;

use App\Services\GlideImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

/**
 * @property Product $resource
 */
class ProductResource extends JsonResource
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
            'category' => $this->resource->category ? $this->resource->category->name : null,
            'category_id' => $this->resource->category_id,
            'price' => $this->resource->price,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'images' => $this->resource->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'path' => Storage::url($image->path),
                    'presets' => [
                        'four_small' => GlideImageService::getGlideImagePath($image->path, 'p=four_small'),
                        'actual_small' => GlideImageService::getGlideImagePath($image->path, 'p=actual_small'),
                        'small' => GlideImageService::getGlideImagePath($image->path, 'p=small'),
                        'big' => GlideImageService::getGlideImagePath($image->path, 'p=big'),
                    ],
                    'first' => $image->pivot->first,
                ];
            }),
            'firstImage' => $this->resource->images->firstWhere('pivot.first', true) ? [
                'path' => Storage::url($this->resource->images->firstWhere('pivot.first', true)->path),
                'first' => 1,
            ] : null,
        ];
    }
}
