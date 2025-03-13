<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Enums\AdsStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserLadderOrderRequest;
use App\Http\Resources\v1\LadderOrderResource;
use App\Models\AdsOrder;
use App\Models\LadderOrder;
use App\ModelServices\Ads\LadderService;
use Illuminate\Http\JsonResponse;

class AdminLadderOrderController extends Controller
{
    protected string $resource = LadderOrderResource::class;

    public function __construct(
        private LadderService $ladderService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $orders = $this->ladderService->getOrders(["ladder"]);
        return $this->ok($this->paginate($orders));
    }

    /**
     * Display the specified resource.
     */
    public function show(LadderOrder $order): JsonResponse
    {
        $this->ladderService->updateShowingTime($order);
        $order->load(['user', 'item.service', "ladder"]);
        return $this->ok($order);
    }
    public function complete(LadderOrder $order): JsonResponse
    {
        $order->load('ads', "order");
        $this->ladderService->showOrder($order);
        return $this->ok($order);
    }
    public function cancel(LadderOrder $order): JsonResponse
    {
        $this->ladderService->cancelOrder($order);
        return $this->ok($order);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UserLadderOrderRequest $request, LadderOrder $order): JsonResponse
    {
        $data = $request->dontUnset()->validated();
        $order->update($data);
        return $this->ok($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LadderOrder $order): JsonResponse
    {
        if ($order->status == AdsStatus::Showing->value) {
            $this->ladderService->cancelOrder($order);
        }
        $order->delete();
        return $this->deleted();
    }
}
