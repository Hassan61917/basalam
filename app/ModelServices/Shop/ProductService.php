<?php

namespace App\ModelServices\Shop;

use App\Handlers\Product\ProductHandler;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function __construct(
        private ProductHandler $productHandler
    )
    {
    }

    public function getAvailableProducts(array $relations = []): Builder
    {
        return Product::query()->available()->with($relations);
    }

    public function getProducts(array $relations = []): Builder
    {
        return Product::query()->withHidden()->with($relations);
    }

    public function makeProduct(Shop $shop, array $data): Product
    {
        $data["available"] = $data["available"] ?? true;
        $data["hidden"] = $data["hidden"] ?? false;
        $options = $data["options"] ?? [];
        $product = $shop->products()->make($data);
        $this->productHandler->handle($product);
        $product->addOptions($options);
        $product->save();
        return $product;
    }

    public function available(Product $product, bool $available): void
    {
        $product->update(["available" => $available]);
    }

    public function hide(Product $product, bool $hidden): void
    {
        $product->update(["hidden" => $hidden]);
    }
}
