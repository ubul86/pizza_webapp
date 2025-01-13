<?php

namespace App\Http\Resources;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

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

        $imageServiceForFirstImage = app(ImageService::class);

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

                $imageService = app(ImageService::class)
                    ->setPath($image->path)
                    ->setType($image->type);

                return [
                    'id' => $image->id,
                    'presets' => $this->getImagePresets($image, $imageService),
                    'first' => $image->pivot->first,
                ];
            }),
            'firstImage' => $this->resource->images->firstWhere('pivot.first', true) ? [
                'presets' => $this->getImagePresets($this->resource->images->firstWhere('pivot.first', true), $imageServiceForFirstImage),
                'first' => 1,
            ] : null,
        ];
    }

    private function getImagePresets(Image $image, ImageService $imageService): array
    {
        $imageService->setPath($image->path)
            ->setType($image->type);

        return [
            'four_small' => $imageService->setPreset('four_small')->build(),
            'actual_small' => $imageService->setPreset('actual_small')->build(),
            'small' => $imageService->setPreset('small')->build(),
            'big' => $imageService->setPreset('big')->build(),
        ];
    }
}
