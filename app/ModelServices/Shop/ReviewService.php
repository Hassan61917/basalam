<?php

namespace App\ModelServices\Shop;

use App\Enums\OrderItemStatus;
use App\Exceptions\ModelException;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewService
{
    public function getAllReviews(array $relations = []): Builder
    {
        return Review::query()->with($relations);
    }

    public function getMyReviews(User $user, array $relations = []): HasMany
    {
        return $user->reviews()->with($relations);
    }

    public function getShopReviews(Shop $shop, array $relations = []): Builder
    {
        return Review::query()
            ->select("reviews.*")
            ->join("order_items", "order_items.id", "=", "reviews.order_id")
            ->where("order_items.shop_id", $shop->id)
            ->with($relations);
    }

    public function getProductReviews(Product $product, array $relations = []): Builder
    {
        return Review::query()
            ->select("reviews.*")
            ->join("order_items", "order_items.id", "=", "reviews.order_id")
            ->where("order_items.product_id", $product->id)
            ->with($relations);
    }

    public function make(User $user, array $data)
    {
        $item = OrderItem::find($data["order_id"]);
        if (!$user->is($item->user)) {
            throw new ModelException("You only can review for your own orders.");
        }
        if ($item->status != OrderItemStatus::Completed->value) {
            throw new ModelException("you must complete order before making review");
        }
        return $user->reviews()->create($data);
    }


}
