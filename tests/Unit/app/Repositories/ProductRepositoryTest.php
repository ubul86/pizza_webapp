<?php

namespace Tests\Unit;

use App\Models\Category;
use Tests\TestCase;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class ProductRepositoryTest extends TestCase
{
    protected ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
    }

    public function testReturnASpecificProduct(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $retrievedProduct = $this->repository->show($product->id);

        $this->assertInstanceOf(Product::class, $retrievedProduct);
        $this->assertEquals($product->id, $retrievedProduct->id);
    }

    public function testThrowsExceptionWhenProductNotFound(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->show(-1);
    }

    public function testCreateProduct(): void
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'price' => 10.0
        ];

        $product = $this->repository->store($data);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
    }

    public function testUpdateProduct(): void
    {
        Category::factory()->create();
        $product = Product::factory()->create();

        $updatedData = ['description' => 'Updated Product Description'];

        $updatedProduct = $this->repository->update($product->id, $updatedData);

        $this->assertDatabaseHas('products', ['description' => 'Updated Product Description']);
        $this->assertEquals('Updated Product Description', $updatedProduct->description);
    }

    public function testDeleteProduct(): void
    {
        Category::factory()->create();
        $product = Product::factory()->create();
        $id = $product->id;
        $result = $this->repository->destroy($product->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $id]);
    }

    public function testBulkDeleteProducts(): void
    {
        Category::factory()->create();
        $products = Product::factory()->count(5)->create();

        $ids = $products->pluck('id')->toArray();

        $result = $this->repository->bulkDestroy($ids);

        $this->assertTrue($result);
        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    }
}
