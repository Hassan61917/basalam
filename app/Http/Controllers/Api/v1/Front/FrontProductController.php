<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\ModelServices\Shop\ProductService;
use App\ModelServices\Social\VisitService;
use Illuminate\Http\JsonResponse;

class FrontProductController extends Controller
{
    protected string $resource = ProductResource::class;

    public function __construct(
        private ProductService $productService,
        private VisitService   $visitService
    )
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->getAvailableProducts(["category", "shop"]);
        return $this->ok($this->paginate($products));
    }

    public function show(Product $product): JsonResponse
    {
        $this->visitService->visit($product, $this->authUser(), request()->ip());
        $product->load("category", "shop", "options", "reviews", "questions")
            ->loadCount("visits", "likes", "dislikes", "wishlist");
        return $this->ok($product);
    }
}
