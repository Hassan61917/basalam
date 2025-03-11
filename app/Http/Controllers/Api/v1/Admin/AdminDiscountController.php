<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\Admin\AdminDiscountRequest;
use App\Http\Resources\v1\DiscountResource;
use App\Models\Discount;
use App\ModelServices\Financial\DiscountService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class AdminDiscountController extends AuthUserController
{
    protected string $resource = DiscountResource::class;

    public function __construct(
        private DiscountService $discountService
    )
    {
    }

    public function before(?Model $model): void
    {
        if ($model?->shop) {
            throw new AuthorizationException("admin can not modify shop discounts");
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $discounts = $this->discountService->getAll();
        return $this->ok($this->paginate($discounts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminDiscountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $discount = $this->discountService->makeFor($this->authUser(), $data);
        return $this->ok($discount);
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount): JsonResponse
    {
        $discount->load("category", "client", "user");
        return $this->ok($discount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminDiscountRequest $request, Discount $discount): JsonResponse
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
