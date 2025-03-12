<?php

namespace App\Http\Controllers\Api\v1\Client;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserReviewRequest;
use App\Http\Resources\v1\ReviewResource;
use App\Models\Review;
use App\ModelServices\Shop\ReviewService;
use Illuminate\Http\JsonResponse;

class ClientReviewController extends AuthUserController
{
    protected string $resource = ReviewResource::class;

    public function __construct(
        public ReviewService $reviewService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $reviews = $this->reviewService->getMyReviews($this->authUser());
        return $this->ok($this->paginate($reviews));
    }

    public function store(UserReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $review = $this->reviewService->make($this->authUser(), $data);
        return $this->ok($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): JsonResponse
    {
        $review->load('order');
        return $this->ok($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserReviewRequest $request, Review $review): JsonResponse
    {
        $data = $request->validated();
        $review->update($data);
        return $this->ok($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();
        return $this->deleted();
    }
}
