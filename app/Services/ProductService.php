<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /** @return EloquentCollection<int, Product> */
    public function index(): EloquentCollection
    {
        return $this->productRepository->index();
    }

    public function show(int $id): Product
    {
        try {
            return $this->productRepository->show($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data): Product
    {
        try {
            return $this->productRepository->store($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(array $data, int $id): Product
    {
        try {
            return $this->productRepository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            return $this->productRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function bulkDestroy(array $ids): bool
    {
        try {
            return $this->productRepository->bulkDestroy($ids);
        } catch (Exception $e) {
            throw $e;
        }
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
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                    $path = Storage::disk('s3')->putFileAs('product-images', $file, $filename);

                    $image = Image::create([
                        'path' => $path,
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
}
