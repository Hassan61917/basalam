<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Exceptions\ModelException;
use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserShopRequest;
use App\Http\Resources\v1\ShopResource;
use App\ModelServices\Shop\ShopService;
use Illuminate\Http\JsonResponse;

class UserShopController extends AuthUserController
{
    protected string $resource = ShopResource::class;
    protected ?string $ownerRelation = null;

    public function __construct(
        private ShopService $shopService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $shop->load("category");
        return $this->ok($shop);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserShopRequest $request): JsonResponse
    {
        $data = $request->validated();
        $shop = $this->shopService->make($this->authUser(), $data);
        $shop->load("user", "category");
        return $this->ok($shop);
    }

    public function open(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $this->shopService->open($shop);
        return $this->ok($shop);
    }

    public function close(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $this->shopService->close($shop);
        return $this->ok($shop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserShopRequest $request): JsonResponse
    {
        if (!$this->hasShop()) {
            throw new ModelException("you have not created a shop yet");
        }
        $data = $request->validated();
        $shop = $this->shopService->update($this->authUser()->shop, $data);
        $shop->load("category");
        return $this->ok($shop);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        if (!$this->hasShop()) {
            throw new ModelException("you have not created a shop yet");
        }
        $this->shopService->delete($this->authUser()->shop);
        return $this->deleted();
    }

    private function hasShop(): bool
    {
        return $this->shopService->hasShop($this->authUser());
    }
}
