<?php

namespace App\Handlers\Discount;

use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Auth\Access\AuthorizationException;

class DiscountCreateHandler extends ModelHandler
{
    protected array $rules = ["shop", "product", "commission", "category"];

    public function shop(Discount $discount): void
    {
        $shop = $discount->shop;
        if ($shop && !$discount->user()->is($shop->user)) {
            throw new AuthorizationException("shop doesn't belong to this user");
        }
    }

    public function product(Discount $discount): void
    {
        $shop = $discount->shop;
        if ($shop && !$shop->products()->find($discount->product_id)) {
            throw new ModelException("shop doesn't have this product");
        }
    }

    public function commission(Discount $discount): void
    {
        $product = $discount->product;
        if ($discount->shop || $product) {
            $parentCategory = $product->category->getLastParent();
            $commission = $parentCategory->commission;
            $limit_discount = $commission->getAmount($product->price);
            if (
                $discount->amount < $limit_discount ||
                $discount->max_amount < $limit_discount ||
                $discount->percent + $commission->percent > 100
            ) {
                throw new ModelException("discount must be less then commission rate");
            }
        }
    }
    public function category(Discount $discount): void
    {
        $category = $discount->category;
        if ($category) {
            $shopCategory = $discount->shop->category;
            $productCategory = $discount->product->category;
            if (
                !$this->isCategoryValid($category, $shopCategory) ||
                !$this->isCategoryValid($category, $productCategory)
            ) {
                throw new ModelException("category is not valid");
            }
        }
    }

    private function isCategoryValid(Category $parent, ?Category $child): bool
    {
        return $child && $parent->isMyChild($child);
    }
}
