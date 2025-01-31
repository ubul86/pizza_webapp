<?php

namespace App\Services;

use App\Factories\ImageUploaderFactory;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductImageService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function uploadImages(int $itemId, array $files): Product
    {
        try {
            $product = Product::find($itemId);

            if (!$product) {
                throw new ModelNotFoundException("Product with ID {$itemId} not found.");
            }

            $imageIds = [];

            if ($files) {
                foreach ($files as $file) {
                    $uploader = ImageUploaderFactory::createUploader();
                    $path = $uploader->upload($file);

                    $image = Image::create([
                        'path' => $path,
                        'type' => Image::TYPE_S3
                    ]);

                    $imageIds[] = $image->id;
                }
            }

            $product->images()->attach($imageIds);

            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function setImageToFirst(int $productId, int $imageId): bool
    {
        try {
            ProductImage::where('product_id', $productId)
                ->update(['first' => 0]);

            ProductImage::where('product_id', $productId)
                ->where('image_id', $imageId)
                ->update(['first' => 1]);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteImage(int $productId, int $imageId): bool
    {
        try {
            return DB::transaction(function () use ($productId, $imageId) {

                ProductImage::where('product_id', $productId)
                    ->where('image_id', $imageId)
                    ->delete();

                $isImageUsedElsewhere = ProductImage::where('image_id', $imageId)->exists();

                if (!$isImageUsedElsewhere) {
                    $imageToDelete = Image::findOrFail($imageId);

                    $imageToDelete->delete();

                    $uploader = ImageUploaderFactory::createUploader();
                    $uploader->delete($imageToDelete->path);
                }

                return true;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }
}
