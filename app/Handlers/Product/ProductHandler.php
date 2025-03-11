<?php

namespace App\Handlers\Product;

use App\Enums\ShopStatus;
use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\Product;

class ProductHandler extends ModelHandler
{
    protected array $rules = [
        "shop", "category",
    ];

    public function shop(Product $product): void
    {
        if ($product->shop->status == ShopStatus::Suspend->value) {
            throw new ModelException("suspended shop can not have new products");
        }
    }

    public function category(Product $product): void
    {
        $shopCategory = $product->shop->category;
        if ($shopCategory && $product->category_id != $shopCategory->id) {
            if (!$shopCategory->isMyChild($product->category)) {
                throw new ModelException("product category is not valid category");
            }
        }
    }
}
