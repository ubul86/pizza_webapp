<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\traits\Controllers\ProductControllerTrait;

class ProductController extends Controller
{
    use ProductControllerTrait;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
}
