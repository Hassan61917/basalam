<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\ModelServices\Shop\ProductService;
use Illuminate\Http\JsonResponse;

class AdminProductController extends Controller
{
    protected string $resource = ProductResource::class;

    public function __construct(
        private ProductService $productService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getProducts(["category"]);
        return $this->ok($this->paginate($products));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load("shop", "category");
        return $this->ok($product);
    }

    public function visible(Product $product): JsonResponse
    {
        $this->productService->hide($product, true);
        return $this->ok($product);
    }

    public function hide(Product $product): JsonResponse
    {
        $this->productService->hide($product, false);
        return $this->ok($product);
    }

    public function update(Product $product, UserProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product->update($data);
        return $this->ok($product);
    }

    public function delete(Product $product): JsonResponse
    {
        $product->delete();
        return $this->deleted();
    }
}
