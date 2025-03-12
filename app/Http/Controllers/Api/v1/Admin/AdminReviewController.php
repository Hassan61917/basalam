<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserReviewRequest;
use App\Http\Resources\v1\ReviewResource;
use App\Models\Review;
use App\ModelServices\Shop\ReviewService;
use Illuminate\Http\JsonResponse;

class AdminReviewController extends Controller
{
    protected string $resource = ReviewResource::class;

    public function __construct(
        private ReviewService $reviewService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reviews = $this->reviewService->getAllReviews();
        return $this->ok($this->paginate($reviews));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): JsonResponse
    {
        $review->load("user","order");
        return $this->ok($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserReviewRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());
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
