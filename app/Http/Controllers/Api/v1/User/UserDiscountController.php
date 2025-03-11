<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserDiscountRequest;
use App\Http\Resources\v1\DiscountResource;
use App\Models\Discount;
use App\ModelServices\Financial\DiscountService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UserDiscountController extends AuthUserController
{
    protected string $resource = DiscountResource::class;
    protected ?string $ownerRelation = "shop";

    public function __construct(
        private DiscountService $discountService
    )
    {
    }

    public function before(?Model $model): void
    {
        if (!$this->authUser()->shop) {
            throw new AuthorizationException("you must be a shop owner");
        }
        parent::before($model);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $discounts = $this->discountService->getAllFor($this->authUser());
        return $this->ok($this->paginate($discounts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserDiscountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data["shop_id"] = $this->authUser()->shop->id;
        $discount = $this->discountService->makeFor($this->authUser(), $data);
        return $this->ok($discount);
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount): JsonResponse
    {
        $discount->load(["category", "client", "product"]);
        return $this->ok($discount);
    }

    public function expire(Discount $discount): JsonResponse
    {
        $this->discountService->expire($discount);
        return $this->ok($discount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserDiscountRequest $request, Discount $discount): JsonResponse
    {
        $data = $request->validated();
        $discount->update($data);
        return $this->ok($discount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();
        return $this->deleted();
    }
}
