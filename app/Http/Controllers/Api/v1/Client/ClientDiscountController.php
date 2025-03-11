<?php

namespace App\Http\Controllers\Api\v1\Client;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\DiscountResource;
use App\Models\Discount;
use App\ModelServices\Financial\DiscountService;
use Illuminate\Http\JsonResponse;

class ClientDiscountController extends AuthUserController
{
    protected string $resource = DiscountResource::class;
    protected ?string $ownerName = "client";
    public function __construct(
        private DiscountService $discountService
    )
    {
    }

    public function used(): JsonResponse
    {
        $discounts = $this->discountService->getUsedDiscounts($this->authUser());
        return $this->ok($this->paginate($discounts));
    }

    public function index(): JsonResponse
    {
        $discounts = $this->discountService->getMyDiscounts($this->authUser());
        return $this->ok($this->paginate($discounts));
    }
    public function show(Discount $discount): JsonResponse
    {
        $discount->load("category", "user", "shop", "product");
        return $this->ok($discount);
    }
}
