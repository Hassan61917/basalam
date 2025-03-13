<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserAdsOrderRequest;
use App\Http\Resources\v1\AdsOrderResource;
use App\Models\AdvertiseOrder;
use App\ModelServices\Ads\AdsService;
use Illuminate\Http\JsonResponse;

class UserAdsOrderController extends AuthUserController
{
    protected string $resource = AdsOrderResource::class;

    public function __construct(
        private AdsService $adsService
    )
    {
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $ads = $this->adsService->getOrdersFor($this->authUser(), ["ads"]);
        return $this->ok($this->paginate($ads));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAdsOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $order = $this->adsService->makeOrder($this->authUser(), $data);
        return $this->ok($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvertiseOrder $order): JsonResponse
    {
        $this->adsService->updateShowingTime($order);
        $order->load('ads');
        return $this->ok($order);
    }
    public function cancel(AdvertiseOrder $order): JsonResponse
    {
        $this->adsService->cancelOrder($order);
        return $this->ok($order);
    }

    public function update(UserAdsOrderRequest $request, AdvertiseOrder $order): JsonResponse
    {
        $data = $request->validated();
        $order->update($data);
        return $this->ok($order);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvertiseOrder $order): JsonResponse
    {
        $order->delete();
        return $this->deleted();
    }
}
