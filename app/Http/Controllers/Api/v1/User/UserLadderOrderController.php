<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Enums\ShowStatus;
use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserLadderOrderRequest;
use App\Http\Resources\v1\LadderOrderResource;
use App\Models\LadderOrder;
use App\ModelServices\Ads\LadderService;
use Illuminate\Http\JsonResponse;

class UserLadderOrderController extends AuthUserController
{
    protected string $resource = LadderOrderResource::class;

    public function __construct(
        private LadderService $ladderService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $orders = $this->ladderService->getOrdersFor($this->authUser(), ["ladder"]);
        return $this->ok($this->paginate($orders));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserLadderOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $order = $this->ladderService->makeOrder($this->authUser(), $data);
        return $this->ok($order);
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

    public function cancel(LadderOrder $order): JsonResponse
    {
        if ($order->status != ShowStatus::Waiting->value) {
            return $this->error(422, "order cannot be cancelled");
        }
        $order = $this->ladderService->cancelOrder($order);
        return $this->ok($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserLadderOrderRequest $request, LadderOrder $order): JsonResponse
    {
        if ($order->status != ShowStatus::Waiting->value) {
            return $this->error(422, "item can be changed only when it is been waiting");
        }
        $data = $request->validated();
        $order->update($data);
        return $this->ok($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LadderOrder $order): JsonResponse
    {
        if ($order->status == ShowStatus::Showing->value) {
            return $this->error(422, "order can not be deleted when it is been showing");
        }
        $order->delete();
        return $this->deleted();
    }
}
