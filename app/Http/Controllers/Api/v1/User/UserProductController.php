<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserProductOptionRequest;
use App\Http\Requests\v1\User\UserProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\ModelServices\Shop\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use League\Uri\Idna\Option;

class UserProductController extends AuthUserController
{
    protected string $resource = ProductResource::class;
    protected ?string $ownerRelation = "shop";

    public function before(?Model $model): void
    {
        if (!$this->authUser()->shop) {
            throw new AuthorizationException("you must be a shop owner");
        }
        parent::before($model);

    }

    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function index(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $products = $shop->products;
        return $this->ok($this->paginate($products));
    }

    public function store(UserProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productService->makeProduct($this->authUser()->shop, $data);
        $product->load("shop", "category");
        return $this->ok($product);
    }

    public function addOption(UserProductOptionRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        $product->options()->create($data);
        return $this->ok($product);
    }
    public function removeOption(Product $product, Option $option): JsonResponse
    {
        $product->options()->delete($option);
        return $this->ok($product);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load("category");
        return $this->ok($product);
    }

    public function available(Product $product): JsonResponse
    {
        $this->productService->available($product, true);
        return $this->ok($product);
    }

    public function unavailable(Product $product): JsonResponse
    {
        $this->productService->available($product, false);
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
