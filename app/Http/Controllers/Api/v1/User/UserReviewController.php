<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Resources\v1\ReviewResource;
use App\Models\Review;
use App\ModelServices\Shop\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserReviewController extends ShopController
{
    protected string $resource = ReviewResource::class;
    protected ?string $ownerRelation = "order.shop";

    public function __construct(
        public ReviewService $reviewService
    )
    {
    }

    public function index(): JsonResponse
    {
        $reviews = $this->reviewService->getShopReviews($this->authUser()->shop);
        return $this->ok($this->paginate($reviews));
    }

    public function show(Review $review): JsonResponse
    {
        $review->load('user', "order");
        return $this->ok($review);
    }

    public function reply(Review $review, Request $request): JsonResponse
    {
        $data = $request->validate([
            "reply" => "required"
        ]);
        $review->update($data);
        return $this->ok($review);
    }
}
