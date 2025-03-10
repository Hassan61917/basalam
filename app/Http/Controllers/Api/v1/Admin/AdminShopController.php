<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserShopRequest;
use App\Http\Resources\v1\ShopResource;
use App\Models\Shop;
use App\ModelServices\Shop\ShopService;
use Illuminate\Http\JsonResponse;

class AdminShopController extends Controller
{
    protected string $resource = ShopResource::class;

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
        $shops = $this->shopService->getAllShop(["category"]);
        return $this->ok($this->paginate($shops));
    }
    public function suspend(Shop $shop): JsonResponse
    {
        $this->shopService->suspend($shop,true);
        return $this->ok($shop);
    }
    public function unsuspend(Shop $shop): JsonResponse
    {
        $this->shopService->suspend($shop,false);
        return $this->ok($shop);
    }
    /*
     * Display the specified resource.
     */
    public function show(Shop $shop): JsonResponse
    {
        $shop->load("user", "category");
        return $this->ok($shop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserShopRequest $request, Shop $shop): JsonResponse
    {
        $data = $request->validated();
        $this->shopService->update($shop, $data);
        return $this->ok($shop);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop): JsonResponse
    {
        $this->shopService->delete($shop);
        return $this->deleted();
    }
}
