<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserAdsOrderRequest;
use App\Http\Resources\v1\AdsOrderResource;
use App\Models\AdvertiseOrder;
use App\ModelServices\Ads\AdsService;
use Illuminate\Http\JsonResponse;

class AdminAdsOrderController extends Controller
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
        $ads = $this->adsService->getOrders(["ads"]);
        return $this->ok($this->paginate($ads));
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvertiseOrder $order): JsonResponse
    {
        $this->adsService->updateShowingTime($order);
        $order->load('ads', "user");
        return $this->ok($order);
    }
    public function start(AdvertiseOrder $order): JsonResponse
    {
        $order->load('ads', "user");
        $this->adsService->showOrder($order);
        return $this->ok($order);
    }
    public function cancel(AdvertiseOrder $order): JsonResponse
    {
        $order->load('ads', "user");
        $this->adsService->cancelOrder($order);
        return $this->ok($order);
    }
    public function complete(AdvertiseOrder $order): JsonResponse
    {
        $order->load('ads', "user");
        $this->adsService->completeOrder($order);
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
